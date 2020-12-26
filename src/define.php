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
    define('VER', '1.1.4');
//---------------------------------------------------------------------------------------------------------
    define('START_TIME', time());
    define('PHP_VER', phpversion());
    define('PLUGIN_HASH', 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c');
    
    /* core commands count */
    define('CORECOUNT', '6');
    
    /* directory names */
    define('LOGSDIR', 'LOGS');
    define('DATADIR', 'DATA');

    /* check version url */
    define('VERSION_URL', 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT');

    /* logs filename */
    !empty($_SERVER['COMPUTERNAME']) ? $computerNameORhostname = $_SERVER['COMPUTERNAME'] : $computerNameORhostname = gethostname();
    $GLOBALS['logFileName'] = LOGSDIR."/".date('Y.m.d').",".$computerNameORhostname.".txt";

    error_reporting(-1);
//---------------------------------------------------------------------------------------------------------
function SetDefaultData()
{
    /* if variable empty in config load default one */
    empty($GLOBALS['CONFIG_NICKNAME'])      ? $GLOBALS['CONFIG_NICKNAME']      = 'minion'                                                                   : false;
    empty($GLOBALS['CONFIG_SERVER'])        ? $GLOBALS['CONFIG_SERVER']        = 'irc.dal.net'                                                              : false;
    empty($GLOBALS['CONFIG_OWNERS_PASSWD']) ? $GLOBALS['CONFIG_OWNERS_PASSWD'] = '47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed'         : false;
    empty($GLOBALS['CONFIG_BOT_RESPONSE'])  ? $GLOBALS['CONFIG_BOT_RESPONSE']  = 'notice'                                                                   : false;
    empty($GLOBALS['CONFIG_WEB_LOGIN'])     ? $GLOBALS['CONFIG_WEB_LOGIN']     = 'changeme'                                                                 : false;
    empty($GLOBALS['CONFIG_WEB_PASSWORD'])  ? $GLOBALS['CONFIG_WEB_PASSWORD']  = 'changeme'                                                                 : false;
    empty($GLOBALS['CONFIG_TIMEZONE'])      ? $GLOBALS['CONFIG_TIMEZONE']      = 'Europe/Warsaw'                                                            : false;
    empty($GLOBALS['CONFIG_FETCH_SERVER'])  ? $GLOBALS['CONFIG_FETCH_SERVER']  = 'https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master' : false;
    empty($GLOBALS['CONFIG_CMD_PREFIX'])    ? $GLOBALS['CONFIG_CMD_PREFIX']    = '!'                                                                        : false;

    if (empty($GLOBALS['CONFIG_PORT']) or !is_numeric($GLOBALS['CONFIG_PORT'])) {
        $GLOBALS['CONFIG_PORT'] = '6667';
    }
    if (empty($GLOBALS['CONFIG_TRY_CONNECT']) or !is_numeric($GLOBALS['CONFIG_TRY_CONNECT'])) {
        $GLOBALS['CONFIG_TRY_CONNECT'] = '99';
    }
    if (empty($GLOBALS['CONFIG_CONNECT_DELAY']) or !is_numeric($GLOBALS['CONFIG_CONNECT_DELAY'])) {
        $GLOBALS['CONFIG_CONNECT_DELAY'] = '6';
    }
    if (!in_array($GLOBALS['CONFIG_AUTO_OP'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_AUTO_OP'] = 'yes';
    }
    if (!in_array($GLOBALS['CONFIG_AUTO_REJOIN'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_AUTO_REJOIN'] = 'yes';
    }
    if (!in_array($GLOBALS['CONFIG_KEEP_NICK'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_KEEP_NICK'] = 'yes';
    }
    if (!in_array($GLOBALS['CONFIG_AUTO_JOIN'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_AUTO_JOIN'] = 'yes';
    }
    if (!in_array($GLOBALS['CONFIG_CTCP_RESPONSE'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_CTCP_RESPONSE'] = 'yes';
    }
    if (!in_array($GLOBALS['CONFIG_KEEPCHAN_MODES'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_KEEPCHAN_MODES'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_CHANNEL_DELAY']) or !is_numeric($GLOBALS['CONFIG_CHANNEL_DELAY'])) {
        $GLOBALS['CONFIG_CHANNEL_DELAY'] = '1.5';
    }
    if (empty($GLOBALS['CONFIG_PRIVATE_DELAY']) or !is_numeric($GLOBALS['CONFIG_PRIVATE_DELAY'])) {
        $GLOBALS['CONFIG_PRIVATE_DELAY'] = '1';
    }
    if (empty($GLOBALS['CONFIG_NOTICE_DELAY']) or !is_numeric($GLOBALS['CONFIG_NOTICE_DELAY'])) {
        $GLOBALS['CONFIG_NOTICE_DELAY'] = '1';
    }
    if (!in_array($GLOBALS['CONFIG_LOGGING'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_LOGGING'] = 'yes';
    }
    if (!in_array($GLOBALS['silent_mode'], ['yes', 'no'], true)) {
        $GLOBALS['silent_mode'] = 'no';
    }
    if (!in_array($GLOBALS['CONFIG_CHECK_UPDATE'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_CHECK_UPDATE'] = 'no';
    }
    if (!in_array($GLOBALS['CONFIG_PLAY_SOUNDS'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_PLAY_SOUNDS'] = 'yes';
    }
    if (!in_array($GLOBALS['CONFIG_SHOW_LOGO'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_SHOW_LOGO'] = 'yes';
    }
    if (!in_array($GLOBALS['CONFIG_SHOW_RAW'], ['yes', 'no'], true)) {
        $GLOBALS['CONFIG_SHOW_RAW'] = 'no';
    }

    /* set timezone */
    date_default_timezone_set($GLOBALS['CONFIG_TIMEZONE']);
}
//---------------------------------------------------------------------------------------------------------
