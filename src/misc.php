<?php
/* Copyright (c) 2013-2024, minions
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
    $hashedPassword = hash('sha256', $newPassword);

    /* save pwd to file */
    saveValueToConfigFile('OWNER', 'owner password', $hashedPassword);
    
    unset($hashedPassword);

    cliNoLog('');
    cliBot('Owner\'s password updated!'.N);
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
                 winSleep(10);
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
function totalLines()
{
    return array_sum(CountLines());
}
//---------------------------------------------------------------------------------------------------------
function saveToFile($file, $data, $method)
{
    $file = @fopen($file, $method);
    @flock($file, 2);
    @fwrite($file, $data);
    @flock($file, 3);
    @fclose($file);
}
//---------------------------------------------------------------------------------------------------------
function playSound($sound)
{
    if (loadValueFromConfigFile('PROGRAM', 'play sounds') == true && ifWindowsOs()) {
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
function Statistics()
{
    global $connectedToServer;

    $ipAddress    = 'http://';
    $statsAddress = 'http://?';

    $ip = @file_get_contents($ipAddress);
    
    /* identify bot session by hashed ip, operating system, bot nickname, name and ident */
    $botID = hash('sha256', $ip.php_uname().getBotNickname().loadValueFromConfigFile('BOT', 'name').
                  loadValueFromConfigFile('BOT', 'ident').$connectedToServer.VER);

    @file($statsAddress."stamp={$botID}&nick=".getBotNickname()."&server=".$connectedToServer."&ver={VER}");
}
//---------------------------------------------------------------------------------------------------------
function winSleep($time)
{
    if (ifWindowsOs()) {
        sleep($time);
    }
}
//---------------------------------------------------------------------------------------------------------
function in_array_r($needle, $haystack, $strict = false)
{
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
//---------------------------------------------------------------------------------------------------------
function checkDirectioriesIfExists()
{
    /* if directories are missing create them */
    !is_dir(LOGSDIR) ? mkdir(LOGSDIR) : false;

    createLogsDateDir();
    
    !is_dir(DATADIR) ? mkdir(DATADIR) : false;

    !is_dir(DATADIR.'/'.SEENDIR) ? @mkdir(DATADIR.'/'.SEENDIR) : false;
}
//---------------------------------------------------------------------------------------------------------
function createLogsDateDir()
{
    if (is_dir(LOGSDIR)) {
        if (!is_dir(LOGSDIR.'/'.@date('d.m.Y'))) {
            mkdir(LOGSDIR.'/'.@date('d.m.Y')); 
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function setTimezone()
{
    if (!empty(loadValueFromConfigFile('TIME', 'timezone'))) {
        date_default_timezone_set(loadValueFromConfigFile('TIME', 'timezone'));
    }    
}
//---------------------------------------------------------------------------------------------------------
function ctrl_handler(int $event)
{
    switch ($event) {
        case PHP_WINDOWS_EVENT_CTRL_C:
            cliBot('You have pressed CTRL+C - Exiting');
            quitSeq();
            break;
        case PHP_WINDOWS_EVENT_CTRL_BREAK:
            cliBot('You have pressed CTRL+BREAK - Exiting');
            quitSeq();
            break;
    }
}
//---------------------------------------------------------------------------------------------------------
function quitSeq()
{
    toServer('QUIT :http://github.com/S3x0r/MINION');

    cliBot('Terminating BOT...');
    cliBot('------------------LOG ENDED: '.date('d.m.Y | H:i:s').'------------------'.N);

    sleep(4);
    exit;
}
//---------------------------------------------------------------------------------------------------------
function isEmptyFolder($dir): bool
{
    return is_dir($dir) && is_readable($dir) && scandir($dir) === ['.', '..'];
}
//---------------------------------------------------------------------------------------------------------
function IsfolderFromDayBeforeExisting()
{
    $date = @date('d.m.Y');
    $day_before = @date( 'd.m.Y', strtotime($date.' -1 day'));
    
    if (is_dir(LOGSDIR.'/'.$day_before)) {
        return true;
    } else {
             return false;
    }
}
