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

function LoadConfig($state = '')
{
    /* when there is no config from arg */
    !isset($GLOBALS['configFile']) ? $GLOBALS['configFile'] = 'CONFIG.INI' : false;

    /* if we got config file */
    if (is_file($GLOBALS['configFile'])) {
        $cfg = new IniParser($GLOBALS['configFile']);

        /* load configuration */

        /* BOT */
        $GLOBALS['CONFIG_NICKNAME']       = $cfg->get('BOT', 'nickname');
        $GLOBALS['CONFIG_NAME']           = $cfg->get('BOT', 'name');
        $GLOBALS['CONFIG_IDENT']          = $cfg->get('BOT', 'ident');

        /* SERVER */
        !isset($GLOBALS['CUSTOM_SERVER_AND_PORT']) ? $GLOBALS['CONFIG_SERVER'] = $cfg->get('SERVER', 'server') : false;
        !isset($GLOBALS['CUSTOM_SERVER_AND_PORT']) ? $GLOBALS['CONFIG_PORT'] = $cfg->get('SERVER', 'port') : false;
        
        $GLOBALS['CONFIG_SERVER_PASSWD']  = $cfg->get('SERVER', 'server_password');
        $GLOBALS['CONFIG_TRY_CONNECT']    = $cfg->get('SERVER', 'try_connect');
        $GLOBALS['CONFIG_CONNECT_DELAY']  = $cfg->get('SERVER', 'connect_delay');
        /* OWNER */
        $GLOBALS['CONFIG_BOT_ADMIN']      = $cfg->get('OWNER', 'bot_admin');
        $GLOBALS['CONFIG_AUTO_OP_LIST']   = $cfg->get('OWNER', 'auto_op_list');
        $GLOBALS['CONFIG_OWNERS']         = $cfg->get('OWNER', 'bot_owners');
        $GLOBALS['CONFIG_OWNER_PASSWD']   = $cfg->get('OWNER', 'owner_password');
        /* ADMIN */
        $GLOBALS['CONFIG_ADMIN_LIST']     = $cfg->get('ADMIN', 'admin_list');
        /* BOT RESPONSE */
        $GLOBALS['CONFIG_BOT_RESPONSE']   = $cfg->get('RESPONSE', 'bot_response');
        /* AUTOMATIC */
        $GLOBALS['CONFIG_AUTO_OP']        = $cfg->get('AUTOMATIC', 'auto_op');
        $GLOBALS['CONFIG_AUTO_REJOIN']    = $cfg->get('AUTOMATIC', 'auto_rejoin');
        $GLOBALS['CONFIG_KEEPCHAN_MODES'] = $cfg->get('AUTOMATIC', 'keep_chan_modes');
        $GLOBALS['CONFIG_KEEP_NICK']      = $cfg->get('AUTOMATIC', 'keep_nick');
        /* CHANNEL */
        $GLOBALS['CONFIG_CNANNEL']        = $cfg->get('CHANNEL', 'channel');
        $GLOBALS['CONFIG_AUTO_JOIN']      = $cfg->get('CHANNEL', 'auto_join');
        $GLOBALS['CONFIG_CHANNEL_MODES']  = $cfg->get('CHANNEL', 'channel_modes');
        $GLOBALS['CONFIG_CHANNEL_KEY']    = $cfg->get('CHANNEL', 'channel_key');
        /* BANS */
        $GLOBALS['CONFIG_BAN_LIST']       = $cfg->get('BANS', 'ban_list');
        /* COMMAND PREFIX */
        $GLOBALS['CONFIG_CMD_PREFIX']     = $cfg->get('COMMAND', 'command_prefix');
        /* CTCP */
        $GLOBALS['CONFIG_CTCP_RESPONSE']  = $cfg->get('CTCP', 'ctcp_response');
        $GLOBALS['CONFIG_CTCP_VERSION']   = $cfg->get('CTCP', 'ctcp_version');
        $GLOBALS['CONFIG_CTCP_FINGER']    = $cfg->get('CTCP', 'ctcp_finger');
        /* DELAYS */
        $GLOBALS['CONFIG_CHANNEL_DELAY']  = $cfg->get('DELAYS', 'channel_delay');
        $GLOBALS['CONFIG_PRIVATE_DELAY']  = $cfg->get('DELAYS', 'private_delay');
        $GLOBALS['CONFIG_NOTICE_DELAY']   = $cfg->get('DELAYS', 'notice_delay');
        /* LOGGING */
        $GLOBALS['CONFIG_LOGGING']        = $cfg->get('LOGS', 'logging');
        /* PANEL */
        $GLOBALS['CONFIG_WEB_LOGIN']      = $cfg->get('PANEL', 'web_login');
        $GLOBALS['CONFIG_WEB_PASSWORD']   = $cfg->get('PANEL', 'web_password');
        /* TIMEZONE */
        $GLOBALS['CONFIG_TIMEZONE']       = $cfg->get('TIME', 'time_zone');
        /* FETCH */
        $GLOBALS['CONFIG_FETCH_SERVER']   = $cfg->get('FETCH', 'fetch_server');
        /* PROGRAM */
        $GLOBALS['CONFIG_SHOW_LOGO']      = $cfg->get('PROGRAM', 'show_logo');
        /* if switch used */
        empty($GLOBALS['silent_cli']) ? $GLOBALS['silent_mode'] = $cfg->get('PROGRAM', 'silent_mode') : false;

        $GLOBALS['CONFIG_CHECK_UPDATE']   = $cfg->get('PROGRAM', 'check_update');
        $GLOBALS['CONFIG_PLAY_SOUNDS']    = $cfg->get('PROGRAM', 'play_sounds');
        /* DEBUG */
        $GLOBALS['CONFIG_SHOW_RAW']       = $cfg->get('DEBUG', 'show_raw');

        /* Set default data */
        SetDefaultData();

        if (!isset($GLOBALS['defaultPwdChanged'])) {
            /* Logo & info :) */
            Logo();
 
            /* Check if there is new version on server */
            CheckUpdateInfo();

            ($state == 'default') ? cliLog('[bot] Config file missing! Creating default config: CONFIG.INI'.N) : false;
        }

        /* if default BOT owner(s) password, prompt to change it! */
        if ($GLOBALS['CONFIG_OWNER_PASSWD'] == '47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed') {
            /* play sound */
            PlaySound('error_conn.mp3');

            /* show info about it */
            cliLog('[bot] Default BOT owner(s) password detected!');
            cliLog('[bot] For security please change it (password can not contain spaces)');

            /* Set new password */
            $newPassword = getPasswd('['.@date('H:i:s').'] New Password: ');

            /* when password to short */
            while (strlen($newPassword) < 8) {
                echo N.'['.@date('H:i:s').'] Password too short, password must be at least 8 characters long'.N;
                unset($newPassword);
                $newPassword = getPasswd('['.@date('H:i:s').'] New Password: ');
            }

            /* join spaces in password */
            $newPassword = str_replace(' ', '', $newPassword);

            /* hash pwd */
            $hashed = hash('sha256', $newPassword);

            /* save pwd to file */
            SaveData($GLOBALS['configFile'], 'OWNER', 'owner_password', $hashed);

            /* remove pwd checking vars */
            unset($newPassword);
            unset($hashed);

            /* Set first time change variable */
            $GLOBALS['defaultPwdChanged'] = 'yes';
            
            echo N;
            cliLog('[bot] Password changed!');
          
            /* update owner(s) password */
            $cfg = new IniParser($GLOBALS['configFile']);
            $GLOBALS['CONFIG_OWNER_PASSWD'] = $cfg->get('OWNER', 'owner_password');
        }

        /* from what file config loaded */
        cliLog("[bot] Configuration Loaded from: {$GLOBALS['configFile']}");
        Line();
    } else {
             /* set default data */
             $GLOBALS['CONFIG_SHOW_LOGO']    = 'yes';
             $GLOBALS['silent_mode']         = 'no';
             $GLOBALS['CONFIG_CHECK_UPDATE'] = 'no';
             $GLOBALS['CONFIG_LOGGING']      = 'yes';

             /* create default config if missing */
             CreateDefaultConfig('CONFIG.INI');
    }
}
//---------------------------------------------------------------------------------------------------------
function CreateDefaultConfig($filename)
{
    /* default config */
    $defaultConfigData = ';<?php exit; ?>

[BOT]

; bot nickname
nickname         = \'minion\'

; bot name
name             = \'http://github.com/S3x0r/MINION\'

; bot ident
ident            = \'minion\'

[SERVER]

; server where to connect
server           = \'irc.dal.net\'

; server port
port             = \'6667\'

; if irc server have password
server_password  = \'\'

; try connect \'n\' (in seconds) times to server, if cannot then quit
try_connect      = \'99\'

; delay (in seconds) after new connection to server
connect_delay    = \'6\'

[OWNER]

; bot administrator information
bot_admin        = \'S3x0r <user@localhost>\'

; bot will give op\'s if this hosts join channel <nick!ident@hostname>
auto_op_list     = \'\'

; BOT OWNER HOSTS <nick!ident@hostname>
bot_owners       = \'\'

; owner password (SHA256)
owner_password   = \'47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed\'

[ADMIN]

; bot admin list <nick!ident@hostname>
admin_list       = \'\'

[RESPONSE]

; where bot should response, you can choose from: channel, notice, priv
bot_response     = \'notice\'

[AUTOMATIC]

; bot will give op when join to channel from auto_op_list: \'yes\', \'no\'
auto_op          = \'yes\'

; bot will auto rejoin channel when kicked: \'yes\', \'no\'
auto_rejoin      = \'yes\'

; do we want to keep channel modes from channel_modes option?
keep_chan_modes  = \'yes\'

; this setting makes the bot try to get his original nickname back if its primary nickname is already in use
keep_nick        = \'yes\'

[CHANNEL]

; channel where to join when connected
channel          = \'#minion\'

; auto join channel when connected: \'yes\', \'no\'
auto_join        = \'yes\'

; set channel modes on bot join
channel_modes   = \'nt\'

; channel key if exists
channel_key      = \'\'

[BANS]

; ban users from this list
ban_list         = \'nick!ident@hostname, *!ident@hostname, *!*@onlyhost\'

[COMMAND]

; bot commands prefix eg. !info, you can change to what you want
command_prefix   = \'!\'

[CTCP]

; response to ctcp requests? \'yes\', \'no\'
ctcp_response    = \'yes\'

; ctcp version response (please do not change it:)
ctcp_version     = \'MINION ('.VER.') powered by minions!\'

; ctcf finger response
ctcp_finger      = \'MINION\'

[DELAYS]

; bot response delay on channel (in seconds)
channel_delay   = \'1.5\'

; bot response delay on private messages (in seconds)
private_delay   = \'1\'

; bot response delay on notice messages (in seconds)
notice_delay    = \'1\'

[LOGS]

; log CLI messages to LOGS folder? \'yes\', \'no\'
logging          = \'yes\'

[PANEL]

; web panel login
web_login        = \'changeme\'

; web panel password
web_password     = \'changeme\'

[TIME]

; bot time zone
time_zone        = \'Europe/Warsaw\'

[FETCH]

; bot plugin repository address
fetch_server     = \'https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master\'

[PROGRAM]

; show BOT startup logo: \'yes\', \'no\'
show_logo        = \'yes\'

; no output to CLI window: \'yes\', \'no\'
silent_mode      = \'no\'

; check on program startup if new version is on server: \'yes\', \'no\'
check_update     = \'no\'

; if we want to play sounds?: \'yes\', \'no\'
play_sounds      = \'yes\'

[DEBUG]

; show raw output on CLI window
show_raw         = \'no\'';

    /* Save default config to file if no config */
    SaveToFile($filename, $defaultConfigData, 'w');

    /* remove variable */
    unset($defaultConfigData);

    if (is_file($filename)) {
        /* Load config again */
        LoadConfig('default');
    } else { /* read only file system? */
             cliLog('[bot]: Error! Cannot make default config! Read-Only filesystem? Exiting.');
             WinSleep(6);
             exit;
    }
}
//---------------------------------------------------------------------------------------------------------
/* configuration file parser */
class IniParser
{
    public $iniFilename = '';
    public $iniParsedArray = array();

    public function __construct($file)
    {
        $this->iniFilename = $file;
        if ($this->iniParsedArray = @parse_ini_file($file, true)) {
            return true;
        } else {
            return false;
        }
    }

    public function getSection($key)
    {
        return $this->iniParsedArray[$key];
    }

    public function getValue($sec, $key)
    {
        if (!isset($this->iniParsedArray[$sec])) {
            return false;
        }
        return $this->iniParsedArray[$sec][$key];
    }

    public function get($sec, $key = null)
    {
        if (is_null($key)) {
            return $this->getSection($sec);
        }
        return $this->getValue($sec, $key);
    }

    public function setSection($sec, $array)
    {
        if (!is_array($array)) {
            return false;
        }
        return $this->iniParsedArray[$sec] = $array;
    }

    public function setValue($sec, $key, $value)
    {
        if ($this->iniParsedArray[$sec][$key] = $value) {
            return true;
        }
    }

    public function set($sec, $key, $value = null)
    {
        if (is_array($key) && is_null($value)) {
            return $this->setSection($sec, $key);
        }
        return $this->setValue($sec, $key, $value);
    }

    public function save($file = null)
    {
        if ($file == null) {
            $file = $this->iniFilename;
        }
        if (is_writeable($file)) {
            $desc = fopen($file, "w");
            foreach ($this->iniParsedArray as $sec => $array) {
                fwrite($desc, "[" . $sec . "]\r\n");
                foreach ($array as $key => $value) {
                    fwrite($desc, "$key = '$value'\r\n");
                }
                    fwrite($desc, "\r\n");
            }
            fclose($desc);
            return true;
        } else {
            return false;
        }
    }
}
