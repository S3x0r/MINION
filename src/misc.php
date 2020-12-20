<?php
/* Copyright (c) 2013-2020, S3x0r <olisek@gmail.com>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

//---------------------------------------------------------------------------------------------------------
 !in_array(PHP_SAPI, array('cli', 'cli-server', 'phpdbg')) ?
  exit('This script can\'t be run from a web browser. Use CLI terminal to run it<br>'.
       'Visit <a href="https://github.com/S3x0r/MINION/">this page</a> for more information.') : false;
//---------------------------------------------------------------------------------------------------------

function SaveData($v1, $v2, $v3, $v4)
{
    $cfg = new IniParser($v1);
    $cfg->setValue("$v2", "$v3", "$v4");
    $cfg->save();
}
//---------------------------------------------------------------------------------------------------------
function LoadData($configFile, $section, $config)
{
    $cfg = new IniParser($configFile);
    $GLOBALS['LOADED'] = $cfg->get("$section", "$config");
}
//---------------------------------------------------------------------------------------------------------
function CheckUpdateInfo()
{
    /* check if new version on server */
    if ($GLOBALS['CONFIG_CHECK_UPDATE'] == 'yes' && !IsSilent()) {
        if (extension_loaded('openssl')) {
            $file = @file_get_contents(VERSION_URL);
        
            if (!empty($file)) {
                $serverVersion = explode("\n", $file);
                if ($serverVersion[0] > VER) {
                    echo "             >>>> New version available! ($serverVersion[0]) <<<<".NN.N;
                } else {
                         echo "       >>>> No new update, you have the latest version <<<<".NN.N;
                }
            } else {
                     echo "            >>>> Cannot connect to update server <<<<".NN.N;
            }
        } else {
                 echo "   ! I cannot check update, i need: php_openssl extension to work!".NN.N;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
/* update users (OWNER,USER) array */
function UpdatePrefix($user, $newPrefix)
{
    $GLOBALS[$user.'_PLUGINS'] = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], $newPrefix, $GLOBALS[$user.'_PLUGINS']);
}
//---------------------------------------------------------------------------------------------------------
/* if first arg after !plugin <arg> is empty */
function OnEmptyArg($information)
{
    if (empty($GLOBALS['args'])) {
        response("Usage: {$GLOBALS['CONFIG_CMD_PREFIX']}{$information}");
        return true;
    } else {
              return false;
    }
}
//---------------------------------------------------------------------------------------------------------
/* sends info if bot is opped, true, false */
function BotOpped()
{
    if (isset($GLOBALS['BOT_OPPED'])) {
        return true;
    } else { 
             return false;
    }
}
//---------------------------------------------------------------------------------------------------------
function CountLines($exts = ['php'])
{
    $fpath = '.';
    $files = array();

    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fpath));
    foreach ($it as $file) {
        if ($file->isDir()) {
            continue;
        }
           $parts = explode('.', $file->getFilename());
           $extension = end($parts);
        if (in_array($extension, $exts)) {
            $files[$file->getPathname()] = count(file($file->getPathname()));
        }
    }
    return $files;
}
//---------------------------------------------------------------------------------------------------------
function TotalLines()
{
    return array_sum(CountLines());
}
//---------------------------------------------------------------------------------------------------------
function randomString($length)
{
    $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i=0; $i < $length; $i++) {
         $str .= substr($seed, mt_rand(0, strlen($seed) -1), 1);
    }
    return $str;
}
//---------------------------------------------------------------------------------------------------------
function inputFromLine($position)
{
    $a = $GLOBALS['rawDataArray'];
    $current = '';
    $index = $position;
    
    while (isset($a[$index])) {
           $current .= $a[$index].' ';
           $index++;
    }
    $string = preg_replace('/^:/', '', $current, 1);
    $string = substr($string, 0, -1);

    return $string;
}
//---------------------------------------------------------------------------------------------------------
function msg_without_command()
{
    $input = null;
    for ($i=3; $i <= (count($GLOBALS['rawDataArray'])); $i++) {
         $input .= $GLOBALS['rawDataArray'][$i]." ";
    }

    $in = rtrim($input);
    $data = str_replace($GLOBALS['rawcmd'][1].' ', '', $in);

    return $data;
}
//---------------------------------------------------------------------------------------------------------
function HasAdmin($mask) //TODO: wtf
{
    $admins_c = $GLOBALS['CONFIG_ADMIN_LIST'];
    $pieces = explode(", ", $admins_c);

    if ($mask == null) {
        return false;
    }
    foreach ($pieces as $piece) {
        if (fnmatch($piece, $mask, 16)) {
            return true;
            return false;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function HasOwner($mask) //TODO: wtf
{
    $owners_c = $GLOBALS['CONFIG_OWNERS'];
    $pieces = explode(", ", $owners_c);

    if ($mask == null) {
        return false;
    }
    foreach ($pieces as $piece) {
        if (fnmatch($piece, $mask, 16)) {
            return true;
            return false;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function SaveToFile($file, $data, $method)
{
    $file = @fopen($file, $method);
    @flock($file, 2);
    @fwrite($file, $data);
    @flock($file, 3);
    @fclose($file);
}
//---------------------------------------------------------------------------------------------------------
function IsSilent()
{
    if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
        return false;
    } else {
             return true;
    }
}
//---------------------------------------------------------------------------------------------------------
function PlaySound($sound)
{
    if (!IsSilent() && $GLOBALS['CONFIG_PLAY_SOUNDS'] == 'yes' && !isset($GLOBALS['OS'])) {
        if (is_file('src/php/play.exe') && is_file('src/sounds/'.$sound)) {
            $command = 'start /b src/php/play.exe src/sounds/'.$sound;
            pclose(popen($command, 'r'));
        } else {
                 echo "\x07";
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function kill($program)
{
    if (!isset($GLOBALS['OS'])) {
        $pattern = '~('.$program.')\.exe~i';
        $tasks = array();
        exec("tasklist 2>NUL", $tasks);

        foreach ($tasks as $task_line) {
            if (preg_match($pattern, $task_line, $out)) {
                exec("taskkill /F /IM ".$out[1].".exe 2>NUL");
                return true;
            }
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function isRunned($program)
{
    if (!isset($GLOBALS['OS'])) {
        $pattern = '~('.$program.')\.exe~i';
        $tasks = array();
        exec("tasklist 2>NUL", $tasks);

        foreach ($tasks as $task_line) {
            if (preg_match($pattern, $task_line, $out)) {
                return true;
            }
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function getPasswd($string = '')
{
    echo $string;
 
    if (!isset($GLOBALS['OS'])) {
        if (is_file('src\php\hide.exe')) {
            $psw = `src\php\hide.exe`;
        } else {
                 echo N;
                 echo '  ERROR: I need \'hide.exe\' file to run!'.N.N,
                      '  You can download missing files from:'.N,
                      '  https://github.com/S3x0r/MINION/releases'.N.N,
                      '  Terminating program after 10 seconds.'.N.N.'  ';
                 WinSleep(10);
                 exit;
        }
    } else {
             system('stty -echo');
             $psw = fgets(STDIN);
             system('stty echo');
    }
    return rtrim($psw, N);
}
//---------------------------------------------------------------------------------------------------------
function Statistics()
{
    $ipAddress    = 'http://minionki.com.pl/bot/ip.php';
    $statsAddress = 'http://minionki.com.pl/bot/stats.php?';

    $ip = @file_get_contents($ipAddress);
    
    /* identify bot session by hashed ip, operating system, bot nickname, name and ident */
    $botID = hash('sha256', $ip.php_uname().getBotNickname().$GLOBALS['CONFIG_NAME'].
                  $GLOBALS['CONFIG_IDENT'].$GLOBALS['CONFIG_SERVER'].VER);

    @file($statsAddress."stamp={$botID}&nick=".getBotNickname()."&server={$GLOBALS['CONFIG_SERVER']}&ver={VER}");
}
//---------------------------------------------------------------------------------------------------------
function WinSleep($time)
{
    !isset($GLOBALS['OS']) ? sleep($time) : false;
}
//---------------------------------------------------------------------------------------------------------
function removeIllegalCharsFromNickname($nickname)
{
    /* illegal chars for file */
    $bad  = [chr(0x5c), '/', ':', '*', '?', '"', '<', '>', '|'];
    $good = ["@[1]", "@[2]", "@[3]", "@[4]", "@[5]", "@[6]", "@[7]", "@[8]", "@[9]"];
    
    return str_replace($bad, $good, $nickname);
}
