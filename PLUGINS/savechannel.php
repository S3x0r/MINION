<?php

 $plugin_description = 'Saving channel to config: !savechannel <#new_channel>';

function savechannel()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("CHANNEL","channel", "$new");
 $cfg->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :Channel Saved.\n");

 MSG('!savechannel on: '.$GLOBALS['channel'].', New channel: '.$GLOBALS['args']);

}

?>