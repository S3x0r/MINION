<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Controls winamp: !winamp <help>';
 $plugin_command = 'winamp';

/* 
   NEED TO CONFIGURE!
   Specify winamp CLAmp.exe program location

*/
    $GLOBALS['winamp_loc'] = 'C:\Dokumenty\programy\Winamp\CLAmp.exe';
//---


function plugin_winamp()
{

  if(empty($GLOBALS['args'])) { CHANNEL_MSG('Usage: '.$GLOBALS['C_CMD_PREFIX'].'winamp <help> to list commands'); } 
  
  else {

    	switch($GLOBALS['args']) {

			case "help": 
			CHANNEL_MSG('Winamp commands:');
			CHANNEL_MSG('winamp stop - Stop music: !winamp stop');
			CHANNEL_MSG('winamp pause - Pause music: !winamp pause');
			CHANNEL_MSG('winamp play - Play music: !winamp play');
			CHANNEL_MSG('winamp next - Next song: !winamp next');
			CHANNEL_MSG('winamp prev - Previous song: !winamp prev');
			CHANNEL_MSG('winamp title - Show song title: !winamp title');
			break;

			case "stop": exec($GLOBALS['winamp_loc'].' /stop'); break;
			case "pause": exec($GLOBALS['winamp_loc'].' /pause'); break;
			case "play": exec($GLOBALS['winamp_loc'].' /play'); sendTitle($target); break;
			case "next": exec($GLOBALS['winamp_loc'].' /next'); sendTitle($target); break;
			case "prev": exec($GLOBALS['winamp_loc'].' /prev'); sendTitle($target); break;
			case "title": sendTitle($target); break;
		}
 }
}

function sendTitle($target)
{
  $title = exec($GLOBALS['winamp_loc'].' /title');
  CHANNEL_MSG("Playing: ".$title, $target);
}

?>