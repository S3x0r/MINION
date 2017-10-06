<?php
/*

    API file to use with plugins

*/

//---------------------------------------------------------------------------------------------------------
/* update users (OWNER,USER) array */
function UpdatePrefix($user, $new_prefix)
{
    $GLOBALS[$user.'_PLUGINS'] = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], $new_prefix, $GLOBALS[$user.'_PLUGINS']);
}
//---------------------------------------------------------------------------------------------------------
/* if first arg after !plugin is empty */
function OnEmptyArg($info)
{
    if (empty($GLOBALS['args'])) {
        BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].''.$info);
        return true;
    } else {
              return false;
    }
}
//---------------------------------------------------------------------------------------------------------
/* sends info if bot is opped, true, false */
function BotOpped()
{
    if (isset($GLOBALS['BOT_OPPED'])) {
        return true;
    } else {
              return false;
    }
}
//---------------------------------------------------------------------------------------------------------
/* sends bot channels array */
function GetBotChannels()
{
    return $GLOBALS['BOT_CHANNELS'];
}
//---------------------------------------------------------------------------------------------------------
