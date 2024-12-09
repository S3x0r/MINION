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

function saveLog($mode, $data)
{
    if (loadValueFromConfigFile('LOGS', 'logging') == true) {
        switch ($mode) {
          case 'bot':
               if (loadValueFromConfigFile('LOGS', 'log bot messages') == true) {
                   createLogsDateDir();
                   saveToFile(logFileNameFormatBot(), $data, 'a');
               }
              break;
          case 'server':
               if (loadValueFromConfigFile('LOGS', 'log server messages') == true) {
                   createLogsDateDir();
                   saveToFile(logFileNameFormatServer(), $data, 'a');
               }
              break;
          case 'channel':
               if (loadValueFromConfigFile('LOGS', 'log channel messages') == true) {
                   createLogsDateDir();
                   saveToFile(logFileNameFormatChannel(), $data, 'a');
               }
              break;
          case 'notice':
               if (loadValueFromConfigFile('LOGS', 'log notice messages') == true) {
                   createLogsDateDir();
                   saveToFile(logFileNameFormatNotice(), $data, 'a');
               }
              break;   
          case 'ctcp':
               if (loadValueFromConfigFile('LOGS', 'log ctcp messages') == true) {
                   createLogsDateDir();
                   saveToFile(logFileNameFormatCTCP(), $data, 'a');
               }
              break;
           case 'plugin':
               if (loadValueFromConfigFile('LOGS', 'log plugins usage messages') == true) {
                   createLogsDateDir();
                   saveToFile(logFileNameFormatPlugins(), $data, 'a');
               }
              break;
           case 'raw':
               if (loadValueFromConfigFile('LOGS', 'log raw messages') == true) {
                   createLogsDateDir();
                   saveToFile(logFileNameFormatRaw(), $data, 'a');
               }
              break;
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