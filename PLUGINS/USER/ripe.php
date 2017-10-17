<?php
/* Copyright (c) 2013-2017, S3x0r <olisek@gmail.com>
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
    die('This script can\'t be run from a web browser. Use CLI to run it.');
}

    $plugin_description = 'Checks ip or host address and shows results: '
    .$GLOBALS['CONFIG_CMD_PREFIX'].'ripe <ip or host>';
    $plugin_command = 'ripe';

function plugin_ripe()
{

    if (OnEmptyArg('ripe <ip or host>')) {
    } else {
              CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'ripe on: '.$GLOBALS['channel'].', by: '
              .$GLOBALS['USER'].', address: '.$GLOBALS['args'], '1');

              BOT_RESPONSE(ripe_check_ip($GLOBALS['args']));
    }
}

function ripe_check_ip($args)
{

    if (!isset($something)) {
        $result = json_decode(file_get_contents("https://stat.ripe.net/data/whois/data.json?resource="
        .urlencode($args)."/32"), true);
        if ($result["status"]=!"ok") {
            $returnstring = 'Cannot check, no connection to server';
        } elseif (count($result["data"]["records"])==0) {
            $returnstring = 'Error, no results';
        } else {
            $data = 'IP-block: ';
            foreach ($result["data"]["records"][0] as $record) {
                switch ($record["key"]) {
                    case 'inetnum':
                    case 'netname':
                    case 'descr':
                    case 'country':
                         $data .= "[".$record["value"]."] ";
                    // FALL-TROUGH
                    default:
                }
            }
            foreach ($result["data"]["irr_records"][0] as $record) {
                switch ($record["key"]) {
                    case 'origin':
                         $data .= "| Network: AS".$record["value"]." |";
                    // FALL-TROUGH
                    default:
                }
            }
            $returnstring = "Info about ".$args.": ".$data." rDNS: ".gethostbyaddr($args);
        }
    } else {
        $ip = gethostbyname($args);
        if (!isset($something)) {
            return ripe_check_ip($ip);
        } else {
            $returnstring = 'Cannot resolve, Enter valid ip or address!';
        }
    }
    return $returnstring;
}
