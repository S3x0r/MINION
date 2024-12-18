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

function user_notice()
{
    if (!isIgnoredUser()) {
        floodProtect('notice');

        cliNotice('<'.userNickname().'> '.inputFromLine('3'));
    }
}
//---------------------------------------------------------------------------------------------------------
function user_modes()
{
    isset(rawDataArray()[4]) ? $more = rawDataArray()[4] : $more = '';
     
    cliLogChannel('['.getBotChannel().'] * '.print_userNick_IdentHost().' sets mode: '.rawDataArray()[3].' '.$more);    
}
//---------------------------------------------------------------------------------------------------------
function user_joined_channel()
{
    if (loadValueFromConfigFile('MESSAGE', 'show users join channel') == true) {
        cliLogChannel('['.getBotChannel().'] * '.print_userNick_IdentHost().' has joined channel');
    }

    /* auto op user */
    if (BotOpped() && loadValueFromConfigFile('AUTOMATIC', 'auto op') == true && !empty(loadValueFromConfigFile('AUTOMATIC', 'auto op list')[0])) {
        $autoOpList = loadValueFromConfigFile('AUTOMATIC', 'auto op list');

        if (in_array(userNickIdentAndHostname(), $autoOpList)) {
            cliBot('User '.print_userNick_IdentHost().' on auto op list, giving op!');
            toServer('MODE '.getBotChannel().' +o '.userNickname());
            playSound('prompt.mp3');
        }
    }

    /* if owner joined send message to channel */
    if (whoIsUser()[1] == 0) {
        if (loadValueFromConfigFile('OWNER', 'owner message on join channel') == true) {
            if (!empty(loadValueFromConfigFile('OWNER', 'owner message'))) {
                toServer('PRIVMSG '.getBotChannel().' :'.userNickname().': '.loadValueFromConfigFile('OWNER', 'owner message'));
            }
        }
    }

    /* give voice users on join if true in config */
    if (whoIsUser()[1] != 0 && loadValueFromConfigFile('CHANNEL', 'give voice users on join') == true) {
        toServer('MODE '.getBotChannel().' +v '.userNickname());
    }

    SeenSave();    
}
//---------------------------------------------------------------------------------------------------------
function user_leaved_channel()
{
    if (loadValueFromConfigFile('MESSAGE', 'show users part channel') == true) {
        cliLogChannel('['.getBotChannel().'] * '.print_userNick_IdentHost().' has leaved channel');
    }
 
    SeenSave();    
}
//---------------------------------------------------------------------------------------------------------
function user_quit()
{
    if (loadValueFromConfigFile('MESSAGE', 'show users quit messages') == true) {
        cliLogChannel('['.getBotChannel().'] * '.print_userNick_IdentHost().' Quit ('.inputFromLine(3).')');
    }

    SeenSave();    
}
//---------------------------------------------------------------------------------------------------------
function user_message_channel()
{
    floodProtect('channel');

    if (loadValueFromConfigFile('MESSAGE', 'show channel user messages') == true) {

    /* no colors */ 
    $withoutColors = preg_replace('/[]]?\d+[,]?\d*/', '', inputFromLine('3'));
  
    cliLogChannel('['.getBotChannel().'] <'.userNickname().'> '.$withoutColors);
    }
}
//---------------------------------------------------------------------------------------------------------
function user_message_private()
{
    if (!isIgnoredUser()) {
        floodProtect('privmsg');

        if (loadValueFromConfigFile('MESSAGE', 'show private messages') == true) {
            cliLogChannel('<'.userNickname().'> '.inputFromLine('3'));
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function user_kicked_from_channel()
{
    if (loadValueFromConfigFile('MESSAGE', 'show channel kicks messages') == true) {
        cliLogChannel('['.getBotChannel().'] * '.print_userNick_IdentHost().' kicked '.rawDataArray()[3]);
    }    

    SeenSave();
}
//---------------------------------------------------------------------------------------------------------
function user_changed_topic()
{
    if (loadValueFromConfigFile('MESSAGE', 'show topic changes') == true) {
        cliLogChannel('['.getBotChannel().'] * '.print_userNick_IdentHost().' sets topic: "'.inputFromLine('3').'"');
    }    
}
//---------------------------------------------------------------------------------------------------------
function user_changed_nick()
{
    if (loadValueFromConfigFile('MESSAGE', 'show nick changes') == true) {
        cliLogChannel('['.getBotChannel().'] * '.userNickname(). ' changed nick to '.inputFromLine('2'));
    }    

    SeenSave();
}
//---------------------------------------------------------------------------------------------------------
function user_registered_as_owner()
{
    /* send information to user about commands */
    response('From now you are my owner, enjoy!');
    response('Core Plugins: '.allPluginsString());
    response('All Plugins: '.allPluginsWithoutCoreString());
}
