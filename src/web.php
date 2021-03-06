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

function WebEntry()
{
    $data = "[MAIN]
WEB_VERSION         = ".VER."
WEB_START_TIME      = ".START_TIME."
WEB_PHP_VERSION     = ".PHP_VER."
WEB_BOT_CONFIG_FILE = ".$GLOBALS['configFile'];
    
    /* save some variables to web.ini */
    SaveToFile('src/panel/web.ini', $data, 'w');

    $file = $GLOBALS['configFile'];
    $cfg = new IniParser($file);
    $GLOBALS['CONFIG_WEB_LOGIN'] = $cfg->get('PANEL', 'web_login');
    $GLOBALS['CONFIG_WEB_PASSWORD'] = $cfg->get('PANEL', 'web_password');
    
    /* generate random string for cookie salt */
    $string = randomString('16');

    /* save data to panel config */
    SaveData('src/panel/web.ini', 'PANEL', 'web_login', $GLOBALS['CONFIG_WEB_LOGIN']);
    SaveData('src/panel/web.ini', 'PANEL', 'web_password', $GLOBALS['CONFIG_WEB_PASSWORD']);
    SaveData('src/panel/web.ini', 'PANEL', 'web_salt', $string);
}
//---------------------------------------------------------------------------------------------------------
function WebSave($v1, $v2)
{
    $cfg = new IniParser('panel/web.ini');
    $cfg->setValue("MAIN", "$v1", "$v2");
    $cfg->save();
}
