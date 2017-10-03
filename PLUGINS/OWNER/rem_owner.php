<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Removes owner from config file: '
    .$GLOBALS['CONFIG_CMD_PREFIX'].'rem_owner <nick!ident@hostname>';
    $plugin_command = 'rem_owner';

function plugin_rem_owner()
{

    if (empty($GLOBALS['args'])) {
        BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'rem_owner <nick!ident@hostname>');
    } else {
              /* read owners from config */
              LoadData($GLOBALS['config_file'], 'ADMIN', 'bot_owners');
              $owners_list = $GLOBALS['LOADED'];
              $array = explode(" ", str_replace(',', '', $owners_list));

            $key = array_search($GLOBALS['args'], $array);
        if ($key !== false) {
            /* remove from host from array */
            unset($array[$key]);
                      
            /* new owners string */
            $string = implode(' ', $array);
            $string2 = str_replace(' ', ', ', $string);

            /* save new list to config */
            SaveData($GLOBALS['config_file'], 'ADMIN', 'bot_owners', $string2);

            /* update variable with new owners */
            $cfg = new IniParser($GLOBALS['config_file']);
            $GLOBALS['CONFIG_OWNERS'] = $cfg->get("ADMIN", "bot_owners");

            /* send info to user */
            BOT_RESPONSE('Host: \''.$GLOBALS['args'].'\' removed from owners.');
                      
            /* & to CLI */
            CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'rem_owner on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '
            .$GLOBALS['nick'].', OWNER REMOVED: '.$GLOBALS['args'], '1');
        }
    }
}
