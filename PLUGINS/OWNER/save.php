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

    $VERIFY             = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = "Saving to config file: {$GLOBALS['CONFIG.CMD.PREFIX']}save help to list commands";
    $plugin_command     = 'save';

/* TODO:
  missing:
server.password
bot.admin
admin.list
keep.chan.modes
keep.nick
channel.modes
channel.key
ban.list
channel.delay
private.delay
notice.delay
web.login
web.password
play.sounds
*/

function plugin_save()
{
    if (OnEmptyArg('save <help> to list commands')) {
    } else {
        switch ($GLOBALS['args']) {
            case 'help':
                 response('save commands:');
                 response('save auto.join      - Saving auto join on channel when connected: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save auto.join <yes/no>');
                 response('save auto.op        - Saving auto op when join channel: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save auto.op <yes/no>');
                 response('save auto.op.list   - Saving auto op list in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save auto.op.list <nick!ident@host, ...>');
                 response('save auto.rejoin    - Saving auto rejoin when kicked from channel: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save auto.rejoin <yes/no>');
                 response('save bot.owners     - Saving bot owners list in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save bot.owners <nick!ident@host, ...>');
                 response('save response       - Saving where bot outputs messages: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save response <channel/notice/priv>');
                 response('save channel        - Saving channel to config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save channel <#new_channel>');
                 response('save command.prefix - Saving prefix commands: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save command.prefix <new_prefix>');
                 response('save connect.delay  - Saving connect delay value to config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save connect.delay <value>');
                 response('save ctcp.finger    - Saving ctcp finger in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save ctcp.finger <string>');
                 response('save ctcp.response  - Saving ctcp response in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save ctcp.response <yes/no>');
                 response('save ctcp.version   - Saving ctcp version in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save ctcp.version <string>');
                 response('save fetch.server   - Saving fetch server to config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save fetch.server <new_server>');
                 response('save ident          - Saving ident to config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save ident <new_ident>');
                 response('save logging        - Saving logging in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save logging <yes/no>');
                 response('save name           - Saving name to config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save name <new_name>');
                 response('save nick           - Saving nickname to config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save nick <new_nick>');
                 response('save owner.password - Saving bot owner password in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save owner.password <password>');
                 response('save port           - Saving port to config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save port <new_port>');
                 response('save show.raw       - Saving show raw in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save show.raw <yes/no>');
                 response('save server         - Saving server to config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save server <new_server>');
                 response('save time.zone      - Saving time zone in config: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save time.zone <eg. Europe/Warsaw>');
                 response('save try.connect    - Saving how many times try connect to server: '
                 .$GLOBALS['CONFIG.CMD.PREFIX'].'save try.connect <value>');
                 response('End.');
                break;
        }
        switch ($GLOBALS['piece1']) {
            case 'auto.join':
                 SaveData($GLOBALS['configFile'], 'CHANNEL', 'auto.join', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.AUTO.JOIN'] = $cfg->get("CHANNEL", "auto.join");
 
                 response('Auto.join Saved.');
                break;

            case 'auto.op':
                 SaveData($GLOBALS['configFile'], 'AUTOMATIC', 'auto.op', $GLOBALS['piece2']);
    
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.AUTO.OP'] = $cfg->get("AUTOMATIC", "auto.op");
 
                 response('Auto.op Saved.');
                break;
 
            case 'auto.op.list':
                 SaveData($GLOBALS['configFile'], 'OWNER', 'auto.op.list', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.AUTO.OP.LIST'] = $cfg->get("OWNER", "auto.op.list");
 
                 response('Auto.op.list Saved.');
                break;

            case 'auto.rejoin':
                 SaveData($GLOBALS['configFile'], 'AUTOMATIC', 'auto.rejoin', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.AUTO.REJOIN'] = $cfg->get("AUTOMATIC", "auto.rejoin");
 
                 response('Auto.rejoin Saved.');
                break;

            case 'bot.owners':
                 SaveData($GLOBALS['configFile'], 'OWNER', 'bot.owners', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.OWNERS'] = $cfg->get("OWNER", "bot.owners");
 
                 response('Bot.owners Saved.');
                break;

            case 'response':
                 SaveData($GLOBALS['configFile'], 'RESPONSE', 'response', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.BOT.RESPONSE'] = $cfg->get("RESPONSE", "response");
 
                 response('response Saved.');
                break;
  
            case 'channel':
                 SaveData($GLOBALS['configFile'], 'CHANNEL', 'channel', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.CHANNEL'] = $cfg->get("CHANNEL", "channel");
 
                 response('Channel Saved.');
                break;

            case 'command.prefix':
                 /* update plugins array */
                 UpdatePrefix('OWNER', $GLOBALS['piece2']);
                 UpdatePrefix('USER', $GLOBALS['piece2']);
            
                 SaveData($GLOBALS['configFile'], 'COMMAND', 'command.prefix', $GLOBALS['piece2']);

                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.CMD.PREFIX'] = $cfg->get("COMMAND", "command.prefix");

                 response('Command.prefix Saved.');
                break;

            case 'connect.delay':
                 SaveData($GLOBALS['configFile'], 'SERVER', 'connect.delay', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.CONNECT.DELAY'] = $cfg->get("SERVER", "connect.delay");
 
                 response('Connect.delay Saved.');
                break;

            case 'ctcp.finger':
                 SaveData($GLOBALS['configFile'], 'CTCP', 'ctcp.finger', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.CTCP.FINGER'] = $cfg->get("CTCP", "ctcp.finger");
 
                 response('Ctcp.finger Saved.');
                break;

            case 'ctcp.response':
                 SaveData($GLOBALS['configFile'], 'CTCP', 'ctcp.response', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.CTCP.RESPONSE'] = $cfg->get("CTCP", "ctcp.response");
 
                 response('Ctcp.response Saved.');
                break;

            case 'ctcp.version':
                 SaveData($GLOBALS['configFile'], 'CTCP', 'ctcp.version', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.CTCP.VERSION'] = $cfg->get("CTCP", "ctcp.version");
 
                 response('Ctcp.version Saved.');
                break;

            case 'fetch.server':
                 SaveData($GLOBALS['configFile'], 'FETCH', 'fetch.server', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.FETCH.SERVER'] = $cfg->get("FETCH", "fetch.server");
 
                 response('Server Saved.');
                break;

            case 'ident':
                 SaveData($GLOBALS['configFile'], 'BOT', 'ident', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.IDENT'] = $cfg->get("BOT", "ident");
 
                 response('Ident Saved.');
                break;

            case 'logging':
                 SaveData($GLOBALS['configFile'], 'LOGS', 'logging', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.LOGGING'] = $cfg->get("LOGS", "logging");
 
                 response('Logging Saved.');
                break;

            case 'name':
                 SaveData($GLOBALS['configFile'], 'BOT', 'name', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.NAME'] = $cfg->get("BOT", "name");
 
                 response('Name Saved.');
                break;

            case 'nick':
                 SaveData($GLOBALS['configFile'], 'BOT', 'nickname', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.NICKNAME'] = $cfg->get("BOT", "nickname");
 
                 response('Nick Saved.');
                break;

            case 'owner.password':
                 SaveData($GLOBALS['configFile'], 'OWNER', 'owner.password', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.OWNER.PASSWD'] = $cfg->get("OWNER", "owner.password");
 
                 response('Owner.password Saved.');
                break;

            case 'port':
                 SaveData($GLOBALS['configFile'], 'SERVER', 'port', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.PORT'] = $cfg->get("SERVER", "port");
 
                 response('Port Saved.');
                break;

            case 'show.raw':
                 SaveData($GLOBALS['configFile'], 'DEBUG', 'show.raw', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.SHOW.RAW'] = $cfg->get("DEBUG", "show.raw");
 
                 response('Show.raw Saved.');
                break;

            case 'server':
                 SaveData($GLOBALS['configFile'], 'SERVER', 'server', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.SERVER'] = $cfg->get("SERVER", "server");
 
                 response('Server Saved.');
                break;

            case 'time.zone':
                 SaveData($GLOBALS['configFile'], 'TIME', 'time.zone', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.TIMEZONE'] = $cfg->get("TIME", "time.zone");
 
                 response('Time.zone Saved.');
                break;

            case 'try.connect':
                 SaveData($GLOBALS['configFile'], 'SERVER', 'try.connect', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG.TRY.CONNECT'] = $cfg->get("SERVER", "try.connect");
 
                 response('Try.connect Saved.');
                break;
        }
    }
}
