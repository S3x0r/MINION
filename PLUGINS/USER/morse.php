<?php
/* Copyright (c) 2013-2024, minions
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

//---------------------------------------------------------------------------------------------------------
 !in_array(PHP_SAPI, array('cli', 'cli-server', 'phpdbg')) ?
  exit('This script can\'t be run from a web browser. Use CLI terminal to run it<br>'.
       'Visit <a href="https://github.com/S3x0r/MINION/">this page</a> for more information.') : false;
//---------------------------------------------------------------------------------------------------------

    $VERIFY             = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Converts to morse code: '.commandPrefix().'morse <text>';
    $plugin_command     = 'morse';

function plugin_morse()
{
    if (OnEmptyArg('morse <text>')) {
    } else {
              $morseCode = ['a' => '.-',      'b' => '-...',    'c' => '-.-.',    'd'  => '-..',  'e'  => '.',
                            'f' => '..-.',    'g' => '--.',     'h' => '....',    'i'  => '..',   'j'  => '.---',
                            'k' => '-.-',     'l' => '.-..',    'm' => '--',      'n'  => '-.',   'o'  => '---',
                            'p' => '.--.',    'q' => '--.-',    'r' => '.-.',     's'  => '...',  't'  => '-',
                            'u' => '..-',     'v' => '...-',    'w' => '.--',     'x'  => '-..-',
                            'y' => '-.--',    'z' => '--..',    '0' => '-----',   '1'  => '.----',
                            '2' => '..---',   '3' => '...--',   '4' => '....-',   '5'  => '.....',
                            '6' => '-....',   '7' => '--...',   '8' => '---..',   '9'  => '----.',
                            '.' => '.-.-.-',  ',' => '--..--',  '?' => '..--..',  '\'' => '.----.',
                            '!' => '-.-.--',  '/' => '-..-.',   '-' => '-....-',  '"'  => '.-..-.',
                            '(' => '-.--.-',  ')' => '-.--.-',  ' ' => '/'];

        $str = strtolower(inputFromLine('4'));
        $final = null;

        for ($pos = 0; $pos < strlen($str); $pos++) {
             $care = $str[$pos];
            if (array_key_exists($care, $morseCode)) {
                $final .= $morseCode[$care].' ';
            }
        }

        response($final);
    }
}
