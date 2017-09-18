<?php
if (PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

    $plugin_description = 'Saving to config file: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save help to list commands';
    $plugin_command = 'save';

function plugin_save() {

    if (empty($GLOBALS['args'])) { NICK_MSG('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save <help> to list commands'); } 
  
    else {

    switch($GLOBALS['args']) {

		case 'help': 
		NICK_MSG('Save commands:');
		NICK_MSG('save auto_join - Saving auto join on channel when connected: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save auto_join <yes/no>');
		NICK_MSG('save auto_op - Saving auto op when join channel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save auto_op <yes/no>');
		NICK_MSG('save auto_op_list - Saving auto op list in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save auto_op_list <nick!ident@host, ...>');
		NICK_MSG('save auto_rejoin - Saving auto rejoin when kicked from channel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save auto_rejoin <yes/no>');
		NICK_MSG('save bot_owners - Saving bot owners list in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save bot_owners <nick!ident@host, ...>');
		NICK_MSG('save bot_response - Saving where bot outputs messages: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save bot_response <channel/notice/priv>');
		NICK_MSG('save channel - Saving channel to config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save channel <#new_channel>');
		NICK_MSG('save command_prefix - Saving prefix commands: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save command_prefix <new_prefix>');
		NICK_MSG('save connect_delay - Saving connect delay value to config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save connect_delay <value>');
		NICK_MSG('save ctcp_finger - Saving ctcp finger in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_finger <string>');
		NICK_MSG('save ctcp_response - Saving ctcp response in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_response <yes/no>');
		NICK_MSG('save ctcp_version - Saving ctcp version in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_version <string>');
		NICK_MSG('save fetch_server - Saving fetch server to config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save fetch_server <new_server>');
		NICK_MSG('save ident - Saving ident to config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save ident <new_ident>');
		NICK_MSG('save logging - Saving logging in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save logging <yes/no>');
		NICK_MSG('save name - Saving name to config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save name <new_name>');
		NICK_MSG('save nick - Saving nickname to config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save nick <new_nick>');
		NICK_MSG('save owner_password - Saving bot owner password in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save owner_password <password>');
		NICK_MSG('save port - Saving port to config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save port <new_port>');
		NICK_MSG('save show_raw - Saving show raw in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save show_raw <yes/no>');
		NICK_MSG('save server - Saving server to config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save server <new_server>');
		NICK_MSG('save time_zone - Saving time zone in config: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save time_zone <eg. Europe/Warsaw>');
		NICK_MSG('save try_connect - Saving how many times try connect to server: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save try_connect <value>');
		break;

    }

    switch($GLOBALS['piece1']) {

	 case 'auto_join':
     SaveData($GLOBALS['config_file'], 'CHANNEL', 'auto_join', $GLOBALS['piece2']);
     
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_AUTO_JOIN'] = $cfg->get("CHANNEL","auto_join");
	 
	 NICK_MSG('Auto_join Saved.');
     CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save auto_join on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New auto_join: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'auto_op':
     SaveData($GLOBALS['config_file'], 'AUTOMATIC', 'auto_op', $GLOBALS['piece2']);
    
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_AUTO_OP'] = $cfg->get("AUTOMATIC","auto_op");
	 
	 NICK_MSG('Auto_op Saved.');
     CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save auto_op on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New auto_op: '.$GLOBALS['piece2'], '1');
	 break;
	 
	 case 'auto_op_list':
     SaveData($GLOBALS['config_file'], 'ADMIN', 'auto_op_list', $GLOBALS['piece2']);
     
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_AUTO_OP_LIST'] = $cfg->get("ADMIN","auto_op_list");
	 
	 NICK_MSG('Auto_op_list Saved.');
     CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save auto_op_list on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New auto_op_list: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'auto_rejoin':	  
	 SaveData($GLOBALS['config_file'], 'AUTOMATIC', 'auto_rejoin', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_AUTO_REJOIN'] = $cfg->get("AUTOMATIC","auto_rejoin");
	 
	 NICK_MSG('Auto_rejoin Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save auto_rejoin on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New auto_rejoin: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'bot_owners':	  
	 SaveData($GLOBALS['config_file'], 'ADMIN', 'bot_owners', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_OWNERS'] = $cfg->get("ADMIN","bot_owners");
	 
	 NICK_MSG('Bot_owners Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save bot_owners on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New bot_owners: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'bot_response':	  
	 SaveData($GLOBALS['config_file'], 'RESPONSE', 'bot_response', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_BOT_RESPONSE'] = $cfg->get("RESPONSE","bot_response");
	 
	 NICK_MSG('bot_response Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save bot_response on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New bot_response: '.$GLOBALS['piece2'], '1');
	 break;
  
  	 case 'channel':
     SaveData($GLOBALS['config_file'], 'CHANNEL', 'channel', $GLOBALS['piece2']);
     
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_CNANNEL'] = $cfg->get("CHANNEL","channel");
	 
	 NICK_MSG('Channel Saved.');
     CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save channel on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New channel: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'command_prefix':
     SaveData($GLOBALS['config_file'], 'COMMAND', 'command_prefix', $GLOBALS['piece2']);
     
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_CMD_PREFIX'] = $cfg->get("COMMAND","command_prefix");
	 
	 NICK_MSG('Command_prefix Saved.');
     CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save command_prefix on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New command_prefix: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'connect_delay':
	 SaveData($GLOBALS['config_file'], 'SERVER', 'connect_delay', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_CONNECT_DELAY'] = $cfg->get("SERVER","connect_delay");
	 
	 NICK_MSG('Connect_delay Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save connect_delay on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New connect_delay: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'ctcp_finger':
	 SaveData($GLOBALS['config_file'], 'CTCP', 'ctcp_finger', $GLOBALS['piece2']);
	 
     /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_CTCP_FINGER'] = $cfg->get("CTCP","ctcp_finger");
	 
	 NICK_MSG('Ctcp_finger Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_finger on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New ctcp_finger: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'ctcp_response':
	 SaveData($GLOBALS['config_file'], 'CTCP', 'ctcp_response', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_CTCP_RESPONSE'] = $cfg->get("CTCP","ctcp_response");
	 
	 NICK_MSG('Ctcp_response Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_response on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New ctcp_response: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'ctcp_version':
	 SaveData($GLOBALS['config_file'], 'CTCP', 'ctcp_version', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_CTCP_VERSION'] = $cfg->get("CTCP","ctcp_version");
	 
	 NICK_MSG('Ctcp_version Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save ctcp_version on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New ctcp_version: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'fetch_server':
     SaveData($GLOBALS['config_file'], 'FETCH', 'fetch_server', $GLOBALS['piece2']);
     
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_FETCH_SERVER'] = $cfg->get("FETCH","fetch_server");
	 
	 NICK_MSG('Server Saved.');
     CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save fetch_server on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New server: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'ident':
	 SaveData($GLOBALS['config_file'], 'BOT', 'ident', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_IDENT'] = $cfg->get("BOT","ident");
	 
	 NICK_MSG('Ident Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save ident on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New ident: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'logging':
	 SaveData($GLOBALS['config_file'], 'LOGS', 'logging', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_LOGGING'] = $cfg->get("LOGS","logging");
	 
	 NICK_MSG('Logging Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save logging on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New logging: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'name':
	 SaveData($GLOBALS['config_file'], 'BOT', 'name', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_NAME'] = $cfg->get("BOT","name");
	 
	 NICK_MSG('Name Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save name on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New name: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'nick':
     SaveData($GLOBALS['config_file'], 'BOT', 'nickname', $GLOBALS['piece2']);
     
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_NICKNAME'] = $cfg->get("BOT","nickname");
	 
	 NICK_MSG('Nick Saved.');
     CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save nick on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New nick: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'owner_password':
     SaveData($GLOBALS['config_file'], 'ADMIN', 'owner_password', $GLOBALS['piece2']);
     
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_OWNER_PASSWD'] = $cfg->get("ADMIN","owner_password");
	 
	 NICK_MSG('Owner_password Saved.');
     CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save owner_password on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New owner_password: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'port':
	 SaveData($GLOBALS['config_file'], 'SERVER', 'port', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_PORT'] = $cfg->get("SERVER","port");
	 
	 NICK_MSG('Port Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save port on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New port: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'show_raw':
	 SaveData($GLOBALS['config_file'], 'DEBUG', 'show_raw', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_SHOW_RAW'] = $cfg->get("DEBUG","show_raw");
	 
	 NICK_MSG('Show_raw Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save show_raw on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New show_raw: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'server':
	 SaveData($GLOBALS['config_file'], 'SERVER', 'server', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_SERVER'] = $cfg->get("SERVER","server");
	 
	 NICK_MSG('Server Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save server on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New server: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'time_zone':
	 SaveData($GLOBALS['config_file'], 'TIME', 'time_zone', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_TIMEZONE'] = $cfg->get("TIME","time_zone");
	 
	 NICK_MSG('Time_zone Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save time_zone on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New time_zone: '.$GLOBALS['piece2'], '1');
	 break;

	 case 'try_connect':
	 SaveData($GLOBALS['config_file'], 'SERVER', 'try_connect', $GLOBALS['piece2']);
	 
	 /* update variable with new owners */
     $cfg = new iniParser($GLOBALS['config_file']);
     $GLOBALS['CONFIG_TRY_CONNECT'] = $cfg->get("SERVER","try_connect");
	 
	 NICK_MSG('Try_connect Saved.');
	 CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'save try_connect on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New try_connect: '.$GLOBALS['piece2'], '1');
	 break;

  }
 }
}
?>