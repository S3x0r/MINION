<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving to config file: !save help to list commands';
 $plugin_command = 'save';

function plugin_save()
{

  if(empty($GLOBALS['args'])) { CHANNEL_MSG('Usage: '.$GLOBALS['C_CMD_PREFIX'].'save help to list commands'); } 
  
  else {

  if($GLOBALS['args'] == 'help') 
	  { 
		CHANNEL_MSG('Save commands:');
		CHANNEL_MSG('save auto_join - Saving auto join on channel when connected: !save auto_join <yes/no>');
		CHANNEL_MSG('save auto_rejoin - Saving auto rejoin when kicked from channel: !save auto_rejoin <yes/no>');
		CHANNEL_MSG('save channel - Saving channel to config: !save channel <#new_channel>');
		CHANNEL_MSG('save command_prefix - Saving prefix commands: !save command_prefix <new_prefix>');
		CHANNEL_MSG('save connect_delay - Saving connect delay value to config: !save connect_delay <value>');
		CHANNEL_MSG('save fetch_server - Saving fetch server to config: !save fetch_server <new_server>');
		CHANNEL_MSG('save ident - Saving ident to config: !save ident <new_ident>');
		CHANNEL_MSG('save name - Saving name to config: !save name <new_name>');
		CHANNEL_MSG('save nick - Saving nickname to config: !save nick <new_nick>');
		CHANNEL_MSG('save port - Saving port to config: !save port <new_port>');
		CHANNEL_MSG('save server - Saving server to config: !save server <new_server>');
		CHANNEL_MSG('save try_connect - Saving how many times try connect to server: !save try_connect <value>');
	  }
 
  if($GLOBALS['piece1'] == 'auto_join')
    {
     SaveData('../CONFIG.INI', 'CHANNEL', 'auto_join', $GLOBALS['piece2']);
     CHANNEL_MSG('Auto_join Saved.');
     CLI_MSG('!save auto_join on: '.$GLOBALS['C_CNANNEL'].', New auto_join: '.$GLOBALS['piece2']);
    } 

  if($GLOBALS['piece1'] == 'auto_rejoin')
	{
	 SaveData('../CONFIG.INI', 'AUTOMATIC', 'auto_rejoin', $GLOBALS['piece2']);
	 CHANNEL_MSG('Auto_rejoin Saved.');
	 CLI_MSG('!save auto_rejoin on: '.$GLOBALS['C_CNANNEL'].', New auto_rejoin: '.$GLOBALS['piece2']);
	}
  
  if($GLOBALS['piece1'] == 'channel')
    {
     SaveData('../CONFIG.INI', 'CHANNEL', 'channel', $GLOBALS['piece2']);
     CHANNEL_MSG('Channel Saved.');
     CLI_MSG('!save channel on: '.$GLOBALS['C_CNANNEL'].', New channel: '.$GLOBALS['piece2']);
    } 

  if($GLOBALS['piece1'] == 'command_prefix')
    {
     SaveData('../CONFIG.INI', 'COMMAND', 'command_prefix', $GLOBALS['piece2']);
     CHANNEL_MSG('Command_prefix Saved.');
     CLI_MSG('!save command_prefix on: '.$GLOBALS['C_CNANNEL'].', New command_prefix: '.$GLOBALS['piece2']);
    } 

  if($GLOBALS['piece1'] == 'connect_delay')
	{
	 SaveData('../CONFIG.INI', 'SERVER', 'connect_delay', $GLOBALS['piece2']);
	 CHANNEL_MSG('Connect_delay Saved.');
	 CLI_MSG('!save connect_delay on: '.$GLOBALS['C_CNANNEL'].', New connect_delay: '.$GLOBALS['piece2']);
	}

  if($GLOBALS['piece1'] == 'fetch_server')
	{
     SaveData('../CONFIG.INI', 'FETCH', 'fetch_server', $GLOBALS['piece2']);
     CHANNEL_MSG('Server Saved.');
     CLI_MSG('!save fetch_server on: '.$GLOBALS['C_CNANNEL'].', New server: '.$GLOBALS['piece2']);
    }

  if($GLOBALS['piece1'] == 'ident')
	{
	 SaveData('../CONFIG.INI', 'BOT', 'ident', $GLOBALS['piece2']);
	 CHANNEL_MSG('Ident Saved.');
	 CLI_MSG('!save ident on: '.$GLOBALS['C_CNANNEL'].', New ident: '.$GLOBALS['piece2']);
	}

  if($GLOBALS['piece1'] == 'name')
	{
	 SaveData('../CONFIG.INI', 'BOT', 'name', $GLOBALS['piece2']);
	 CHANNEL_MSG('Name Saved.');
	 CLI_MSG('!save name on: '.$GLOBALS['C_CNANNEL'].', New name: '.$GLOBALS['piece2']);
	}

  if($GLOBALS['piece1'] == 'nick')
	{
     SaveData('../CONFIG.INI', 'BOT', 'nickname', $GLOBALS['piece2']);
     CHANNEL_MSG('Nick Saved.');
     CLI_MSG('!save nick on: '.$GLOBALS['C_CNANNEL'].', New nick: '.$GLOBALS['piece2']);
	}

  if($GLOBALS['piece1'] == 'port')
	{
	 SaveData('../CONFIG.INI', 'SERVER', 'port', $GLOBALS['piece2']);
	 CHANNEL_MSG('Port Saved.');
	 CLI_MSG('!save port on: '.$GLOBALS['C_CNANNEL'].', New port: '.$GLOBALS['piece2']);
	}

  if($GLOBALS['piece1'] == 'server')
	{
	 SaveData('../CONFIG.INI', 'SERVER', 'server', $GLOBALS['piece2']);
	 CHANNEL_MSG('Server Saved.');
	 CLI_MSG('!save server on: '.$GLOBALS['C_CNANNEL'].', New server: '.$GLOBALS['piece2']);
	}

  if($GLOBALS['piece1'] == 'try_connect')
	{
	 SaveData('../CONFIG.INI', 'SERVER', 'try_connect', $GLOBALS['piece2']);
	 CHANNEL_MSG('Try_connect Saved.');
	 CLI_MSG('!save try_connect on: '.$GLOBALS['C_CNANNEL'].', New try_connect: '.$GLOBALS['piece2']);
	}

  }
}

?>