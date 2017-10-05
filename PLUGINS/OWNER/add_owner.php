<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Adds Owner host to config file: '
    .$GLOBALS['CONFIG_CMD_PREFIX'].'add_owner <nick!ident@hostname>';
    $plugin_command = 'add_owner';

function plugin_add_owner()
{
    $nick_ex = explode('!', trim($GLOBALS['args']));

    if (OnEmptyArg('add_owner <nick!ident@hostname>')) {
    } elseif ($nick_ex[0] != $GLOBALS['CONFIG_NICKNAME']) {
           LoadData($GLOBALS['config_file'], 'ADMIN', 'bot_owners');

           $owners_list = $GLOBALS['LOADED'];
           $new         = trim($GLOBALS['args']);
        if ($owners_list == '') {
            $new_list = $new.'';
        }
        if ($owners_list != '') {
            $new_list = $owners_list.', '.$new;
        }
           SaveData($GLOBALS['config_file'], 'ADMIN', 'bot_owners', $new_list);

           /* update variable with new owners */
           $cfg = new IniParser($GLOBALS['config_file']);
           $GLOBALS['CONFIG_OWNERS'] = $cfg->get("ADMIN", "bot_owners");

           /* inform nick about it */
           $owner_commands = implode(' ', $GLOBALS['OWNER_PLUGINS']);
           $user_commands  = implode(' ', $GLOBALS['USER_PLUGINS']);

           fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :From now you are on my owners list, enjoy.\n");
           fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :Core Commands: ".$GLOBALS['CONFIG_CMD_PREFIX']."load ".$GLOBALS['CONFIG_CMD_PREFIX']."unload\n");
           fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :Owner Commands: $owner_commands\n");
           fputs($GLOBALS['socket'], 'PRIVMSG '.$nick_ex[0]." :User Commands: $user_commands\n");
 
           CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'add_owner on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '
           .$GLOBALS['nick'].', OWNER ADDED: '.$GLOBALS['args'], '1');

           BOT_RESPONSE('Host: \''.$GLOBALS['args'].'\' added to owners.');
    } else {
              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'add_owner on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '
               .$GLOBALS['nick'].', CANNOT ADD MYSELF: '.$GLOBALS['args'], '1');

              BOT_RESPONSE('I cannot add myself to owners, im already master :)');
    }
}
