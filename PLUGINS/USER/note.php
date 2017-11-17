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
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Adds a note: '.$GLOBALS['CONFIG_CMD_PREFIX'].'note help to list commands';
    $plugin_command = 'note';

function plugin_note()
{
    if (OnEmptyArg('note <help> to list commands')) {
    } else {
        if (!is_dir('../DATA')) {
            mkdir('../DATA');
        }
        $GLOBALS['ident'] = '../DATA/'.$GLOBALS['host'].'.txt';
        
        switch ($GLOBALS['args']) {
            case 'help':
                 BOT_RESPONSE('Note commands:');
                 BOT_RESPONSE($GLOBALS['CONFIG_CMD_PREFIX'].'note add <note>  - Adds a note');
                 BOT_RESPONSE($GLOBALS['CONFIG_CMD_PREFIX'].'note clear       - Delete all notes');
                 BOT_RESPONSE($GLOBALS['CONFIG_CMD_PREFIX'].'note del <numer> - Delete specified note');
                 BOT_RESPONSE($GLOBALS['CONFIG_CMD_PREFIX'].'note help        - Shows help');
                 BOT_RESPONSE($GLOBALS['CONFIG_CMD_PREFIX'].'note list        - List notes');
                break;

            case 'list':
                if (is_file($GLOBALS['ident'])) {
                    $currentNotes = fopen($GLOBALS['ident'], "r");
                    BOT_RESPONSE('Your Notes:');
                    $count = 1;
                    while (!feof($currentNotes)) {
                           BOT_RESPONSE('('.$count++.') '.fgets($currentNotes));
                    }
                    fclose($currentNotes);
                } else {
                         BOT_RESPONSE('You have no notes yet');
                }
                break;

            case 'clear':
                if (is_file($GLOBALS['ident'])) {
                    unlink($GLOBALS['ident']);
                    BOT_RESPONSE('Notes Cleared');
                } else {
                         BOT_RESPONSE('There was no notes for that user');
                }
                break;
        }
        switch ($GLOBALS['piece1']) {
            case 'add':
                if (!empty($GLOBALS['piece2'])) {
                    /* if file is empty */
                    if (is_file($GLOBALS['ident']) && filesize($GLOBALS['ident']) == 0) {
                        $note = all_note();
                      /* if there is no file */
                    } elseif (!is_file($GLOBALS['ident'])) {
                              $note = all_note();
                      /* if file exists and not empty */
                    } elseif (is_file($GLOBALS['ident']) && filesize($GLOBALS['ident']) != 0) {
                              $note = "\n".all_note();
                    }

                    $makeNote = fopen($GLOBALS['ident'], "a+");
                    fwrite($makeNote, $note);
                    fclose($makeNote);
                    BOT_RESPONSE('Note added');
                } else {
                         BOT_RESPONSE('I need some data :)');
                }
                break;

            case 'del':
                if (is_file($GLOBALS['ident'])) {
                    $writeNotes = '';
                    $notes = file($GLOBALS['ident'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $i = $GLOBALS['piece2'];

                    if (is_numeric((int)$i) && $i > 0) {
                        $j = $i-1;
                        unset($notes[$j]);

                        $newNotes = fopen($GLOBALS['ident'], "w+");
                        foreach ($notes as $value) {
                            $writeNotes = $value."\n";
                            fwrite($newNotes, $writeNotes);
                        }
                        fclose($newNotes);

                        BOT_RESPONSE('Note Deleted');
                    } else {
                             BOT_RESPONSE('Not Valid Entry');
                    }
                } else {
                         BOT_RESPONSE('You have no notes yet to delete');
                }
                break;
        }
    }
}

function all_note()
{
    $a = $GLOBALS['ex'];
    $current = '';
    $index = 5;
    
    while (isset($a[$index])) {
           $current .= $a[$index].' ';
           $index++;
    }
    $b = preg_replace('/^:/', '', $current, 1);
    return $b;
}
