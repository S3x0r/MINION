<?php
/* Copyright (c) 2013-2018, S3x0r <olisek@gmail.com>
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
PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------

    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = "Saving to config file: {$GLOBALS['CONFIG_CMD_PREFIX']}save help to list commands";
    $plugin_command = 'save';

function plugin_save()
{
    if (OnEmptyArg('save <help> to list commands')) {
    } else {
        switch ($GLOBALS['args']) {
            case 'help':
                 response('Save commands:');
                 response('save auto_join      - Saving auto join on channel when connected: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save auto_join <yes/no>');
                 response('save auto_op        - Saving auto op when join channel: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save auto_op <yes/no>');
                 response('save auto_op_list   - Saving auto op list in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save auto_op_list <nick!ident@host, ...>');
                 response('save auto_rejoin    - Saving auto rejoin when kicked from channel: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save auto_rejoin <yes/no>');
                 response('save bot_owners     - Saving bot owners list in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save bot_owners <nick!ident@host, ...>');
                 response('save response       - Saving where bot outputs messages: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save response <channel/notice/priv>');
                 response('save channel        - Saving channel to config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save channel <#new_channel>');
                 response('save command_prefix - Saving prefix commands: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save command_prefix <new_prefix>');
                 response('save connect_delay  - Saving connect delay value to config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save connect_delay <value>');
                 response('save ctcp_finger    - Saving ctcp finger in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_finger <string>');
                 response('save ctcp_response  - Saving ctcp response in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_response <yes/no>');
                 response('save ctcp_version   - Saving ctcp version in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_version <string>');
                 response('save fetch_server   - Saving fetch server to config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save fetch_server <new_server>');
                 response('save ident          - Saving ident to config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save ident <new_ident>');
                 response('save logging        - Saving logging in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save logging <yes/no>');
                 response('save name           - Saving name to config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save name <new_name>');
                 response('save nick           - Saving nickname to config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save nick <new_nick>');
                 response('save owner_password - Saving bot owner password in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save owner_password <password>');
                 response('save port           - Saving port to config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save port <new_port>');
                 response('save show_raw       - Saving show raw in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save show_raw <yes/no>');
                 response('save server         - Saving server to config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save server <new_server>');
                 response('save time_zone      - Saving time zone in config: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save time_zone <eg. Europe/Warsaw>');
                 response('save try_connect    - Saving how many times try connect to server: '
                 .$GLOBALS['CONFIG_CMD_PREFIX'].'save try_connect <value>');
                break;
        }
        switch ($GLOBALS['piece1']) {
            case 'auto_join':
                 SaveData($GLOBALS['configFile'], 'CHANNEL', 'auto_join', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_AUTO_JOIN'] = $cfg->get("CHANNEL", "auto_join");
 
                 response('Auto_join Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'auto_op':
                 SaveData($GLOBALS['configFile'], 'AUTOMATIC', 'auto_op', $GLOBALS['piece2']);
    
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_AUTO_OP'] = $cfg->get("AUTOMATIC", "auto_op");
 
                 response('Auto_op Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;
 
            case 'auto_op_list':
                 SaveData($GLOBALS['configFile'], 'OWNER', 'auto_op_list', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_AUTO_OP_LIST'] = $cfg->get("OWNER", "auto_op_list");
 
                 response('Auto_op_list Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'auto_rejoin':
                 SaveData($GLOBALS['configFile'], 'AUTOMATIC', 'auto_rejoin', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_AUTO_REJOIN'] = $cfg->get("AUTOMATIC", "auto_rejoin");
 
                 response('Auto_rejoin Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'bot_owners':
                 SaveData($GLOBALS['configFile'], 'OWNER', 'bot_owners', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_OWNERS'] = $cfg->get("OWNER", "bot_owners");
 
                 response('Bot_owners Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'response':
                 SaveData($GLOBALS['configFile'], 'RESPONSE', 'response', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_BOT_RESPONSE'] = $cfg->get("RESPONSE", "response");
 
                 response('response Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;
  
            case 'channel':
                 SaveData($GLOBALS['configFile'], 'CHANNEL', 'channel', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_CNANNEL'] = $cfg->get("CHANNEL", "channel");
 
                 response('Channel Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'command_prefix':
                 /* update plugins array */
                 UpdatePrefix('OWNER', $GLOBALS['piece2']);
                 UpdatePrefix('USER', $GLOBALS['piece2']);
            
                 SaveData($GLOBALS['configFile'], 'COMMAND', 'command_prefix', $GLOBALS['piece2']);

                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_CMD_PREFIX'] = $cfg->get("COMMAND", "command_prefix");

                 response('Command_prefix Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'connect_delay':
                 SaveData($GLOBALS['configFile'], 'SERVER', 'connect_delay', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_CONNECT_DELAY'] = $cfg->get("SERVER", "connect_delay");
 
                 response('Connect_delay Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'ctcp_finger':
                 SaveData($GLOBALS['configFile'], 'CTCP', 'ctcp_finger', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_CTCP_FINGER'] = $cfg->get("CTCP", "ctcp_finger");
 
                 response('Ctcp_finger Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'ctcp_response':
                 SaveData($GLOBALS['configFile'], 'CTCP', 'ctcp_response', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_CTCP_RESPONSE'] = $cfg->get("CTCP", "ctcp_response");
 
                 response('Ctcp_response Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'ctcp_version':
                 SaveData($GLOBALS['configFile'], 'CTCP', 'ctcp_version', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_CTCP_VERSION'] = $cfg->get("CTCP", "ctcp_version");
 
                 response('Ctcp_version Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'fetch_server':
                 SaveData($GLOBALS['configFile'], 'FETCH', 'fetch_server', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_FETCH_SERVER'] = $cfg->get("FETCH", "fetch_server");
 
                 response('Server Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'ident':
                 SaveData($GLOBALS['configFile'], 'BOT', 'ident', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_IDENT'] = $cfg->get("BOT", "ident");
 
                 response('Ident Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'logging':
                 SaveData($GLOBALS['configFile'], 'LOGS', 'logging', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_LOGGING'] = $cfg->get("LOGS", "logging");
 
                 response('Logging Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'name':
                 SaveData($GLOBALS['configFile'], 'BOT', 'name', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_NAME'] = $cfg->get("BOT", "name");
 
                 response('Name Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'nick':
                 SaveData($GLOBALS['configFile'], 'BOT', 'nickname', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_NICKNAME'] = $cfg->get("BOT", "nickname");
 
                 response('Nick Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'owner_password':
                 SaveData($GLOBALS['configFile'], 'OWNER', 'owner_password', $GLOBALS['piece2']);
     
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_OWNER_PASSWD'] = $cfg->get("OWNER", "owner_password");
 
                 response('Owner_password Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'port':
                 SaveData($GLOBALS['configFile'], 'SERVER', 'port', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_PORT'] = $cfg->get("SERVER", "port");
 
                 response('Port Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'show_raw':
                 SaveData($GLOBALS['configFile'], 'DEBUG', 'show_raw', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_SHOW_RAW'] = $cfg->get("DEBUG", "show_raw");
 
                 response('Show_raw Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'server':
                 SaveData($GLOBALS['configFile'], 'SERVER', 'server', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_SERVER'] = $cfg->get("SERVER", "server");
 
                 response('Server Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'time_zone':
                 SaveData($GLOBALS['configFile'], 'TIME', 'time_zone', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_TIMEZONE'] = $cfg->get("TIME", "time_zone");
 
                 response('Time_zone Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;

            case 'try_connect':
                 SaveData($GLOBALS['configFile'], 'SERVER', 'try_connect', $GLOBALS['piece2']);
 
                 /* update variable with new owners */
                 $cfg = new IniParser($GLOBALS['configFile']);
                 $GLOBALS['CONFIG_TRY_CONNECT'] = $cfg->get("SERVER", "try_connect");
 
                 response('Try_connect Saved.');

                 CLI_MSG('[PLUGIN: save] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                         $GLOBALS['channel'].' | new value: '.$GLOBALS['piece2'], '1');
                break;
        }
    }
}

//TODO: check some input