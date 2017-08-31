<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Updates the BOT if new version is available: !update';
 $plugin_command = 'update';

 $GLOBALS['v_addr']   = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
 $GLOBALS['v_source'] = 'http://github.com/S3x0r/davybot/archive/master.zip';
 $GLOBALS['CheckVersion'] = file_get_contents($GLOBALS['v_addr']);
 $GLOBALS['dir']      = '../../';
 $GLOBALS['newdir']   = $GLOBALS['dir'].'davybot'.$GLOBALS['CheckVersion'];

//------------------------------------------------------------------------------------------------
 function plugin_update()
 {
  CLI_MSG('!update on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
  v_connect();
 }
//------------------------------------------------------------------------------------------------
function v_connect()
{
 if($GLOBALS['CheckVersion'] !='')
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
  $version = explode("\n", $GLOBALS['CheckVersion']);
	
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
function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 
//------------------------------------------------------------------------------------------------
function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK );
        
        foreach( $files as $file )
        {
            delete_files( $file );
        }
		unlink($GLOBALS['newdir'].'/davybot-master/.gitattributes');
        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}
//------------------------------------------------------------------------------------------------
function v_extract()
{
  CHANNEL_MSG('Extracting update');

	$zip = new ZipArchive;
	if ($zip->open($GLOBALS['dir'].'update.zip') === TRUE) {
    $zip->extractTo($GLOBALS['newdir']);
    $zip->close();
    CHANNEL_MSG('Extracted.');
	
	recurse_copy($GLOBALS['newdir'].'/davybot-master', $GLOBALS['newdir']);
	delete_files($GLOBALS['newdir'].'/davybot-master');
	
	//delete downloaded zip
	unlink($GLOBALS['dir'].'update.zip');
	
	//copy CONFIG.INI from last version
	copy('../CONFIG.INI', $GLOBALS['newdir'].'/CONFIG.INI');

  // reconnect to run new version
  fputs($GLOBALS['socket'],"QUIT :Installing, reconnecting\n");
  system('cd '.$GLOBALS['newdir'].' & START_BOT.BAT');
  die();

} else {
    CHANNEL_MSG('Failed to extract, aborting.');
 }
}