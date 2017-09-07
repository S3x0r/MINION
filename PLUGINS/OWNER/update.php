<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Updates the BOT if new version is available: !update';
 $plugin_command = 'update';

 $GLOBALS['v_addr']   = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
 $GLOBALS['v_source'] = 'http://github.com/S3x0r/davybot/archive/master.zip';
 $GLOBALS['dir']      = '../';
 $GLOBALS['newdir']   = $GLOBALS['dir'].'davybot'.$GLOBALS['CheckVersion'];

//------------------------------------------------------------------------------------------------
 function plugin_update()
 {
  CLI_MSG('!update on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
  v_connect();
 }
//------------------------------------------------------------------------------------------------
function v_connect()
{
  $GLOBALS['CheckVersion'] = file_get_contents($GLOBALS['v_addr']);

 if($GLOBALS['CheckVersion'] !='')
		 {
		 v_checkVersion();
	     }
     
	 else {
		  BOT_RESPONSE('Cannot connect to update server, try next time.');
          }
}
//------------------------------------------------------------------------------------------------
function v_checkVersion()
{
  $version = explode("\n", $GLOBALS['CheckVersion']);
	
  if($version[0] > VER) 
	{
	  BOT_RESPONSE('My version: '.VER.', version on server: '.$version[0].'');
	 v_tryDownload();
    }
	 
   else 
	{
	 BOT_RESPONSE('No new update, you have the latest version.');
	}
}
//------------------------------------------------------------------------------------------------
function v_tryDownload()
{  
      BOT_RESPONSE('Downloading update...');
	  $newUpdate = file_get_contents($GLOBALS['v_source']);
      $dlHandler = fopen($GLOBALS['dir'].'update.zip', 'w');
      if(!fwrite($dlHandler, $newUpdate)) { BOT_RESPONSE('Could not save new update, operation aborted'); exit(); }
      fclose($dlHandler);
      BOT_RESPONSE('Update Downloaded');
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
  BOT_RESPONSE('Extracting update');

	$zip = new ZipArchive;
	if ($zip->open($GLOBALS['dir'].'update.zip') === TRUE) {
    $zip->extractTo($GLOBALS['newdir']);
    $zip->close();
    BOT_RESPONSE('Extracted.');
	
	recurse_copy($GLOBALS['newdir'].'/davybot-master', $GLOBALS['newdir']);
	delete_files($GLOBALS['newdir'].'/davybot-master');
	
	//delete downloaded zip
	unlink($GLOBALS['dir'].'update.zip');
	
	//copy CONFIG from last version
	copy($GLOBALS['config_file'], $GLOBALS['newdir'].'/OLD_CONFIG.INI');

  // reconnect to run new version
  fputs($GLOBALS['socket'],"QUIT :Installing, reconnecting\n");
  system('cd '.$GLOBALS['newdir'].' & START_BOT.BAT');
  die();

} else {
    BOT_RESPONSE('Failed to extract, aborting.');
 }
}