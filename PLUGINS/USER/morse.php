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

    $plugin_description = 'Converts to morse code: '.$GLOBALS['CONFIG_CMD_PREFIX'].'morse <text>';
    $plugin_command = 'morse';

function plugin_morse()
{

    if (OnEmptyArg('morse <text>')) {
    } else {
              $morse_code = array('a'  =>  '.-', 'b'  =>  '-...', 'c'  =>  '-.-.', 'd'  =>  '-..', 'e'  =>  '.',
                                  'f'  =>  '..-.', 'g'  =>  '--.', 'h'  =>  '....', 'i'  =>  '..', 'j'  =>  '.---',
                                  'k'  =>  '-.-', 'l'  =>  '.-..', 'm'  =>  '--', 'n'  =>  '-.', 'o'  =>  '---',
                                  'p'  =>  '.--.', 'q'  =>  '--.-', 'r'  =>  '.-.', 's'  =>  '...', 't'  =>  '-',
                                  'u'  =>  '..-', 'v'  =>  '...-', 'w'  =>  '.--', 'x'  =>  '-..-',
                                  'y'  =>  '-.--', 'z'  =>  '--..', '0'  =>  '-----', '1'  =>  '.----',
                                  '2'  =>  '..---', '3'  =>  '...--', '4'  =>  '....-', '5'  =>  '.....',
                                  '6'  =>  '-....', '7'  =>  '--...', '8'  =>  '---..', '9'  =>  '----.',
                                  '.'  =>  '.-.-.-', ','  =>  '--..--', '?'  =>  '..--..', '\''  =>  '.----.',
                                  '!'  =>  '-.-.--', '/'  =>  '-..-.', '-'  =>  '-....-', '"'  =>  '.-..-.',
                                  '('  =>  '-.--.-', ')'  =>  '-.--.-', ' '  =>  '/',);

        $string = strtolower(msg_without_command());
        $len = strlen($string);
        $final = null;
        for ($pos = 0; $pos < $len; $pos++) {
             $care = $string[$pos];
            if (array_key_exists($care, $morse_code)) {
                $final .= $morse_code[$care]." ";
            }
        }
        CLI_MSG($GLOBALS['CONFIG_CMD_PREFIX'].'morse on: '.$GLOBALS['channel'].', by: '.$GLOBALS['USER'], '1');
        BOT_RESPONSE($string.' converted to morse: '.rtrim($final));
    }
}
