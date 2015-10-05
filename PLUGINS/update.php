<?php

 $plugin_description = 'Checks for update: !update';
 
 $GLOBALS['dir']      = '../';
 $GLOBALS['v_addr']   = 'http://aurorix.nazwa.pl/VERSION.TXT';
 $GLOBALS['v_source'] = 'http://github.com/S3x0r/davybot/archive/master.zip';

//------------------------------------------------------------------------------------------------
 function update()
 {
  v_connect();
 }
//------------------------------------------------------------------------------------------------
function v_connect()
{
  global $socket;
  global $channel;
  global $CheckVersion;

  $CheckVersion = file_get_contents($GLOBALS['v_addr']);

	 if($CheckVersion !='')
		 {
		  v_checkVersion();
	     }
     
	 else {
		  fputs($socket, 'PRIVMSG '.$channel." :Cannot connect to update server, try next time.\n");
          }
}
//------------------------------------------------------------------------------------------------
function v_checkVersion()
{
  global $socket;
  global $channel;
  global $CheckVersion;

  $version = explode("\n", $CheckVersion);
	
  if($version[0] > VER) 
	{
	 fputs($socket, 'PRIVMSG '.$channel." :My version: ".VER.", version on server: ".$version[0]."\n");
	 v_tryDownload();
    }
	 
   else 
	{
     fputs($socket, 'PRIVMSG '.$channel." :No new update, you have the latest version.\n");
	}
}
//------------------------------------------------------------------------------------------------
function v_tryDownload()
{

  global $socket;
  global $channel;
  
	  fputs($socket, 'PRIVMSG '.$channel." :Downloading update...\n");
	  $newUpdate = file_get_contents($GLOBALS['v_source']);
      $dlHandler = fopen($GLOBALS['dir'].'update.zip', 'w');
      if(!fwrite($dlHandler, $newUpdate)) { fputs($socket, 'PRIVMSG '.$channel." :Could not save new update, operation aborted\n"); exit(); }
      fclose($dlHandler);
      fputs($socket, 'PRIVMSG '.$channel." :Update Downloaded\n");
	  v_extract();

}
//------------------------------------------------------------------------------------------------
function v_extract()
{

  global $socket;
  global $channel;

  fputs($socket, 'PRIVMSG '.$channel." :Extracting update\n");

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
	 fputs($socket, 'PRIVMSG '.$channel." :Extracted.\n");
	 zip_close($zipHandle);
	 v_createBat();
}
//------------------------------------------------------------------------------------------------
function v_createBat()
{
  global $socket;
  global $channel;	

  $data = '
del /Q readme.txt
del /Q .gitattributes
del /Q BOT.php
del /Q START_BOT.BAT
del /Q CONFIG.INI
rmdir /S /Q DOCS
rmdir /S /Q PLUGINS
mkdir DOCS
mkdir PLUGINS
cd davybot-master
copy * "../"
xcopy /E DOCS "../DOCS"
xcopy /E PLUGINS "../PLUGINS"
cd ..
rmdir /S /Q davybot-master
del update.zip
START_BOT.BAT
del INSTALL.BAT';

	$f=fopen($GLOBALS['dir'].'INSTALL.BAT', 'w');
	flock($f, 2);
	fwrite($f, $data);
	flock($f, 3);
	fclose($f); 

  fputs($socket, 'PRIVMSG '.$channel." :Installing...\n");
  sleep(2);
  system('cd .. & INSTALL.BAT');
  die();

}