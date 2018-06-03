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
//---------------------------------------------------------------------------------------------------------
if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
         Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>');
}
//---------------------------------------------------------------------------------------------------------
function StartupConfig()
{
    /* load some startup needed variables */
    if (is_file('../CONFIG.INI')) {
        $config_file = '../CONFIG.INI';
        $cfg = new IniParser($config_file);

        $GLOBALS['CONFIG_SHOW_LOGO']    = $cfg->get('PROGRAM', 'show_logo');
        $GLOBALS['silent_mode']         = $cfg->get('PROGRAM', 'silent_mode');
        $GLOBALS['CONFIG_CHECK_UPDATE'] = $cfg->get('PROGRAM', 'check_update');

        if (!in_array($GLOBALS['CONFIG_SHOW_LOGO'], ['yes', 'no'], true)) {
            $GLOBALS['CONFIG_SHOW_LOGO'] = 'yes';
        }
        if (!in_array($GLOBALS['silent_mode'], ['yes', 'no'], true)) {
            $GLOBALS['silent_mode'] = 'no';
        }
        if (!in_array($GLOBALS['CONFIG_CHECK_UPDATE'], ['yes', 'no'], true)) {
            $GLOBALS['CONFIG_CHECK_UPDATE'] = 'no';
        }
    } else {
             $GLOBALS['CONFIG_SHOW_LOGO']    = 'yes';
             $GLOBALS['silent_mode']         = 'no';
             $GLOBALS['CONFIG_CHECK_UPDATE'] = 'no';
    }
}
//---------------------------------------------------------------------------------------------------------
function LoadConfig($filename)
{
    global $cfg;
    global $config_file;

    /* check if config is loaded from -c switch */
    if (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == '-c') {
        if (isset($_SERVER['argv'][2]) && is_file($_SERVER['argv'][2])) {
            $config_file = $_SERVER['argv'][2];
        } elseif (isset($_SERVER['argv'][2]) && !is_file($_SERVER['argv'][2])) {
                   echo '  [ERROR] Config file does not exist, wrong path?'.PHP_EOL.PHP_EOL;
                   sleep(6);
                   die();
        } elseif (isset($_SERVER['argv'][1]) && empty($_SERVER['argv'][2])) {
                   echo '  [ERROR] You need to specify config file! I need some data :)'.PHP_EOL.PHP_EOL;
                   sleep(6);
                   die();
        }
    } else {
              $config_file = $filename;
    }

    if (is_file($config_file)) {
        $cfg = new IniParser($config_file);

        /* load configuration to variables */

        /* BOT */
        $GLOBALS['CONFIG_NICKNAME']       = $cfg->get('BOT', 'nickname');
        $GLOBALS['CONFIG_NAME']           = $cfg->get('BOT', 'name');
        $GLOBALS['CONFIG_IDENT']          = $cfg->get('BOT', 'ident');
        /* SERVER */
        /* if -o switch server */
        if (empty($_SERVER['argv'][2])) {
            $GLOBALS['CONFIG_SERVER']     = $cfg->get('SERVER', 'server');
        }
        /* if -o switch port */
        if (empty($_SERVER['argv'][3])) {
            $GLOBALS['CONFIG_PORT']       = $cfg->get('SERVER', 'port');
        }
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
        if (empty($GLOBALS['silent_cli'])) {
            $GLOBALS['silent_mode']       = $cfg->get('PROGRAM', 'silent_mode');
        }
        $GLOBALS['CONFIG_CHECK_UPDATE']   = $cfg->get('PROGRAM', 'check_update');
        $GLOBALS['CONFIG_PLAY_SOUNDS']    = $cfg->get('PROGRAM', 'play_sounds');
        /* DEBUG */
        $GLOBALS['CONFIG_SHOW_RAW']       = $cfg->get('DEBUG', 'show_raw');

        /* check if we have enough data to connect */
        if (empty($GLOBALS['CONFIG_NICKNAME'])) {
            CLI_MSG('[ERROR] I need nickname! No nickname in config file, Exiting.', '0');
            sleep(6);
            die();
        }
        if (empty($GLOBALS['CONFIG_SERVER'])) {
            CLI_MSG('[ERROR] I dont know where to connect! No server in config file, Exiting.', '0');
            sleep(6);
            die();
        }

        /* Set default data */
        SetDefaultData();
       
        /* if default BOT owner(s) password, prompt for change it! */
        if ($GLOBALS['CONFIG_OWNER_PASSWD'] == '47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed') {
            /* play sound */
            PlaySound('error_conn.mp3');

            /* show info about it */
            CLI_MSG(TR_13, '0');
            CLI_MSG(TR_14, '0');

            /* 'New Password:' */
            $new_pwd = getPasswd('['.@date('H:i:s').'] New Password: '.PHP_EOL);

            while (strlen($new_pwd) < 6) {
                echo '['.@date('H:i:s').'] '.TR_16.PHP_EOL;
                unset($new_pwd);
                $new_pwd = getPasswd('['.@date('H:i:s').'] New Password: '.PHP_EOL);
            }

            /* join spaces in password */
            $new_pwd = str_replace(' ', '', $new_pwd);

            /* hash pwd */
            $hashed = hash('sha256', $new_pwd);

            /* save pwd to file */
            SaveData($config_file, 'OWNER', 'owner_password', $hashed);

            /* remove pwd checking vars */
            unset($new_pwd);
            unset($hashed);

            /* Set first time change variable */
            $GLOBALS['pwd_changed'] = '1';

            CLI_MSG('Password changed', '0');
          
            /* load config again */
            LoadConfig($config_file);
        }

        /* from what file config loaded */
        CLI_MSG(TR_17.' '.$config_file, '0');
        Line(COLOR);
     
        /* logging init */
        if ($GLOBALS['CONFIG_LOGGING'] == 'yes') {
            Logs();
        }
        
        /* if all ok load plugins */
        LoadPlugins();
    } else {
             /* set default logging */
             $GLOBALS['CONFIG_LOGGING'] = 'yes';
 
             CLI_MSG(TR_18, '0');
             CLI_MSG(TR_19.' CONFIG.INI '.PHP_EOL, '0');

             /* create default config if missing */
             CreateDefaultConfig('../CONFIG.INI');
    }
}
//---------------------------------------------------------------------------------------------------------
function CreateDefaultConfig($filename)
{
    /* default config */
    $default_config = ';<?php die(); ?>

[BOT]

; bot nickname
nickname         = \'minion\'

; bot name
name             = \'http://github.com/S3x0r/MINION\'

; bot ident
ident            = \'minion\'

[SERVER]

; server where to connect
server           = \'minionki.com.pl\'

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
bot_admin        = \'S3x0r <olisek@gmail.com>\'

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

[LANG]

; set BOT language: \'EN\', \'PL\'
language         = \'EN\'

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
    SaveToFile($filename, $default_config, 'w');

    /* remove variable */
    unset($default_config);

    if (is_file($filename)) {
        /* Load config again */
        LoadConfig($filename);
    } else { /* read only file system? */
             CLI_MSG('[ERROR]: '.TR_20, '0');
             sleep(6);
             die();
    }
}
//---------------------------------------------------------------------------------------------------------
function SaveData($v1, $v2, $v3, $v4)
{
    $cfg = new IniParser($v1);
    $cfg->setValue("$v2", "$v3", "$v4");
    $cfg->save();
}
//---------------------------------------------------------------------------------------------------------
function LoadData($config_file, $section, $config)
{
    $cfg = new IniParser($config_file);
    $GLOBALS['LOADED'] = $cfg->get("$section", "$config");
}
//---------------------------------------------------------------------------------------------------------
/* configuration file parser */
class IniParser
{
    public $iniFilename = '';
    public $iniParsedArray = array();

    public function iniParser($file)
    {
        $this->iniFilename = $file;
        if ($this->iniParsedArray = parse_ini_file($file, true)) {
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
