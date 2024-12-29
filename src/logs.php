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

function saveLog($_mode, $_data)
{
    if (loadValueFromConfigFile('LOGS', 'logging') == true) {
        createLogsDateDir();

        switch ($_mode) {
          case 'bot':
               if (loadValueFromConfigFile('LOGS', 'log bot messages') == true) {
                   saveToFile(logFileNameFormatBot(), $_data, 'a');
               }
              break;
          case 'server':
               if (loadValueFromConfigFile('LOGS', 'log server messages') == true) {
                   saveToFile(logFileNameFormatServer(), $_data, 'a');
               }
              break;
          case 'channel':
               if (loadValueFromConfigFile('LOGS', 'log channel messages') == true) {
                   saveToFile(logFileNameFormatChannel(), $_data, 'a');
               }
              break;
          case 'notice':
               if (loadValueFromConfigFile('LOGS', 'log notice messages') == true) {
                   saveToFile(logFileNameFormatNotice(), $_data, 'a');
               }
              break;   
          case 'ctcp':
               if (loadValueFromConfigFile('LOGS', 'log ctcp messages') == true) {
                   saveToFile(logFileNameFormatCTCP(), $_data, 'a');
               }
              break;
           case 'plugin':
               if (loadValueFromConfigFile('LOGS', 'log plugins usage messages') == true) {
                   saveToFile(logFileNameFormatPlugins(), $_data, 'a');
               }
              break;
           case 'raw':
               if (loadValueFromConfigFile('LOGS', 'log raw messages') == true) {
                   saveToFile(logFileNameFormatRaw(), $_data, 'a');
               }
              break;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function zipLogs()
{
    if (IsfolderFromDayBeforeExisting() == true) {
        $date              = @date('d.m.Y');
        $day_before_folder = LOGSDIR.'/'.@date( 'd.m.Y', strtotime($date.' -1 day'));

        /* if we got logs in folder */
        if (!isEmptyFolder($day_before_folder)) {
            cliBot("Compressing logs: '{$day_before_folder}'");
            $zip_file_name = $day_before_folder.'.zip';

            $rootPath = realpath($day_before_folder);

            $zip = new ZipArchive();
            $zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file)
            {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();

            /* if compressed file exists */
            if (is_file($zip_file_name)) {
                cliBot("Compressed logs file: '{$zip_file_name}'");

                if (is_dir($day_before_folder)) {
                    cliBot("Deleting directory: '{$day_before_folder}'");

                    $files = glob($day_before_folder.'/'.'*', GLOB_MARK);

                    foreach ($files as $file) {
                             unlink($file);
                    }

                    rmdir($day_before_folder);

                    if (!is_dir($day_before_folder)) {
                        cliBot('Done.');
                        playSound('prompt.mp3');
                    }
                }
            }
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function logFileNameFormatChannel()
{
    global $connectedToServer;

    $filename = loadValueFromConfigFile('CHANNEL', 'channel').'.'.$connectedToServer.'.txt';

    return LOGSDIR.'/'.@date('d.m.Y').'/'.$filename;
}
//---------------------------------------------------------------------------------------------------------
function logFileNameFormatBot()
{
    return LOGSDIR.'/'.@date('d.m.Y').'/'.LOGBOTFILE;
}
//---------------------------------------------------------------------------------------------------------
function logFileNameFormatPlugins()
{
    return LOGSDIR.'/'.@date('d.m.Y').'/'.LOGPLUGINSFILE;
}
//---------------------------------------------------------------------------------------------------------
function logFileNameFormatServer()
{
    return LOGSDIR.'/'.@date('d.m.Y').'/'.LOGSERVERFILE;
}
//---------------------------------------------------------------------------------------------------------
function logFileNameFormatCTCP()
{
    return LOGSDIR.'/'.@date('d.m.Y').'/'.LOGCTCPFILE;
}
//---------------------------------------------------------------------------------------------------------
function logFileNameFormatNotice()
{
    return LOGSDIR.'/'.@date('d.m.Y').'/'.LOGNOTICEFILE;
}
//---------------------------------------------------------------------------------------------------------
function logFileNameFormatRaw()
{
    return LOGSDIR.'/'.@date('d.m.Y').'/'.LOGRAWFILE;
}
