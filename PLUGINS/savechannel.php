<?php

 $plugin_description = 'Saving channel to config: !savechannel <#new_channel>';

function savechannel()
{
 global $cfg;
 $new = trim($GLOBALS['args']);

 $cfg = new iniParser("../CONFIG.INI");
 $cfg->setValue("Configuration","channel", "$new");
 $cfg->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Channel Saved.\n");

}

?>