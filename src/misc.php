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
function changeDefaultOwnerPwd()
{
    /* Set new password */
    $newPassword = getPasswd('['.@date('H:i:s').'] New Password: ');

    /* when password to short */
    while (strlen($newPassword) < 8) {
        echo N.'['.@date('H:i:s').'] Password too short, password must be at least 8 characters long'.N;
        unset($newPassword);
        $newPassword = getPasswd('['.@date('H:i:s').'] New Password: ');
    }

    /* join spaces in password */
    $newPassword = str_replace(' ', '', $newPassword);

    /* hash pwd */
    $hashed = hash('sha256', $newPassword);

    /* save pwd to file */
    SaveValueToConfigFile('OWNER', 'owner.password', $hashed);

    /* Set first time change variable */
    $GLOBALS['defaultPwdChanged'] = 'yes'; //- to wyjebać i zmienić na funkcje !!!
    
    echo N;
    cliLog('[bot] Password changed!'.N);
}
//---------------------------------------------------------------------------------------------------------
function SaveValueToConfigFile($v1, $v2, $v3)
{
    $cfg = new IniParser(getConfigFileName());
    $cfg->setValue($v1, $v2, $v3);
    $cfg->save();
}
//---------------------------------------------------------------------------------------------------------
/* if first arg after !plugin <arg> is empty */
function OnEmptyArg($information)
{
    if (empty(msgAsArguments())) {
        response("Usage: ".loadValueFromConfigFile('COMMAND', 'command.prefix')."{$information}");
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
    $a = rawDataArray();
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
    for ($i=3; $i <= (count(rawDataArray())); $i++) {
         $input .= rawDataArray()[$i]." ";
    }

    $in = rtrim($input);
    $data = str_replace($GLOBALS['rawcmd'][1].' ', '', $in);

    return $data;
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
function PlaySound($sound)
{
    if (loadValueFromConfigFile('PROGRAM', 'play.sounds') == 'yes' && ifWindowsOs()) {
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
    if (ifWindowsOs()) {
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
    if (ifWindowsOs()) {
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
 
    if (ifWindowsOs()) {
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
    $botID = hash('sha256', $ip.php_uname().getBotNickname().loadValueFromConfigFile('BOT', 'name').
                  loadValueFromConfigFile('BOT', 'ident').loadValueFromConfigFile('SERVER', 'server').VER);

    @file($statsAddress."stamp={$botID}&nick=".getBotNickname()."&server=".loadValueFromConfigFile('SERVER', 'server')."&ver={VER}");
}
//---------------------------------------------------------------------------------------------------------
function WinSleep($time)
{
    if (ifWindowsOs()) {
        sleep($time);
    }
}
//---------------------------------------------------------------------------------------------------------
function removeIllegalCharsFromNickname($nickname)
{
    /* illegal chars for file */
    $bad  = [chr(0x5c), '/', ':', '*', '?', '"', '<', '>', '|'];
    $good = ["@[1]", "@[2]", "@[3]", "@[4]", "@[5]", "@[6]", "@[7]", "@[8]", "@[9]"];
    
    return str_replace($bad, $good, $nickname);
}
//---------------------------------------------------------------------------------------------------------
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}
//---------------------------------------------------------------------------------------------------------
function runProgram($command)
{
    $descriptorspec = array(
          0 => array("pipe", "r"),
          1 => array("pipe", "w"),
          2 => array("pipe", "w")
    );

    $process = proc_open($command, $descriptorspec, $pipes);
}
