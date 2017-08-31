<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Checking for updates: !checkupdate';
 $plugin_command = 'checkupdate';
//------------------------------------------------------------------------------------------------
function plugin_checkupdate()
{
  global $CheckVersion;

  CLI_MSG('!checkupdate on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'], '1');

  $CheckVersion = file_get_contents($GLOBALS['v_addr']);

	 if($CheckVersion !='')
		 {
		  checkVersion();
	     }
     
	 else {
		  CHANNEL_MSG('Cannot connect to update server, try next time.');
          }
}
//------------------------------------------------------------------------------------------------
function checkVersion()
{
  global $CheckVersion;

  $version = explode("\n", $CheckVersion);
	
  if($version[0] > VER) 
	{
	  CHANNEL_MSG('New version available!');
	  CHANNEL_MSG('My version: '.VER.', version on server: '.$version[0].'');
	  CHANNEL_MSG('To update me use !update');
    }
	 
   else 
	{
	 CHANNEL_MSG('No new update, you have the latest version.');
	}
}
//------------------------------------------------------------------------------------------------
?>