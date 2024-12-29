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

    cliNoLog();
    cliBot('Owner\'s password updated!'.N);
}
//---------------------------------------------------------------------------------------------------------
function getPasswd($_string = '')
{
    echo $_string;
 
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
        }
    } else {
             system('stty -echo');
             $psw = fgets(STDIN);
             system('stty echo');
    }

    return rtrim($psw, N);
}
//---------------------------------------------------------------------------------------------------------
function CountLines($_exts = ['php'])
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

       if (in_array($extension, $_exts)) {
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
function playSound($_sound)
{
    if (ifWindowsOs() && loadValueFromConfigFile('PROGRAM', 'play sounds')) {
        if (is_file('src/php/play.exe') && is_file('src/sounds/'.$_sound)) {
            $cmd = 'start /b src/php/play.exe src/sounds/'.$_sound;
            pclose(popen($cmd, 'r'));
        } else {
                 echo "\x07";
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function kill($_program)
{
    if (ifWindowsOs()) {
        $pattern = '~('.$_program.')\.exe~i';
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
function isRunned($_program)
{
    if (ifWindowsOs()) {
        $pattern = '~('.$_program.')\.exe~i';
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
function winSleep($_time)
{
    if (ifWindowsOs()) {
        sleep($_time);
        exit;
    }
}
//---------------------------------------------------------------------------------------------------------
function in_array_r($_needle, $_haystack, $_strict = false)
{
    foreach ($_haystack as $item) {
        if (($_strict ? $item === $_needle : $item == $_needle) || (is_array($item) && in_array_r($_needle, $item, $_strict))) {
            return true;
        }
    }

    return false;
}
//---------------------------------------------------------------------------------------------------------
function runProgram($_command)
{
    $descriptorspec = array(
          0 => array("pipe", "r"),
          1 => array("pipe", "w"),
          2 => array("pipe", "w")
    );

    $process = proc_open($_command, $descriptorspec, $pipes);
}
//---------------------------------------------------------------------------------------------------------
function ctrl_handler(int $_event)
{
    switch ($_event) {
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
    quitFromServer('http://github.com/S3x0r/MINION');

    cliBot('Terminating BOT...');
    cliBot('------------------LOG ENDED: '.date('d.m.Y | H:i:s').'------------------'.N);

    sleep(4);
    exit;
}
//---------------------------------------------------------------------------------------------------------
function isEmptyFolder($_dir): bool
{
    return is_dir($_dir) && is_readable($_dir) && scandir($_dir) === ['.', '..'];
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
//---------------------------------------------------------------------------------------------------------
function isIgnoredUser()
{
    if (!empty(loadValueFromConfigFile('IGNORE', 'users')[0])) {
        $IgnoredUsers = loadValueFromConfigFile('IGNORE', 'users');

        if (in_array(userIdentAndHostname(), $IgnoredUsers)) {
            return true;
        } else {
                 return false;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function commandPrefix()
{
    return loadValueFromConfigFile('COMMAND', 'command prefix');
}
//---------------------------------------------------------------------------------------------------------
function floodProtect($_where)
{
    $key          = userNickname().';'.userIdentAndHostname().';'.getBotChannel();
    $keyArray     = explode(';', $key);
    $nickname     = $keyArray[0];
    $fullHostmask = $keyArray[0].'!'.$keyArray[1];

    /* if user != owner */
    if ($fullHostmask != isUserOwner()) {
        global $flood;

        $identHost    = $keyArray[1];
        $channel      = $keyArray[2];
        $delay        = loadValueFromConfigFile('FLOOD', 'flood delay');
        $kick_comment = 'reason: flood';      
        $previous_timestamp = array_key_exists($key, $flood) ? floatval($flood[$key]) : 0.0000;

        $flood[$key] = microtime(true);

        if (abs($flood[$key] - $previous_timestamp) < floatval($delay)) {
            /* channel flood */
            if ($_where == 'channel') {
                /* ban kick */
                if (loadValueFromConfigFile('FLOOD', 'channel flood') == 'bankick') {
                    if (banUser($channel, $identHost)) {
                        kickUser($channel, $nickname, $kick_comment);
                        cliBot("User: '{$nickname}' Host: '{$identHost}' ban-kicked. Reason: Channel message flood");
                    }
                }
   
                /* kick */
                if (loadValueFromConfigFile('FLOOD', 'channel flood') == 'kick') {
                    if (kickUser($channel, $nickname, $kick_comment)) {
                        cliBot("User: '{$nickname}' Host: '{$identHost}' kicked. Reason: Channel message flood");
                    }
                }
   
                /* warn */
                if (loadValueFromConfigFile('FLOOD', 'channel flood') == 'warn') {
                    response("{$nickname}: Please do not flood in channel!");
                    cliBot("User: '{$nickname}' Host: '{$identHost}' warned. Reason: Channel message flood");
                }
            }
   
            /* privmsg flood */
            if ($_where == 'privmsg') {
                /* ignore */
                if (loadValueFromConfigFile('FLOOD', 'privmsg flood') == 'ignore') {
                    addUserToIgnoreList($identHost);
                    cliBot("User: '{$nickname}' Host: '{$identHost}' added to ignore list. Reason: Private message flood");
                }

                /* warn */
                if (loadValueFromConfigFile('FLOOD', 'privmsg flood') == 'warn') {
                    response("{$nickname}: Please do not flood me!");
                    cliBot("User: '{$nickname}' Host: '{$identHost}' warned. Reason: Private message flood");
                }
            }

            /* notice flood */
            if ($_where == 'notice') {
                /* ignore */
                if (loadValueFromConfigFile('FLOOD', 'notice flood') == 'ignore') {
                    addUserToIgnoreList($identHost);
                    cliBot("User: '{$nickname}' Host: '{$identHost}' added to ignore list. Reason: Notice message flood");   
                }
                
                /* warn */
                if (loadValueFromConfigFile('FLOOD', 'notice flood') == 'warn') {
                    response("{$nickname}: Please do not flood me!");
                    cliBot("User: '{$nickname}' Host: '{$identHost}' warned. Reason: Notice message flood");
                }
            }

             /* ctcp flood */
            if ($_where == 'ctcp') {
                /* ignore */
                if (loadValueFromConfigFile('FLOOD', 'ctcp flood') == 'ignore') {
                    addUserToIgnoreList($identHost);
                    cliBot("User: '{$nickname}' Host: '{$identHost}' added to ignore list. Reason: CTCP message flood");  
                }

                /* warn */
                if (loadValueFromConfigFile('FLOOD', 'ctcp flood') == 'warn') {
                    response("{$nickname}: Please do not flood me!");
                    cliBot("User: '{$nickname}' Host: '{$identHost}' warned. Reason: CTCP message flood");
                }
            }
        }
    }
}
