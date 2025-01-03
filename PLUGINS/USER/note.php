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
    $plugin_description = 'Adds a note: '.commandPrefix().'note help to list commands';
    $plugin_command     = 'note';

function plugin_note()
{
    if (OnEmptyArg('note <help> to list commands')) {
    } else {
        $notesFilename = DATADIR."/".removeIllegalCharsFromNickname(userNickname())."-".userHostname().".txt";
    
        switch (commandFromUser()) {
            case 'help':
                 response('Note commands:');
                 response(commandPrefix().'note add <note>  - Adds a note');
                 response(commandPrefix().'note clear       - Delete all notes');
                 response(commandPrefix().'note del <numer> - Delete specified note');
                 response(commandPrefix().'note help        - Shows help');
                 response(commandPrefix().'note list        - List notes');
                break;

            case 'list':
                if (is_file($notesFilename)) {
                    $currentNotes = fopen($notesFilename, "r");
                    response('Your Notes:');
                    $count = 1;
                    while (!feof($currentNotes)) {
                           response('('.$count++.') '.fgets($currentNotes));
                    }
                    fclose($currentNotes);
                } else {
                         response('You have no notes yet.');
                }
                break;

            case 'clear':
                if (is_file($notesFilename)) {
                    unlink($notesFilename);
                    response('Notes Deleted.');
                } else {
                         response('What to delete? You have no notes yet.');
                }
                break;
        }
        switch (msgPiece()[0]) {
            case 'add':
                if (!empty(msgPiece()[1])) {
                    /* if file is empty */
                    if (is_file($notesFilename) && filesize($notesFilename) == 0) {
                        $note = inputFromLine('5');
                      /* if there is no file */
                    } elseif (!is_file($notesFilename)) {
                              $note = inputFromLine('5');
                      /* if file exists and not empty */
                    } elseif (is_file($notesFilename) && filesize($notesFilename) != 0) {
                              $note = "\n".inputFromLine('5');
                    }

                    $makeNote = fopen($notesFilename, "a+");
                    fwrite($makeNote, $note);
                    fclose($makeNote);
                    response('Note added.');
                } else {
                         response('I need some data :)');
                }
                break;

            case 'del':
                if (is_file($notesFilename)) {
                    $writeNotes = '';
                    $notes = file($notesFilename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $i = msgPiece()[1];

                    if (is_numeric((int)$i) && $i > 0) {
                        $j = $i-1;
                        unset($notes[$j]);

                        $saveNotes = fopen($notesFilename, "w+");

                        foreach ($notes as $value) {
                                 $writeNotes = $value."\n";
                                 fwrite($saveNotes, $writeNotes);
                        }
                        
                        $stat = fstat($saveNotes);

                        if ($stat['size'] == 0) {
                            unlink($notesFilename);
                        } else {
                                 ftruncate($saveNotes, $stat['size']-1);
                                 fclose($saveNotes);
                        }

                        response('Note Deleted.');
                    } else {
                             response('Not Valid Entry.');
                    }
                } else {
                         response('You have no notes yet to delete.');
                }
                break;
        }
    }
}
