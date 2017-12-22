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
    define('VER', '0.9.5');
//---------------------------------------------------------------------------------------------------------
    define('START_TIME', time());
    define('PHP_VER', phpversion());
    define('PLUGIN_HASH', 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c');
    define('COLOR', '6');
    set_time_limit(0);
    set_error_handler('ErrorHandler');
    error_reporting(-1);
//---------------------------------------------------------------------------------------------------------
function SetDefaultData()
{
    /* if variable empty in config load default one */
    if (empty($GLOBALS['CONFIG_PORT']) or !is_numeric($GLOBALS['CONFIG_PORT'])) {
        $GLOBALS['CONFIG_PORT'] = '6667';
    }
    if (empty($GLOBALS['CONFIG_TRY_CONNECT']) or !is_numeric($GLOBALS['CONFIG_TRY_CONNECT'])) {
        $GLOBALS['CONFIG_TRY_CONNECT'] = '99';
    }
    if (empty($GLOBALS['CONFIG_CONNECT_DELAY']) or !is_numeric($GLOBALS['CONFIG_CONNECT_DELAY'])) {
        $GLOBALS['CONFIG_CONNECT_DELAY'] = '6';
    }
    if (empty($GLOBALS['CONFIG_OWNERS_PASSWD'])) {
        $GLOBALS['CONFIG_OWNERS_PASSWD'] = '47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed';
    }
    if (empty($GLOBALS['CONFIG_BOT_RESPONSE'])) {
        $GLOBALS['CONFIG_BOT_RESPONSE'] = 'notice';
    }
    if ($GLOBALS['CONFIG_AUTO_OP'] != 'no' && $GLOBALS['CONFIG_AUTO_OP'] != 'yes') {
        $GLOBALS['CONFIG_AUTO_OP'] = 'yes';
    }
    if ($GLOBALS['CONFIG_AUTO_REJOIN'] != 'no' && $GLOBALS['CONFIG_AUTO_REJOIN'] != 'yes') {
        $GLOBALS['CONFIG_AUTO_REJOIN'] = 'yes';
    }
    if ($GLOBALS['CONFIG_KEEP_NICK'] != 'no' && $GLOBALS['CONFIG_KEEP_NICK'] != 'yes') {
        $GLOBALS['CONFIG_KEEP_NICK'] = 'yes';
    }
    if ($GLOBALS['CONFIG_AUTO_JOIN'] != 'no' && $GLOBALS['CONFIG_AUTO_JOIN'] != 'yes') {
        $GLOBALS['CONFIG_AUTO_JOIN'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_CMD_PREFIX'])) {
        $GLOBALS['CONFIG_CMD_PREFIX'] = '!';
    }
    if ($GLOBALS['CONFIG_CTCP_RESPONSE'] != 'no' && $GLOBALS['CONFIG_CTCP_RESPONSE'] != 'yes') {
        $GLOBALS['CONFIG_CTCP_RESPONSE'] = 'yes';
    }
    if ($GLOBALS['CONFIG_KEEPCHAN_MODES'] != 'no' && $GLOBALS['CONFIG_KEEPCHAN_MODES'] != 'yes') {
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
    if ($GLOBALS['CONFIG_LOGGING'] != 'no' && $GLOBALS['CONFIG_LOGGING'] != 'yes') {
        $GLOBALS['CONFIG_LOGGING'] = 'yes';
    }
    if ($GLOBALS['CONFIG_LANGUAGE'] != 'EN' && $GLOBALS['CONFIG_LANGUAGE'] != 'PL') {
        $GLOBALS['CONFIG_LANGUAGE'] = 'EN';
    }
    if (empty($GLOBALS['CONFIG_WEB_LOGIN'])) {
        $GLOBALS['CONFIG_WEB_LOGIN'] = 'changeme';
    }
    if (empty($GLOBALS['CONFIG_WEB_PASSWORD'])) {
        $GLOBALS['CONFIG_WEB_PASSWORD'] = 'changeme';
    }
    if (empty($GLOBALS['CONFIG_TIMEZONE'])) {
        $GLOBALS['CONFIG_TIMEZONE'] = 'Europe/Warsaw';
    }
    if (empty($GLOBALS['CONFIG_FETCH_SERVER'])) {
        $GLOBALS['CONFIG_FETCH_SERVER'] = 'https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master';
    }
    if ($GLOBALS['silent_mode'] != 'no' && $GLOBALS['silent_mode'] != 'yes') {
        $GLOBALS['silent_mode'] = 'no';
    }
    if ($GLOBALS['CONFIG_CHECK_UPDATE'] != 'no' && $GLOBALS['CONFIG_CHECK_UPDATE'] != 'yes') {
        $GLOBALS['CONFIG_CHECK_UPDATE'] = 'no';
    }
    if ($GLOBALS['CONFIG_PLAY_SOUNDS'] != 'no' && $GLOBALS['CONFIG_PLAY_SOUNDS'] != 'yes') {
        $GLOBALS['CONFIG_PLAY_SOUNDS'] = 'yes';
    }
    if ($GLOBALS['CONFIG_SHOW_LOGO'] != 'no' && $GLOBALS['CONFIG_SHOW_LOGO'] != 'yes') {
        $GLOBALS['CONFIG_SHOW_LOGO'] = 'yes';
    }
    if ($GLOBALS['CONFIG_SHOW_RAW'] != 'no' && $GLOBALS['CONFIG_SHOW_RAW'] != 'yes') {
        $GLOBALS['CONFIG_SHOW_RAW'] = 'no';
    }

    /* set timezone */
    date_default_timezone_set($GLOBALS['CONFIG_TIMEZONE']);
}
//---------------------------------------------------------------------------------------------------------
