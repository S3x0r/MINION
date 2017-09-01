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
  CLI_MSG('!winamp on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'], '1');

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['C_CMD_PREFIX'].'winamp <help> to list commands'); } 
  
  else {

    	switch($GLOBALS['args']) {

			case "help": 
			BOT_RESPONSE('Winamp commands:');
			BOT_RESPONSE('winamp stop - Stop music: !winamp stop');
			BOT_RESPONSE('winamp pause - Pause music: !winamp pause');
			BOT_RESPONSE('winamp play - Play music: !winamp play');
			BOT_RESPONSE('winamp next - Next song: !winamp next');
			BOT_RESPONSE('winamp prev - Previous song: !winamp prev');
			BOT_RESPONSE('winamp title - Show song title: !winamp title');
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
  BOT_RESPONSE("Playing: ".$title, $target);
}

?>