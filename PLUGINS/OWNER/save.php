<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving to config file: !save help to list commands';
 $plugin_command = 'save';

function plugin_save()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'save <help> to list commands'); } 
  
  else {

    switch($GLOBALS['args']) {

		case "help": 
		BOT_RESPONSE('Save commands:');
		BOT_RESPONSE('save auto_join - Saving auto join on channel when connected: !save auto_join <yes/no>');
		BOT_RESPONSE('save auto_rejoin - Saving auto rejoin when kicked from channel: !save auto_rejoin <yes/no>');
		BOT_RESPONSE('save channel - Saving channel to config: !save channel <#new_channel>');
		BOT_RESPONSE('save command_prefix - Saving prefix commands: !save command_prefix <new_prefix>');
		BOT_RESPONSE('save connect_delay - Saving connect delay value to config: !save connect_delay <value>');
		BOT_RESPONSE('save fetch_server - Saving fetch server to config: !save fetch_server <new_server>');
		BOT_RESPONSE('save ident - Saving ident to config: !save ident <new_ident>');
		BOT_RESPONSE('save name - Saving name to config: !save name <new_name>');
		BOT_RESPONSE('save nick - Saving nickname to config: !save nick <new_nick>');
		BOT_RESPONSE('save port - Saving port to config: !save port <new_port>');
		BOT_RESPONSE('save server - Saving server to config: !save server <new_server>');
		BOT_RESPONSE('save try_connect - Saving how many times try connect to server: !save try_connect <value>');
		break;

	}

   switch($GLOBALS['piece1']) {

	 case "auto_join":
     SaveData($GLOBALS['config_file'], 'CHANNEL', 'auto_join', $GLOBALS['piece2']);
     BOT_RESPONSE('Auto_join Saved.');
     CLI_MSG('!save auto_join on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New auto_join: '.$GLOBALS['piece2'], '1');
	 break;

	 case "auto_rejoin":	  
	 SaveData($GLOBALS['config_file'], 'AUTOMATIC', 'auto_rejoin', $GLOBALS['piece2']);
	 BOT_RESPONSE('Auto_rejoin Saved.');
	 CLI_MSG('!save auto_rejoin on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New auto_rejoin: '.$GLOBALS['piece2'], '1');
	 break;
  
  	 case "channel":
     SaveData($GLOBALS['config_file'], 'CHANNEL', 'channel', $GLOBALS['piece2']);
     BOT_RESPONSE('Channel Saved.');
     CLI_MSG('!save channel on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New channel: '.$GLOBALS['piece2'], '1');
	 break;

	 case "command_prefix":
     SaveData($GLOBALS['config_file'], 'COMMAND', 'command_prefix', $GLOBALS['piece2']);
     BOT_RESPONSE('Command_prefix Saved.');
     CLI_MSG('!save command_prefix on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New command_prefix: '.$GLOBALS['piece2'], '1');
	 break;

	 case "connect_delay":
	 SaveData($GLOBALS['config_file'], 'SERVER', 'connect_delay', $GLOBALS['piece2']);
	 BOT_RESPONSE('Connect_delay Saved.');
	 CLI_MSG('!save connect_delay on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New connect_delay: '.$GLOBALS['piece2'], '1');
	 break;

	 case "fetch_server":
     SaveData($GLOBALS['config_file'], 'FETCH', 'fetch_server', $GLOBALS['piece2']);
     BOT_RESPONSE('Server Saved.');
     CLI_MSG('!save fetch_server on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New server: '.$GLOBALS['piece2'], '1');
	 break;

	 case "ident":
	 SaveData($GLOBALS['config_file'], 'BOT', 'ident', $GLOBALS['piece2']);
	 BOT_RESPONSE('Ident Saved.');
	 CLI_MSG('!save ident on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New ident: '.$GLOBALS['piece2'], '1');
	 break;

	 case "name":
	 SaveData($GLOBALS['config_file'], 'BOT', 'name', $GLOBALS['piece2']);
	 BOT_RESPONSE('Name Saved.');
	 CLI_MSG('!save name on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New name: '.$GLOBALS['piece2'], '1');
	 break;

	 case "nick":
     SaveData($GLOBALS['config_file'], 'BOT', 'nickname', $GLOBALS['piece2']);
     BOT_RESPONSE('Nick Saved.');
     CLI_MSG('!save nick on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New nick: '.$GLOBALS['piece2'], '1');
	 break;

	 case "port":
	 SaveData($GLOBALS['config_file'], 'SERVER', 'port', $GLOBALS['piece2']);
	 BOT_RESPONSE('Port Saved.');
	 CLI_MSG('!save port on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New port: '.$GLOBALS['piece2'], '1');
	 break;

	 case "server":
	 SaveData($GLOBALS['config_file'], 'SERVER', 'server', $GLOBALS['piece2']);
	 BOT_RESPONSE('Server Saved.');
	 CLI_MSG('!save server on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New server: '.$GLOBALS['piece2'], '1');
	 break;

	 case "try_connect":
	 SaveData($GLOBALS['config_file'], 'SERVER', 'try_connect', $GLOBALS['piece2']);
	 BOT_RESPONSE('Try_connect Saved.');
	 CLI_MSG('!save try_connect on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', New try_connect: '.$GLOBALS['piece2'], '1');
	 break;

  }
 }
}
?>