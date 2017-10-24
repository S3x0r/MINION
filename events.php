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
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}

function on_server_ping()
{
    fputs($GLOBALS['socket'], "PONG ".$GLOBALS['ex'][1]."\n");
}
//---------------------------------------------------------------------------------------------------------
function on_join()
{
    /* auto op */
    if ($GLOBALS['CONFIG_AUTO_OP'] == 'yes') {
        $cfg = new IniParser($GLOBALS['config_file']);
        $GLOBALS['CONFIG_AUTO_OP_LIST'] = $cfg->get("ADMIN", "auto_op_list");

        $auto_op_list_c = $GLOBALS['CONFIG_AUTO_OP_LIST'];
        $pieces = explode(", ", $auto_op_list_c);

        $mask2 = $GLOBALS['USER'].'!'.$GLOBALS['USER_IDENT'].'@'.$GLOBALS['host'];

        if (in_array($mask2, $pieces)) {
            if (BotOpped() == true) {
                CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') has joined '.$GLOBALS['channel'], '1');
                CLI_MSG(TR_31.' '.$GLOBALS['USER'].' '.TR_32, '1');
                fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['USER']."\n");
            }
        }
    }
//---------------------------------------------------------------------------------------------------------
    /* if bot join */
    if ($GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
        array_push($GLOBALS['BOT_CHANNELS'], $GLOBALS['channel']);
        
        /* save data for web panel */
        $data = implode(' ', $GLOBALS['BOT_CHANNELS']);
        WebSave('WEB_BOT_CHANNELS', $data);
    } else {
              /* if some else join */
              CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') has joined '.$GLOBALS['channel'], '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_part()
{
    /* if bot part */
    if ($GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
        $key = array_search($GLOBALS['channel'], $GLOBALS['BOT_CHANNELS']);
        if ($key!== false) {
            unset($GLOBALS['BOT_CHANNELS'][$key]);

            /* save data for web panel */
            $data = implode(' ', $GLOBALS['BOT_CHANNELS']);
            WebSave('WEB_BOT_CHANNELS', $data);
        }
    }
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') has leaved '.$GLOBALS['channel'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_kick()
{
    /* auto rejoin when bot kicked if in config 'yes' */
    if (isset($GLOBALS['ex'][3]) && $GLOBALS['ex'][3] == $GLOBALS['BOT_NICKNAME']) {
        if ($GLOBALS['CONFIG_AUTO_REJOIN'] == 'yes') {
            CLI_MSG(TR_30, '1');
            sleep(2);
            fputs($GLOBALS['socket'], "JOIN :".$GLOBALS['ex'][2]."\n");
        }
    }
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') kicked '.
        $GLOBALS['ex'][3].' from '.$GLOBALS['channel'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_topic()
{
    /* topic change */
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') ('.
        $GLOBALS['channel'].') sets topic: '.parse_ex3(), '1');
}
//---------------------------------------------------------------------------------------------------------
function on_privmsg()
{
    CLI_MSG('['.$GLOBALS['channel'].'] <'.$GLOBALS['USER'].'> '.parse_ex3(), '1');
}
//---------------------------------------------------------------------------------------------------------
function on_mode()
{
    /* check if someone changes channel modes and set default if changed */
    if ($GLOBALS['ex'][2] == $GLOBALS['channel'] && $GLOBALS['USER'] != $GLOBALS['BOT_NICKNAME']) {
        set_channel_modes();
    }
    if (empty($GLOBALS['USER_HOST'])) {
    } else {
        /* if bot opped */
        if (isset($GLOBALS['ex'][4]) && $GLOBALS['ex'][4] == $GLOBALS['BOT_NICKNAME']) {
            if (isset($GLOBALS['ex'][3]) && $GLOBALS['ex'][3] == '+o') {
                CLI_MSG('[BOT] Ok i have op now on channel: '.$GLOBALS['channel'], '1');
                $GLOBALS['BOT_OPPED'] = 'yes';

                /* change channel modes */
                fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel']."\n");
                set_channel_modes();
            }
        }
        /* if bot deoped */
        if (isset($GLOBALS['ex'][4]) && $GLOBALS['ex'][4] == $GLOBALS['BOT_NICKNAME']) {
            if (isset($GLOBALS['ex'][3]) && $GLOBALS['ex'][3] == '-o') {
                CLI_MSG('[BOT] I dont have op now, channel: '.$GLOBALS['channel'], '1');
                unset($GLOBALS['BOT_OPPED']);
            }
        }
        if (isset($GLOBALS['ex'][4])) {
            $rest = $GLOBALS['ex'][4];
        } else {
                  $rest = '';
        }
        CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') sets mode: '.$GLOBALS['ex'][3].' '.$rest, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_nick()
{
    if ($GLOBALS['USER'] == $GLOBALS['CONFIG_NICKNAME'] && empty($GLOBALS['I_USE_RND_NICKNAME'])) {
        $GLOBALS['BOT_NICKNAME'] = str_replace(':', '', $GLOBALS['ex'][2]);
        wcliExt();
        CLI_MSG('[BOT] My new nickname is: '.$GLOBALS['BOT_NICKNAME'], '1');
    } elseif (isset($GLOBALS['BOT_NICKNAME']) && $GLOBALS['USER'] == $GLOBALS['BOT_NICKNAME']) {
              $GLOBALS['BOT_NICKNAME'] = str_replace(':', '', $GLOBALS['ex'][2]);
              wcliExt();
              CLI_MSG('[BOT] My new nickname is: '.$GLOBALS['BOT_NICKNAME'], '1');
    } else {
              $new = str_replace(':', '', $GLOBALS['ex'][2]);
              CLI_MSG(' * '.$GLOBALS['USER']. ' changed nick to '.$new, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_quit()
{
    if (isset($GLOBALS['ex'][2])) {
        $quit = $GLOBALS['ex'][2];
    } else {
              $quit = '';
    } //need fix not showing all
   
    CLI_MSG('* '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') Quit ('.$quit.')', '1');
}
//---------------------------------------------------------------------------------------------------------
function on_001() /* server message */
{
    CLI_MSG('S>'.$GLOBALS['srv_msg'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_002() /* host, version server */
{
    CLI_MSG('S>'.$GLOBALS['srv_msg'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_003() /* server creation time */
{
    CLI_MSG('S>'.$GLOBALS['srv_msg'], '1');
}
//---------------------------------------------------------------------------------------------------------
function on_324() /* channel modes */
{
    if (isset($GLOBALS['ex'][4])) {
        $GLOBALS['CHANNEL_MODES'] = str_replace('+', '', $GLOBALS['ex'][4]);
    }
}
//---------------------------------------------------------------------------------------------------------
function on_332() /* topic */
{
    CLI_MSG('Topic on: '.parse_ex3(), '1');
}
//---------------------------------------------------------------------------------------------------------
function on_353() /* on channel join inf */
{
    if (isset($GLOBALS['ex'][2]) && $GLOBALS['ex'][2] == $GLOBALS['BOT_NICKNAME']) {
        /* set channel from 353 */
        $GLOBALS['channel'] = $GLOBALS['ex'][4];

        if (isset($GLOBALS['ex'][5]) && $GLOBALS['ex'][5] == ':@'.$GLOBALS['BOT_NICKNAME']) {
            $GLOBALS['BOT_OPPED'] = 'yes';
            set_channel_modes();
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function on_376() /* join after motd */
{
    if (empty($GLOBALS['silent_mode'])) {
        echo "\n";
    }
    CLI_MSG(TR_58.' '.$GLOBALS['BOT_NICKNAME'], '1');

    /* register to bot info */
    if (isset($GLOBALS['if_first_time_pwd_change'])) {
        CLI_MSG('****************************************************', '0');
        CLI_MSG(TR_34.' /msg '.$GLOBALS['BOT_NICKNAME'].' register '.$GLOBALS['pwd'], '0');
        CLI_MSG('****************************************************', '0');
        unset($GLOBALS['pwd']);
        unset($GLOBALS['if_first_time_pwd_change']);
    }

    /* wcli extension */
    wcliExt();

    /* if autojoin */
    if ($GLOBALS['CONFIG_AUTO_JOIN'] == 'yes') {
        CLI_MSG(TR_35.' '.$GLOBALS['CONFIG_CNANNEL'], '1');
        JOIN_CHANNEL($GLOBALS['CONFIG_CNANNEL']);
    }
}
//---------------------------------------------------------------------------------------------------------
function on_422() /* join if no motd */
{
    on_376();
}
//---------------------------------------------------------------------------------------------------------
function on_432() /* if nick reserved */
{
    /* keep nick */
    if ($GLOBALS['CONFIG_KEEP_NICK']=='yes') {
        $GLOBALS['NICKNAME_FROM_CONFIG'] = $GLOBALS['CONFIG_NICKNAME'];
        $GLOBALS['I_USE_RND_NICKNAME']='1';
        $GLOBALS['first_time'] = time();
    }
   
    /* set random nick */
    $GLOBALS['BOT_NICKNAME'] = $GLOBALS['BOT_NICKNAME'].'|'.rand(0, 99);
    CLI_MSG(TR_33.' '.$GLOBALS['BOT_NICKNAME']."\n", '1');

    fputs($GLOBALS['socket'], 'NICK '.$GLOBALS['BOT_NICKNAME']."\n");
}
//---------------------------------------------------------------------------------------------------------
function on_433() /* if nick already exists */
{
    on_432();
}
//---------------------------------------------------------------------------------------------------------
function on_471() /* if +limit on channel */
{
    CLI_MSG('[BOT] I cannot join, channel is full', '1');
}
//---------------------------------------------------------------------------------------------------------
function on_473() /* if +invite on channel */
{
    CLI_MSG('[BOT] I cannot join, channel is invite only', '1');
}
//---------------------------------------------------------------------------------------------------------
function on_474() /* if bot +banned on channel */
{
    CLI_MSG('[BOT] I cannot join, im banned on channel', '1');
}
//---------------------------------------------------------------------------------------------------------
function on_475() /* if +key on channel */
{
    if (!empty($GLOBALS['CONFIG_CHANNEL_KEY'])) {
        JOIN_CHANNEL($GLOBALS['CONFIG_CNANNEL'].' '.$GLOBALS['CONFIG_CHANNEL_KEY']);
    } else {
              CLI_MSG('[BOT] I cannot join, bad channel key in config or key not set', '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function on_register_to_bot()
{
    try {
        /* check if user is not already owner */
        if (!HasOwner($GLOBALS['mask'])) {
            /* hash message from user to use for comparsion */
            $hashed = hash('sha256', $GLOBALS['args']);
        
            /* if user password match password in config do the rest */
            if ($hashed == $GLOBALS['CONFIG_OWNER_PASSWD']) {
                LoadData($GLOBALS['config_file'], 'ADMIN', 'bot_owners');
            
                /* load owner list from config */
                $owners_list = $GLOBALS['LOADED'];
                $new         = trim($GLOBALS['mask']);

                if (empty($owners_list)) {
                    $new_list = $new.'';
                }

                if (!empty($owners_list)) {
                    $new_list = $owners_list.', '.$new;
                }

                SaveData($GLOBALS['config_file'], 'ADMIN', 'bot_owners', $new_list);

                /* Add host to auto op list */
                LoadData($GLOBALS['config_file'], 'ADMIN', 'auto_op_list');

                $auto_list   = $GLOBALS['LOADED'];
                $new         = trim($GLOBALS['mask']);

                if (empty($auto_list)) {
                    $new_list = $new.'';
                }

                if (!empty($auto_list)) {
                    $new_list = $auto_list.', '.$new;
                }

                SaveData($GLOBALS['config_file'], 'ADMIN', 'auto_op_list', $new_list);

                $owner_commands = implode(' ', $GLOBALS['OWNER_PLUGINS']);
                $user_commands  = implode(' ', $GLOBALS['USER_PLUGINS']);

                /* send information to user about commands */
                NICK_MSG(TR_36);
                NICK_MSG('Core Commands: '.$GLOBALS['CONFIG_CMD_PREFIX'].'load '.
                    $GLOBALS['CONFIG_CMD_PREFIX'].'unload '.$GLOBALS['CONFIG_CMD_PREFIX'].'panel');
                NICK_MSG(TR_59.' '.$owner_commands);
                NICK_MSG(TR_60.' '.$user_commands);

                /* send info who is bot admin */
                if (!empty($GLOBALS['CONFIG_BOT_ADMIN'])) {
                    NICK_MSG('Bot Admin: '.$GLOBALS['CONFIG_BOT_ADMIN']);
                }

                /* cli msg */
                CLI_MSG(TR_43.', '.$GLOBALS['channel'].', '.TR_47.' '.$GLOBALS['mask'], '1');
                CLI_MSG(TR_44.', '.$GLOBALS['channel'].', '.TR_47.' '.$GLOBALS['mask'], '1');

                /* give op */
                if (BotOpped() == true) {
                    fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +o '.$GLOBALS['USER']."\n");
                }

                /* update variable with new owners */
                $cfg = new IniParser($GLOBALS['config_file']);
                $GLOBALS['CONFIG_OWNERS'] = $cfg->get("ADMIN", "bot_owners");

                /* remove variables */
                unset($hashed);
                unset($owners_list);
                unset($new);
                unset($new_list);
                unset($auto_list);
                unset($owner_commands);
                unset($user_commands);
            }
        } else {
                  $hashed = hash('sha256', $GLOBALS['args']);
            /* if user is already an owner */
            if ($hashed == $GLOBALS['CONFIG_OWNER_PASSWD']) {
                NICK_MSG('You are already my owner');
            }
        }
    } catch (Exception $e) {
                             BOT_RESPONSE(TR_49.' RegisterToBot() '.TR_50);
                             CLI_MSG('[ERROR]: '.TR_49.' RegisterToBot() '.TR_50, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
/* update users (OWNER,USER) array */
function UpdatePrefix($user, $new_prefix)
{
    $GLOBALS[$user.'_PLUGINS'] = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], $new_prefix, $GLOBALS[$user.'_PLUGINS']);
}
//---------------------------------------------------------------------------------------------------------
/* if first arg after !plugin is empty */
function OnEmptyArg($info)
{
    if (empty($GLOBALS['args'])) {
        BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].''.$info);
        return true;
    } else {
              return false;
    }
}
//---------------------------------------------------------------------------------------------------------
/* sends info if bot is opped, true, false */
function BotOpped()
{
    if (isset($GLOBALS['BOT_OPPED'])) {
        return true;
    } else {
              return false;
    }
}
//---------------------------------------------------------------------------------------------------------
/* sends bot channels array */
function GetBotChannels()
{
    return $GLOBALS['BOT_CHANNELS'];
}
//---------------------------------------------------------------------------------------------------------
