<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Updates the BOT if new version is available: !update';
 
 $GLOBALS['dir']      = '../';
 $GLOBALS['v_addr']   = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
 $GLOBALS['v_source'] = 'http://github.com/S3x0r/davybot/archive/master.zip';

//------------------------------------------------------------------------------------------------
 function plugin_update()
 {
  v_connect();
 }
//------------------------------------------------------------------------------------------------
function v_connect()
{
  global $CheckVersion;

  $CheckVersion = file_get_contents($GLOBALS['v_addr']);

	 if($CheckVersion !='')
		 {
		  v_checkVersion();
	     }
     
	 else {
		  CHANNEL_MSG('Cannot connect to update server, try next time.');
          }
}
//------------------------------------------------------------------------------------------------
function v_checkVersion()
{
  global $CheckVersion;

  $version = explode("\n", $CheckVersion);
	
  if($version[0] > VER) 
	{
	  CHANNEL_MSG('My version: '.VER.', version on server: '.$version[0].'');
	 v_tryDownload();
    }
	 
   else 
	{
	 CHANNEL_MSG('No new update, you have the latest version.');
	}
}
//------------------------------------------------------------------------------------------------
function v_tryDownload()
{  
      CHANNEL_MSG('Downloading update...');
	  $newUpdate = file_get_contents($GLOBALS['v_source']);
      $dlHandler = fopen($GLOBALS['dir'].'update.zip', 'w');
      if(!fwrite($dlHandler, $newUpdate)) { CHANNEL_MSG('Could not save new update, operation aborted'); exit(); }
      fclose($dlHandler);
      CHANNEL_MSG('Update Downloaded');
	  v_extract();
}
//------------------------------------------------------------------------------------------------
function v_extract()
{
  CHANNEL_MSG('Extracting update');

  $zipHandle = zip_open($GLOBALS['dir'].'update.zip');
               
   while ($aF = zip_read($zipHandle) ) 
	{
     $thisFileName = zip_entry_name($aF);
	 $thisFileDir = dirname($thisFileName);
					
	 if( substr($thisFileName,-1,1) == $GLOBALS['dir']) continue;

	 if(!is_dir($GLOBALS['dir'].''.$thisFileDir))
      {
	   mkdir($GLOBALS['dir'].''.$thisFileDir);
	   //fputs($socket, 'PRIVMSG '.$channel." :Dir: ".$thisFileDir."\n");
	  }
					
	 if(!is_dir($GLOBALS['dir'].''.$thisFileName))
	  {
       //fputs($socket, 'PRIVMSG '.$channel." : ".$thisFileName."\n");
	   $contents = zip_entry_read($aF, zip_entry_filesize($aF));
	   $contents = str_replace("\r\n", "\n", $contents);
	   $updateThis = '';
       $updateThis = fopen($GLOBALS['dir'].''.$thisFileName, 'w');
	   fwrite($updateThis, $contents);
	   fclose($updateThis);
	   unset($contents);
      }
	 }
	 CHANNEL_MSG('Extracted.');
	 zip_close($zipHandle);
	 v_createBat();
}
//------------------------------------------------------------------------------------------------
function v_createBat()
{
  $data = '
del /Q *
rmdir /S /Q DOCS
rmdir /S /Q PHP
rmdir /S /Q PLUGINS
mkdir DOCS
mkdir PHP
mkdir PLUGINS
cd davybot-master
copy * "../"
xcopy /E DOCS "../DOCS"
xcopy /E PHP "../PHP"
xcopy /E PLUGINS "../PLUGINS"
cd ..
rmdir /S /Q davybot-master
del /Q update.zip
START_BOT.BAT
del /Q INSTALL.BAT';

	$f=fopen($GLOBALS['dir'].'INSTALL.BAT', 'w');
	flock($f, 2);
	fwrite($f, $data);
	flock($f, 3);
	fclose($f); 

  CHANNEL_MSG('Installing...');
  sleep(2);
  fputs($GLOBALS['socket'],"QUIT :Installing, reconnecting\n");
  system('cd .. & INSTALL.BAT');
  die();

}