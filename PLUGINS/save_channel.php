<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Saving channel to config: !save_channel <#new_channel>';

function plugin_save_channel()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("CHANNEL","channel", "$new");
 $cfg->save();

 CHANNEL_MSG('Channel Saved.');

 MSG('!savechannel on: '.$GLOBALS['channel'].', New channel: '.$GLOBALS['args']);

}

?>