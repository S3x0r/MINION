<?php

 $plugin_description = 'Saving channel to config: !savechannel <#new_channel>';

function savechannel()
{
$new = trim($GLOBALS['args']);

 $GLOBALS['cfg'] = new iniParser("../CONFIG/CONFIG.INI");
 $GLOBALS['cfg']->setValue("Configuration","channel", "$new");
 $GLOBALS['cfg']->save();

 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :New Channel Saved.\n");

}

?>