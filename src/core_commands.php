<?php
/* Copyright (c) 2013-2020, S3x0r <olisek@gmail.com>
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

function plugin_seen()
{
    if (OnEmptyArg('seen <nickname> to check specified user when was last seen on channel')) {
    } else { /* prevent directory traversal */
             $GLOBALS['args'] = str_replace('..', '', str_replace('/', '', $GLOBALS['args']));
        if ($GLOBALS['args'] == getBotNickname()) {
            response('Yes im here! :)');
        } elseif ($GLOBALS['args'] == $GLOBALS['USER']) {
                  response('Look at mirror!');
        } elseif ($GLOBALS['args'] == 'owner') {
            !empty($GLOBALS['CONFIG_BOT_ADMIN']) ? response("My Owner: {$GLOBALS['CONFIG_BOT_ADMIN']}") : false;
        } else {
                 /* revert from illegal chars file */
                 $GLOBALS['args'] = removeIllegalCharsFromNickname($GLOBALS['args']);

            is_file(DATADIR."/SEEN/{$GLOBALS['args']}") ?
                response(file_get_contents(DATADIR."/SEEN/{$GLOBALS['args']}")) : response('No such user in my database.');
            
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function SeenSave()
{
    !is_dir(DATADIR.'/SEEN') ? @mkdir(DATADIR.'/SEEN') : false;
    
    $seenDataDir = DATADIR.'/SEEN/';

    substr(getBotChannel(), 0, 1) != '#' ? $chan = $GLOBALS['CONFIG_CNANNEL'] : $chan = getBotChannel();

    $data = "Last seen user: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}) On channel: {$chan}, Date: ".date("d.m.Y").", Time: ".date("H:i:s");

    /* illegal chars for file */
    $GLOBALS['USER'] = removeIllegalCharsFromNickname($GLOBALS['USER']);

    is_file($seenDataDir.$GLOBALS['USER']) ?
        @file_put_contents($seenDataDir.$GLOBALS['USER'], $data) : @file_put_contents($seenDataDir.$GLOBALS['USER'], $data);
}
//---------------------------------------------------------------------------------------------------------
function plugin_pause()
{
    response('Pausing all activity');
  
    /* hah :) */
    $GLOBALS['stop'] = true;

    cliLog("[PLUGIN: pause] Used by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
    cliLog('[bot] Im in Pause mode');
}
//---------------------------------------------------------------------------------------------------------
function plugin_unpause()
{
    if (isset($GLOBALS['stop'])) {
        unset($GLOBALS['stop']);
        response('Back to life!');
        cliLog("[PLUGIN: unpause] Used by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
        cliLog('[bot] Unpaused');
    } else {
             response('First i need to be paused, then i can unpause myself :p');
             response('Use !pause first');
    }
}
//---------------------------------------------------------------------------------------------------------
function plugin_panel()
{
    if (OnEmptyArg('panel <help> to list commands')) {
    } else {
        switch ($GLOBALS['args']) {
            case 'help':
                response('Panel commands:');
                response("start <port> - Start panel with specified port: {$GLOBALS['CONFIG_CMD_PREFIX']}panel start <eg. 3131>");
                response("stop         - Stop panel: {$GLOBALS['CONFIG_CMD_PREFIX']}panel stop");
                break;
        }
        switch ($GLOBALS['piece1']) {
            case 'start':
                /* if windows system */
                if (!isset($GLOBALS['OS'])) {
                    $port = $GLOBALS['piece2'];
                    if (!empty($port)) {
                        if (!isRunned('serv')) {
                            if (is_file('src/panel/serv.exe')) {
                                $command = 'cd src/panel & serv.exe --http-host=0.0.0.0 --http-port='.
                                $port.' --no-https --hide-window';
                                popen($command, 'r');
                                response('Runned.');
                                cliLog("[PANEL] Panel Runned at port: {$port}");
                            } else {
                                     response('Cannot find web server, missing?');
                            }
                        } else {
                                 response('Panel already runned! ...');
                        }
                    } else {
                             response('I need port to run server!');
                    }
                } else {
                         response('This plugin works on windows only at this time.');
                }
                break;
            case 'stop':
                if (!isset($GLOBALS['OS'])) {
                    if (kill('serv')) {
                        response('Panel Closed');
                        cliLog('[PANEL] Panel Closed');
                    } else {
                         response('Panel Not runned stupid!');
                    }
                }
                break;
        }
    }

    cliLog("[PLUGIN: panel] Used by: {$GLOBALS['USER']} ({$GLOBALS['USER_HOST']}), channel: ".getBotChannel());
}
//---------------------------------------------------------------------------------------------------------
function plugin_load()
{
    if (empty($GLOBALS['args'])) {
        response("Usage {$GLOBALS['CONFIG_CMD_PREFIX']}load <plugin_name>");
    } else {
        !empty($GLOBALS['piece1']) ? LoadPlugin($GLOBALS['piece1']) : false;
    }
}
//---------------------------------------------------------------------------------------------------------
function plugin_unload()
{
    if (empty($GLOBALS['args'])) {
        response("Usage {$GLOBALS['CONFIG_CMD_PREFIX']}unload <plugin_name>");
    } else {
        !empty($GLOBALS['piece1']) ? UnloadPlugin($GLOBALS['piece1']) : false;
    }
}
//---------------------------------------------------------------------------------------------------------
function CoreCmd_RegisterToBot()
{
    try {
        if (empty($GLOBALS['CONFIG_OWNERS'])) {
            if (!HasOwner($GLOBALS['mask'])) {
                /* hash message from user to use for comparsion */
                $hashed = hash('sha256', $GLOBALS['args']);
        
                /* if user password match password in config do the rest */
                if ($hashed == $GLOBALS['CONFIG_OWNER_PASSWD']) {
                    LoadData($GLOBALS['configFile'], 'OWNER', 'bot_owners');
            
                    cliLog("[bot] Successful registration as owner from: {$GLOBALS['USER']} ({$GLOBALS['mask']})");

                    $new = trim($GLOBALS['mask']);

                    empty($GLOBALS['LOADED']) ? $newList = $new : $newList = "{$GLOBALS['LOADED']}, {$new}";

                    SaveData($GLOBALS['configFile'], 'OWNER', 'bot_owners', $newList);

                    /* Add host to auto op list */
                    LoadData($GLOBALS['configFile'], 'OWNER', 'auto_op_list');

                    $new = trim($GLOBALS['mask']);

                    empty($GLOBALS['LOADED']) ? $newList = $new : $newList = "{$GLOBALS['LOADED']}, {$new}";

                    SaveData($GLOBALS['configFile'], 'OWNER', 'auto_op_list', $newList);
          
                    /* cli msg */
                    cliLog("[bot] New Owner added: {$GLOBALS['USER']} ({$GLOBALS['mask']})");
                    cliLog("[bot] New Auto Op added: {$GLOBALS['USER']} ({$GLOBALS['mask']})");

                    /* send information to user about commands */
                    response('From now you are on my owner(s) list, enjoy.');

                    response("Core Commands: {$GLOBALS['CONFIG_CMD_PREFIX']}load ".
                                 "{$GLOBALS['CONFIG_CMD_PREFIX']}panel ".
                                 "{$GLOBALS['CONFIG_CMD_PREFIX']}pause ".
                                 "{$GLOBALS['CONFIG_CMD_PREFIX']}seen ".
                                 "{$GLOBALS['CONFIG_CMD_PREFIX']}unload ".
                                 "{$GLOBALS['CONFIG_CMD_PREFIX']}unpause");

                    response('Owner Commands: '.implode(' ', $GLOBALS['OWNER_PLUGINS']));
                    response('Admin Commands: '.implode(' ', $GLOBALS['ADMIN_PLUGINS']));
                    response('User Commands: '.implode(' ', $GLOBALS['USER_PLUGINS']));

                    /* send info who is bot admin */
                    !empty($GLOBALS['CONFIG_BOT_ADMIN']) ? response("Bot Admin: {$GLOBALS['CONFIG_BOT_ADMIN']}") : false;

                    /* give op */
                    if (BotOpped() == true) {
                        toServer("MODE ".getBotChannel()." +o {$GLOBALS['USER']}");
                    }

                    /* update variable with new owners */
                    $cfg = new IniParser($GLOBALS['configFile']);
                    $GLOBALS['CONFIG_OWNERS'] = $cfg->get("OWNER", "bot_owners");
                }
            } else {
                     $hashed = hash('sha256', $GLOBALS['args']);
                     /* if user is already an owner */
                     $hashed == $GLOBALS['CONFIG_OWNER_PASSWD'] ? response('You are already my owner') : false;
            }
        }
    } catch (Exception $e) {
                             cliLog('[ERROR]: Function: '.__FUNCTION__.' failed');
    }
}
