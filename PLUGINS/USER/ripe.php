<?php
/* Copyright (c) 2013-2018, S3x0r <olisek@gmail.com>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
         Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>');
}
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Checks ip address and shows results: '.$GLOBALS['CONFIG_CMD_PREFIX'].'ripe <ip>';
    $plugin_command = 'ripe';

function plugin_ripe()
{
    if (OnEmptyArg('ripe <ip>')) {
    } else {
        if (extension_loaded('openssl')) {
            BOT_RESPONSE(ripe_check_ip($GLOBALS['args']));

            CLI_MSG('[PLUGIN: ripe] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                    $GLOBALS['channel'].' | ip: '.$GLOBALS['args'], '1');
        } else {
                 BOT_RESPONSE('I cannot use this plugin, i need php_openssl extension to work!');
        }
    }
}

function ripe_check_ip($args)
{
    $result = json_decode(file_get_contents("https://stat.ripe.net/data/whois/data.json?resource="
              .urlencode($args)."/32"), true);

    if (empty($result)) {
        $returnstring = 'Cannot check IP, no connection to ripe server.';
    } elseif (count($result["data"]["records"])==0) {
              $returnstring = 'No results.';
    } else {
             $data = 'IP-block: ';
        foreach ($result["data"]["records"][0] as $record) {
            switch ($record["key"]) {
                case 'inetnum':
                case 'netname':
                case 'descr':
                case 'country':
                    $data .= "[".$record["value"]."] ";
                    //fall-through
                default:
            }
        }
        foreach ($result["data"]["irr_records"][0] as $record) {
            switch ($record["key"]) {
                case 'origin':
                    $data .= "| Network: AS".$record["value"]." |";
                    //fall-through
                default:
            }
        }
            $returnstring = "Info about ".$args.": ".$data." rDNS: ".gethostbyaddr($args);
    }
    return $returnstring;
}
