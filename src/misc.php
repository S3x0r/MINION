<?php
/* Copyright (c) 2013-2017, S3x0r <olisek@gmail.com>
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

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}
//---------------------------------------------------------------------------------------------------------
function CheckUpdateInfo()
{
    /* check if new version on server */
    if ($GLOBALS['CONFIG_CHECK_UPDATE'] == 'yes' && !IsSilent()) {
        if (extension_loaded('openssl')) {
            $url = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
            $CheckVersion = @file_get_contents($url);
        
            if ($CheckVersion !='') {
                $version = explode("\n", $CheckVersion);
                if ($version[0] > VER) {
                    echo "             >>>> New version available! ($version[0]) <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
                } else {
                         echo "       >>>> No new update, you have the latest version <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
                }
            } else {
                     echo "            >>>> Cannot connect to update server <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
            }
        } else {
                 echo "   ! I cannot check update, i need: php_openssl extension to work !".PHP_EOL.PHP_EOL.PHP_EOL;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
/* update users (OWNER,USER) array */
function UpdatePrefix($user, $new_prefix)
{
    $GLOBALS[$user.'_PLUGINS'] = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], $new_prefix, $GLOBALS[$user.'_PLUGINS']);
}
//---------------------------------------------------------------------------------------------------------
/* if first arg after !plugin <arg> is empty */
function OnEmptyArg($info)
{
    if (empty($GLOBALS['args'])) {
        BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].''.$info);
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
/* sends bot channels array */
function GetBotChannels()
{
    return $GLOBALS['BOT_CHANNELS'];
}
//---------------------------------------------------------------------------------------------------------
function CountLines($exts = array('php'))
{
    $fpath = '../';
    $files=array();

    $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fpath));
    foreach ($it as $file) {
        if ($file->isDir()) {
            continue;
        }
           $parts = explode('.', $file->getFilename());
           $extension=end($parts);
        if (in_array($extension, $exts)) {
            $files[$file->getPathname()]=count(file($file->getPathname()));
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
function random_str($length)
{
    $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i=0; $i < $length; $i++) {
         $str .= substr($seed, mt_rand(0, strlen($seed) -1), 1);
    }
    return $str;
}
//---------------------------------------------------------------------------------------------------------
function parse_ex3($position)
{
    $a = $GLOBALS['ex'];
    $current = '';
    $index = $position;
    
    while (isset($a[$index])) {
           $current .= $a[$index].' ';
           $index++;
    }
    $b = preg_replace('/^:/', '', $current, 1);
    return $b;
}
//---------------------------------------------------------------------------------------------------------
function msg_without_command()
{
    $input = null;
    for ($i=3; $i <= (count($GLOBALS['ex'])); $i++) {
         $input .= $GLOBALS['ex'][$i]." ";
    }

    $in = rtrim($input);
    $data = str_replace($GLOBALS['rawcmd'][1].' ', '', $in);

    return $data;
}
//---------------------------------------------------------------------------------------------------------
function HasAdmin($mask)
{
    $admins_c = $GLOBALS['CONFIG_ADMIN_LIST'];
    $pieces = explode(", ", $admins_c);

    if ($mask == null) {
    }
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
function HasOwner($mask)
{
    $owners_c = $GLOBALS['CONFIG_OWNERS'];
    $pieces = explode(", ", $owners_c);

    if ($mask == null) {
    }
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
function SaveToFile($f1, $f2, $f3)
{
    $file = $f1;
    $data = $f2;
    $f=fopen($file, $f3);
    flock($f, 2);
    fwrite($f, $data);
    flock($f, 3);
    fclose($f);
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
    if (!IsSilent() && $GLOBALS['CONFIG_PLAY_SOUNDS'] == 'yes' && !isset($GLOBALS['OS_TYPE'])) {
        if (is_file('php/play.exe') && is_file('sounds/'.$sound)) {
            $command = 'start /b php/play.exe sounds/'.$sound;
            pclose(popen($command, 'r'));
        } else {
                 echo "\x07";
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function kill($program)
{
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
//---------------------------------------------------------------------------------------------------------
function isRunned($program)
{
    $pattern = '~('.$program.')\.exe~i';
    $tasks = array();
    exec("tasklist 2>NUL", $tasks);

    foreach ($tasks as $task_line) {
        if (preg_match($pattern, $task_line, $out)) {
            return true;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function Line($color)
{
    /* set color to line */
    if (extension_loaded('wcli')) {
        wcli_echo('------------------------------------------------------------------------------'.PHP_EOL, $color);
    } else {
             echo '------------------------------------------------------------------------------'.PHP_EOL;
    }
}
//---------------------------------------------------------------------------------------------------------
function Color($data, $color)
{
    if (extension_loaded('wcli')) {
        wcli_echo($data, $color);
    } else {
             echo $data;
    }
}
