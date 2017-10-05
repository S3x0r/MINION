<?php
if (PHP_SAPI !== 'cli') {
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Shows quotes from bash.org: '.$GLOBALS['CONFIG_CMD_PREFIX'].'bash';
    $plugin_command = 'bash';

function plugin_bash()
{
    try {
           $page = file_get_contents('http://bash.org/?random1');
           preg_match_all('@<p class="qt">(.*?)</p>@s', $page, $quotes);

           if (!isset($matches[1])) {
               $matches[1] = 1;
           } elseif ($matches[1]>50) {
                     $matches[1] = 50;
           }

           $primero = true;
           for ($i=0; $i < $matches[1]; $i++) {
           if ($primero) {
            $primero = false;
           } else {
           }

           CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'bash on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
           BOT_RESPONSE(str_replace('<br />', '', html_entity_decode($quotes[1][$i], ENT_QUOTES)));
    }
} catch (Exception $e) {
                          BOT_RESPONSE(TR_49.' plugin_bash() '.TR_50);
                          CLI_MSG('[ERROR]: '.TR_49.' plugin_bash() '.TR_50, '1');
    }
}
