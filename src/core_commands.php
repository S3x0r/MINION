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

PHP_SAPI !== 'cli' ? exit('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
                           Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>') : false;
//---------------------------------------------------------------------------------------------------------

function CoreCmd_Seen()
{
    if (OnEmptyArg('seen <nickname> to check specified user when was last seen on channel')) {
    } else { /* prevent directory traversal */
             $GLOBALS['args'] = str_replace('..', '', str_replace('/', '', $GLOBALS['args']));
        if ($GLOBALS['args'] == $GLOBALS['BOT_NICKNAME']) {
            BOT_RESPONSE('Yes im here! :)');
        } elseif ($GLOBALS['args'] == $GLOBALS['USER']) {
                  BOT_RESPONSE('Look at mirror!');
        } elseif ($GLOBALS['args'] == 'owner') {
            if (!empty($GLOBALS['CONFIG_BOT_ADMIN'])) {
                BOT_RESPONSE('My Owner: '.$GLOBALS['CONFIG_BOT_ADMIN']);
            }
        } else {
                 /* revert from illegal chars file */
                 $bad   = [chr('0x5c'), '/', ':', '*', '?', '"', '<', '>', '|'];
                 $good  = ["@[1]", "@[2]", "@[3]", "@[4]", "@[5]", "@[6]", "@[7]", "@[8]", "@[9]"];
                 $GLOBALS['args'] = str_replace($bad, $good, $GLOBALS['args']);

            is_file('../DATA/SEEN/'.$GLOBALS['args']) ?
                BOT_RESPONSE(file_get_contents('../DATA/SEEN/'.$GLOBALS['args'])) : BOT_RESPONSE('No such user in my database.');
            
        }
        CLI_MSG('[PLUGIN: seen] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                $GLOBALS['channel'], '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function SeenSave()
{
    if (!is_dir('../DATA')) {
        mkdir('../DATA');
        if (!is_dir('../DATA/SEEN')) {
            mkdir('../DATA/SEEN');
        }
    }
    
    $seen_dir = '../DATA/SEEN/';

    substr($GLOBALS['channel'], 0, 1) != '#' ? $chan = $GLOBALS['CONFIG_CNANNEL'] : $chan = $GLOBALS['channel'];

    $data = 'Last seen user: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') On channel: '.$chan.
        ', Date: '.date("d.m.Y").', Time: '.date("H:i:s");

    /* illegal chars for file */
    $bad  = [chr('0x5c'), '/', ':', '*', '?', '"', '<', '>', '|'];
    $good = ["@[1]", "@[2]", "@[3]", "@[4]", "@[5]", "@[6]", "@[7]", "@[8]", "@[9]"];
    $GLOBALS['USER'] = str_replace($bad, $good, $GLOBALS['USER']);

    is_file($seen_dir.$GLOBALS['USER']) ?
        file_put_contents($seen_dir.$GLOBALS['USER'], $data) : file_put_contents($seen_dir.$GLOBALS['USER'], $data);
}
//---------------------------------------------------------------------------------------------------------
function CoreCmd_Pause()
{
    BOT_RESPONSE('Pausing all activity');
  
    /* hah :) */
    $GLOBALS['stop'] = '1';

    CLI_MSG('[PLUGIN: pause] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.$GLOBALS['channel'], '1');
    CLI_MSG('[BOT] Im in Pause mode', '1');
}
//---------------------------------------------------------------------------------------------------------
function CoreCmd_Unpause()
{
    if (isset($GLOBALS['stop'])) {
        unset($GLOBALS['stop']);
        BOT_RESPONSE('Back to life!');
        CLI_MSG('[PLUGIN: unpause] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                $GLOBALS['channel'], '1');
        CLI_MSG('[BOT] Unpaused', '1');
    } else {
             BOT_RESPONSE('First i need to be paused, then i can unpause myself :p');
             BOT_RESPONSE('Use !pause first');
    }
}
//---------------------------------------------------------------------------------------------------------
function CoreCmd_Panel()
{
    if (OnEmptyArg('panel <help> to list commands')) {
    } else {
        switch ($GLOBALS['args']) {
            case 'help':
                BOT_RESPONSE('Panel commands:');
                BOT_RESPONSE('start <port> - Start panel with specified port: '
                .$GLOBALS['CONFIG_CMD_PREFIX'].'panel start <eg. 3131>');
                BOT_RESPONSE('stop         - Stop panel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'panel stop');
                break;
        }
        switch ($GLOBALS['piece1']) {
            case 'start':
                /* if windows system */
                if (!isset($GLOBALS['OS'])) {
                    $port = $GLOBALS['piece2'];
                    if (!empty($port)) {
                        if (!isRunned('serv')) {
                            if (is_file('panel/serv.exe')) {
                                $command = 'cd panel & serv.exe --http-host=0.0.0.0 --http-port='.
                                $port.' --no-https --hide-window';
                                popen($command, 'r');
                                BOT_RESPONSE('Runned.');
                                CLI_MSG('[PLUGIN: panel] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                                        $GLOBALS['channel'].' | port: '.$port, '1');
                                CLI_MSG('[PANEL] Panel Runned at port: '.$port, '1');
                            } else {
                                     BOT_RESPONSE('Cannot find web server, missing?');
                            }
                        } else {
                                 BOT_RESPONSE('Panel already runned! ...');
                        }
                    } else {
                             BOT_RESPONSE('I need port to run server!');
                    }
                } else {
                         BOT_RESPONSE('This plugin works on windows only at this time.');
                }
                break;
            case 'stop':
                if (!isset($GLOBALS['OS'])) {
                    if (kill('serv')) {
                        BOT_RESPONSE('Panel Closed');
                        CLI_MSG('[PLUGIN: panel] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                                $GLOBALS['channel'].' | STOP ', '1');
                        CLI_MSG('[PANEL] Panel Closed', '1');
                    } else {
                         BOT_RESPONSE('Panel Not runned stupid!');
                    }
                }
                break;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function CoreCmd_Load()
{
    if (empty($GLOBALS['args'])) {
        BOT_RESPONSE('Usage '.$GLOBALS['CONFIG_CMD_PREFIX'].'load <plugin_name>');
    } else {
        if (!empty($GLOBALS['piece1'])) {
            LoadPlugin($GLOBALS['piece1']);
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function CoreCmd_Unload()
{
    if (empty($GLOBALS['args'])) {
        BOT_RESPONSE('Usage '.$GLOBALS['CONFIG_CMD_PREFIX'].'unload <plugin_name>');
    } else {
        if (!empty($GLOBALS['piece1'])) {
            UnloadPlugin($GLOBALS['piece1']);
        }
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
                    LoadData($GLOBALS['config_file'], 'OWNER', 'bot_owners');
            
                    CLI_MSG('[BOT] Successful registration as owner from: '.$GLOBALS['USER'].' ('.
                            $GLOBALS['mask'].')', '1');

                    /* load owner list from config */
                    $owners_list = $GLOBALS['LOADED'];
                    $new         = trim($GLOBALS['mask']);

                    empty($owners_list) ? $new_list = $new : $new_list = $owners_list.', '.$new;

                    SaveData($GLOBALS['config_file'], 'OWNER', 'bot_owners', $new_list);

                    /* Add host to auto op list */
                    LoadData($GLOBALS['config_file'], 'OWNER', 'auto_op_list');

                    $auto_list   = $GLOBALS['LOADED'];
                    $new         = trim($GLOBALS['mask']);

                    empty($auto_list) ? $new_list = $new : $new_list = $auto_list.', '.$new;

                    SaveData($GLOBALS['config_file'], 'OWNER', 'auto_op_list', $new_list);

                    $owner_commands = implode(' ', $GLOBALS['OWNER_PLUGINS']);
                    $admin_commands = implode(' ', $GLOBALS['ADMIN_PLUGINS']);
                    $user_commands  = implode(' ', $GLOBALS['USER_PLUGINS']);
          
                    /* cli msg */
                    CLI_MSG('[BOT] New owner added: '.$GLOBALS['USER'].' ('.$GLOBALS['mask'].')', '1');
                    CLI_MSG('[BOT] New auto op added: '.$GLOBALS['USER'].' ('.$GLOBALS['mask'].')', '1');

                    /* send information to user about commands */
                    BOT_RESPONSE('From now you are on my owner(s) list, enjoy.');

                    BOT_RESPONSE('Core Commands: '.
                        $GLOBALS['CONFIG_CMD_PREFIX'].'load '.
                        $GLOBALS['CONFIG_CMD_PREFIX'].'panel '.
                        $GLOBALS['CONFIG_CMD_PREFIX'].'pause '.
                        $GLOBALS['CONFIG_CMD_PREFIX'].'seen '.
                        $GLOBALS['CONFIG_CMD_PREFIX'].'unload '.
                        $GLOBALS['CONFIG_CMD_PREFIX'].'unpause');

                    BOT_RESPONSE("Owner Commands: {$owner_commands}");
                    BOT_RESPONSE("Admin Commands: {$admin_commands}");
                    BOT_RESPONSE("User Commands: {$user_commands}");

                    /* send info who is bot admin */
                    if (!empty($GLOBALS['CONFIG_BOT_ADMIN'])) {
                        BOT_RESPONSE('Bot Admin: '.$GLOBALS['CONFIG_BOT_ADMIN']);
                    }

                    /* give op */
                    if (BotOpped() == true) {
                        fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['USER'].N);
                    }

                    /* update variable with new owners */
                    $cfg = new IniParser($GLOBALS['config_file']);
                    $GLOBALS['CONFIG_OWNERS'] = $cfg->get("OWNER", "bot_owners");
                }
            } else {
                     $hashed = hash('sha256', $GLOBALS['args']);
                /* if user is already an owner */
                if ($hashed == $GLOBALS['CONFIG_OWNER_PASSWD']) {
                    BOT_RESPONSE('You are already my owner');
                }
            }
        }
    } catch (Exception $e) {
                             CLI_MSG('[ERROR]: Function: '.__FUNCTION__.' failed', '1');
    }
}
