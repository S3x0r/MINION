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
/* This is first file called by BOT.PHP -> Start();
 *
 *
 */

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}
//---------------------------------------------------------------------------------------------------------
function Start()
{
//---------------------------------------------------------------------------------------------------------
    define('VER', '0.6.4');
//---------------------------------------------------------------------------------------------------------
    define('START_TIME', time());
    define('PHP_VER', phpversion());
    set_time_limit(0);
    set_error_handler('ErrorHandler');
    error_reporting(-1);
//---------------------------------------------------------------------------------------------------------

    /* check os type and set path */
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    } else {
             chdir('.');
             $GLOBALS['OS_TYPE'] = 'other';
    }

    /* load some needed variables */
    if (is_file('../CONFIG.INI')) {
        $config_file = '../CONFIG.INI';
        $cfg = new IniParser($config_file);
        $GLOBALS['CONFIG_SHOW_LOGO'] = $cfg->get('PROGRAM', 'show_logo');
        $GLOBALS['silent_mode'] = $cfg->get('PROGRAM', 'silent_mode');
        $GLOBALS['CONFIG_CHECK_UPDATE'] = $cfg->get('PROGRAM', 'check_update');
    } else {
             $GLOBALS['CONFIG_SHOW_LOGO'] = 'yes';
             $GLOBALS['silent_mode'] = 'no';
             $GLOBALS['CONFIG_CHECK_UPDATE'] = 'no';
    }

    /* Load translation file */
    SetLanguage();

    /* CLI arguments */
    if (isset($_SERVER['argv'][1])) {
        switch ($_SERVER['argv'][1]) {
            case '-h':
                echo PHP_EOL.'  '.TR_62.PHP_EOL.PHP_EOL,
                     '  -c '.TR_63.PHP_EOL,
                     '  -p '.TR_64.PHP_EOL,
                     '  -s '.TR_65.PHP_EOL,
                     '  -v '.TR_66.PHP_EOL,
                     '  -h '.TR_67.PHP_EOL.PHP_EOL;
                die();
                break;

            case '-p':
                echo PHP_EOL.' '.TR_68.PHP_EOL;
                echo PHP_EOL.' '.TR_69.' ';
                $STDIN = fopen('php://stdin', 'r');
                $pwd = fread($STDIN, 30);
                while (strlen($pwd) < 8) {
                       echo ' '.TR_16.PHP_EOL;
                       echo ' '.TR_69.' ';
                       unset($pwd);
                       $pwd = fread($STDIN, 30);
                }
                $hash = hash('sha256', rtrim($pwd, "\n\r"));
                echo PHP_EOL.' '.TR_70." $hash".PHP_EOL.PHP_EOL;
                die();

            case '-s':
                $GLOBALS['silent_cli'] = 'yes';
                $GLOBALS['silent_mode'] = 'yes';
                break;

            case '-v':
                echo PHP_EOL.' '.TR_71.' '.VER.PHP_EOL;
                die();
                break;
        }
    }
    
    /* wcli extension */
    if (extension_loaded('wcli')) {
        if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
            wcli_maximize();
            wcli_set_console_title('MINION '.VER);
            wcli_hide_cursor();
        }
    }

    /* include EVENTS file */
    if (is_file('events.php')) {
        require('events.php');
    } else {
              echo PHP_EOL.'  ERROR: I need \'EVENTS.PHP\' file to run!',
                   ' Terminating program after 5 seconds.'.PHP_EOL;
              sleep(5);
              die();
    }

    /* include TIMERS file */
    if (is_file('timers.php')) {
        require('timers.php');
    } else {
              echo PHP_EOL.'  ERROR: I need \'TIMERS.PHP\' file to run!',
                   ' Terminating program after 5 seconds.'.PHP_EOL;
              sleep(5);
              die();
    }

    /* Logo & info :) */
    if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
        if ($GLOBALS['CONFIG_SHOW_LOGO'] == 'yes' or empty($GLOBALS['CONFIG_SHOW_LOGO'])) {
            echo "
    B@B@B@B@@@B@B@B@B@B@B@B@@@B@B@B@B@B@@@@@@@B@B@B@B@B@@@B@B
    @B@BGB@B@B@B@B@@@B@@@B@B@B@@@B@B@B@B@B@@@B@B@B@@@B@@@@@B@
    B@B@  :@Bi:@B@B@B@@@B@BGS522s22SXMB@B@B@B@B@B@B@B@@@B@B@B
    @: r   :   H@B@B@B@9sr;rrs5ssss2H2229M@@@B@B@B@B@B@B@B@@@
    B         S@B@@@B,      ,::rsGB5:,  ,:i9@@B@B@B@B@B@, B@B
    @B@M,     @B@X@X   rMB@Mr:,:MS          iB@B@B2  B@   @@@
    B@@@B@    :@BGB  sB@B@;sBBrii  rB@B@B2:, :B@B@i         s
    @@@B@@@ii:sB@9X ,@@B,    BSi  9Bi ,B@B@r,  M@B@B        S
    B@@@B@B@92,@9,X  @B@,   ,@2i  @     B@GX:,  B@@,     X@@B
    @B@@@B@BMs:r@r;i i@B@G2M@S::, @s  ,X@G92,   ,B@    B@B@B@
    @@B@B@M@B2r:sssr: i29@B5i,  r :@B@B@BXr,,   ,@;::rM@B@B@B
    @B@B@B@B@Gs:rHSSsi:,,,,     ,:,,rssri,,,iir,9s  rB@B@B@B@
    B@B@B@B@B@si:XSSSsrsi::,,,::,:::,,,, ,,:;rsr,  :B@B@B@B@B
    @B@B@B@@@BG: :XXG: :rssssS3x0rS2ssr::irrrrrr  ,B@B@B@B@B@
    B@B@B@B@B@Bs  :SGM                 :rrrsr,    G@B@@@B@B@@
    @B@@@B@B@B@Xs  :SM@               ,ssss,     r@B@B@B@B@B@
    B@B@B@@@B@B2Hs  :SM@@sr:,      :sMG22s,   ,r:@@@B@B@B@B@B
    @B@B@B@B@B@2s9s,  ,::r222sHSX222srri:   ,rrirB@B@B@B@B@B@
    B@B@B@B@B@B2s292                       :rri:2@B@B@B@B@B@B
    @B@B@B@@@B@Ss29s,  ,, ,         ,     rrrii,M@@B@@@B@B@B@
    B@B@B@B@B@@MsXGs,,,,, ,,:i:,,,       ,ssrriiB@B@B@@@B@B@B
    @B@B@B@@@B@r:r5r ,,,, ,,,,, ,,       ,rii:,,@B@B@@@B@B@B@
    B@B@B@B@B@@:   ,,:,,,,          ,,          G@@@B@B@B@B@B
    @B@B@B@B@B@B   ,,,,,,,,   ,                X@B@B@B@B@B@@@
    B@B@B@B@B@B@B        , , ,,               9@B@B@B@B@B@B@B
    @B@B@@@B@B@B@Br                         i@@B@B@B@B@B@B@B@
    B@B@B@B@B@@@B@B@Br:                  rM@B@B@B@B@B@B@B@B@@
    @B@B@B@B@@@B@B@@@B@B@2           :GB@BBG9XXSSS9X9999G9GGM
    B@B@@@B@B@B@B@@@B@B@@s           Srri;i;rrrssssssss22S5HS
    @B@B@B@B@B@BBMMGG9G:              :,::::iir;rs22SXGGMMMMB
    ";
        }
    }

    if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
        echo "
    MINION - ver: ".VER.", ".TR_10." S3x0r, ".TR_11." olisek@gmail.com
                   ".TR_12." ".TotalLines()." :)
    ".PHP_EOL.PHP_EOL;
    }
    
    /* check if new version on server */
    if ($GLOBALS['CONFIG_CHECK_UPDATE'] == 'yes') {
        $url = 'https://raw.githubusercontent.com/S3x0r/version-for-BOT/master/VERSION.TXT';
        $CheckVersion = @file_get_contents($url);
        
        if ($CheckVersion !='') {
            $version = explode("\n", $CheckVersion);
            if ($version[0] > VER) {
                echo "             >>>> New version available! ($version[0]) <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
            } else {
                     echo "       >>>> No new update, you have the latest version <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
            }
        } else {
                 echo "            >>>> Cannot connect to update server <<<<".PHP_EOL.PHP_EOL.PHP_EOL;
        }
    }

    /* try to load config */
    LoadConfig('../CONFIG.INI');
}
//---------------------------------------------------------------------------------------------------------
function SetLanguage()
{
    /* is IS config file */
    if (is_file('../CONFIG.INI')) {
        $config_file = '../CONFIG.INI';
        $cfg = new IniParser($config_file);
        $GLOBALS['CONFIG_LANGUAGE'] = $cfg->get("LANG", "language");

        if (!empty($GLOBALS['CONFIG_LANGUAGE'])) {
            if (is_file('lang/'.$GLOBALS['CONFIG_LANGUAGE'].'.php')) {
                require('lang/'.$GLOBALS['CONFIG_LANGUAGE'].'.php');
            } else {
                if (is_file('lang/EN.php')) {
                    CLI_MSG('ERROR: No such language: \''.$GLOBALS['CONFIG_LANGUAGE'].'\' in LANG dir', '0');
                    CLI_MSG('[BOT] Changing to default language: EN', '0');
                    require('lang/EN.php');
                } else {
                         no_lang_file();
                }
            }
        } elseif (empty($GLOBALS['CONFIG_LANGUAGE'])) {
                  $GLOBALS['CONFIG_LANGUAGE'] = 'EN';
            if (is_file('lang/EN.php')) {
                require('lang/EN.php');
            } else {
                     no_lang_file();
            }
        }    /* if NO config file */
    } elseif (!is_file('../CONFIG.INI')) {
              $GLOBALS['CONFIG_LANGUAGE'] = 'EN';
        if (is_file('lang/EN.php')) {
            require('lang/EN.php');
        } else {
                 no_lang_file();
        }
    }

    unset($config_file);
    unset($cfg);
}
//---------------------------------------------------------------------------------------------------------
function no_lang_file()
{
    echo PHP_EOL.PHP_EOL.'ERROR: I need at least one translation in LANG directory to work! Exiting.'.PHP_EOL.PHP_EOL;
    sleep(6);
    die();
}
//---------------------------------------------------------------------------------------------------------
function LoadConfig($filename)
{
    global $cfg;
    global $config_file;

    /* check if config is loaded from -c switch */
    if (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == '-c') {
        if (isset($_SERVER['argv'][2]) && file_exists($_SERVER['argv'][2])) {
            $config_file = $_SERVER['argv'][2];
        } elseif (isset($_SERVER['argv'][2]) && !file_exists($_SERVER['argv'][2])) {
                   echo ' [ERROR] Config file does not exist, wrong path?'.PHP_EOL;
                   die();
        } elseif (isset($_SERVER['argv'][1]) && empty($_SERVER['argv'][2])) {
                   echo ' [ERROR] You need to specify config file! I need some data :)'.PHP_EOL;
                   die();
        }
    } else {
              $config_file = $filename;
    }

    if (file_exists($config_file)) {
        $cfg = new IniParser($config_file);

        /* load configuration to variables */

        /* BOT */
        $GLOBALS['CONFIG_NICKNAME']       = $cfg->get("BOT", "nickname");
        $GLOBALS['CONFIG_NAME']           = $cfg->get("BOT", "name");
        $GLOBALS['CONFIG_IDENT']          = $cfg->get("BOT", "ident");
        /* SERVER */
        $GLOBALS['CONFIG_SERVER']         = $cfg->get("SERVER", "server");
        $GLOBALS['CONFIG_PORT']           = $cfg->get("SERVER", "port");
        $GLOBALS['CONFIG_SERVER_PASSWD']  = $cfg->get("SERVER", "server_password");
        $GLOBALS['CONFIG_TRY_CONNECT']    = $cfg->get("SERVER", "try_connect");
        $GLOBALS['CONFIG_CONNECT_DELAY']  = $cfg->get("SERVER", "connect_delay");
        /* ADMIN */
        $GLOBALS['CONFIG_BOT_ADMIN']      = $cfg->get("ADMIN", "bot_admin");
        $GLOBALS['CONFIG_AUTO_OP_LIST']   = $cfg->get("ADMIN", "auto_op_list");
        $GLOBALS['CONFIG_OWNERS']         = $cfg->get("ADMIN", "bot_owners");
        $GLOBALS['CONFIG_OWNER_PASSWD']   = $cfg->get("ADMIN", "owner_password");
        /* BOT RESPONSE */
        $GLOBALS['CONFIG_BOT_RESPONSE']   = $cfg->get("RESPONSE", "bot_response");
        /* AUTOMATIC */
        $GLOBALS['CONFIG_AUTO_OP']        = $cfg->get("AUTOMATIC", "auto_op");
        $GLOBALS['CONFIG_AUTO_REJOIN']    = $cfg->get("AUTOMATIC", "auto_rejoin");
        $GLOBALS['CONFIG_KEEP_NICK']      = $cfg->get("AUTOMATIC", "keep_nick");
        /* CHANNEL */
        $GLOBALS['CONFIG_CNANNEL']        = $cfg->get("CHANNEL", "channel");
        $GLOBALS['CONFIG_AUTO_JOIN']      = $cfg->get("CHANNEL", "auto_join");
        $GLOBALS['CONFIG_CHANNEL_MODES']  = $cfg->get("CHANNEL", "channel_modes");
        $GLOBALS['CONFIG_CHANNEL_KEY']    = $cfg->get("CHANNEL", "channel_key");
        /* COMMAND PREFIX */
        $GLOBALS['CONFIG_CMD_PREFIX']     = $cfg->get("COMMAND", "command_prefix");
        /* CTCP */
        $GLOBALS['CONFIG_CTCP_RESPONSE']  = $cfg->get("CTCP", "ctcp_response");
        $GLOBALS['CONFIG_CTCP_VERSION']   = $cfg->get("CTCP", "ctcp_version");
        $GLOBALS['CONFIG_CTCP_FINGER']    = $cfg->get("CTCP", "ctcp_finger");
        /* DELAYS */
        $GLOBALS['CONFIG_CHANNEL_DELAY']  = $cfg->get("DELAYS", "channel_delay");
        $GLOBALS['CONFIG_PRIVATE_DELAY']  = $cfg->get("DELAYS", "private_delay");
        $GLOBALS['CONFIG_NOTICE_DELAY']   = $cfg->get("DELAYS", "notice_delay");
        /* LOGGING */
        $GLOBALS['CONFIG_LOGGING']        = $cfg->get("LOGS", "logging");
        /* PANEL */
        $GLOBALS['CONFIG_WEB_LOGIN']      = $cfg->get("PANEL", "web_login");
        $GLOBALS['CONFIG_WEB_PASSWORD']   = $cfg->get("PANEL", "web_password");
        /* TIMEZONE */
        $GLOBALS['CONFIG_TIMEZONE']       = $cfg->get("TIME", "time_zone");
        /* FETCH */
        $GLOBALS['CONFIG_FETCH_SERVER']   = $cfg->get("FETCH", "fetch_server");
        /* PROGRAM */
        $GLOBALS['CONFIG_SHOW_LOGO']      = $cfg->get("PROGRAM", "show_logo");
        if (empty($GLOBALS['silent_cli'])) {
            $GLOBALS['silent_mode']       = $cfg->get("PROGRAM", "silent_mode");
        }
        $GLOBALS['CONFIG_CHECK_UPDATE']   = $cfg->get("PROGRAM", "check_update");
        /* DEBUG */
        $GLOBALS['CONFIG_SHOW_RAW']       = $cfg->get("DEBUG", "show_raw");

        /* check if we have enough data to connect */
        if (empty($GLOBALS['CONFIG_NICKNAME'])) {
            CLI_MSG('[ERROR] I need nickname! No nickname in config file, Exiting.', '0');
            sleep(6);
            die();
        }
        if (empty($GLOBALS['CONFIG_SERVER'])) {
            CLI_MSG('[ERROR] No server in config file! Exiting.', '0');
            sleep(6);
            die();
        }

        /* Set default data */
        SetDefaultData();

//---------------------------------------------------------------------------------------------------------
  /* if default master password, prompt for change it! */
        if ($GLOBALS['CONFIG_OWNER_PASSWD'] == '47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed') {
            CLI_MSG(TR_13, '0');
            CLI_MSG(TR_14, '0');

            echo '['.@date('H:i:s').'] '.TR_15.' ';

            $STDIN = fopen('php://stdin', 'r');
            $new_pwd = fread($STDIN, 30);

            while (strlen($new_pwd) < 8) {
                echo '['.@date('H:i:s').'] '.TR_16.PHP_EOL;
                echo '['.@date('H:i:s').'] '.TR_15.' ';
                unset($new_pwd);
                $new_pwd = fread($STDIN, 30);
            }

            /* keep pwd as normal text */
            $GLOBALS['pwd'] = rtrim($new_pwd, "\n\r");

            /* hash pwd */
            $hashed = hash('sha256', $GLOBALS['pwd']);

            /* save pwd to file */
            SaveData($config_file, 'ADMIN', 'owner_password', $hashed);

            /* remove pwd checking vars */
            unset($new_pwd);
            unset($STDIN);
            unset($hashed);

            /* Set first time change variable */
            $GLOBALS['if_first_time_pwd_change'] = '1';

            /* load config again */
            LoadConfig($config_file);
        }
//---------------------------------------------------------------------------------------------------------  
        /* from what file config loaded */
        if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
            CLI_MSG(TR_17.' '.$config_file, '0');
            echo '------------------------------------------------------------------------------'.PHP_EOL;
        }
        /* logging init */
        if ($GLOBALS['CONFIG_LOGGING'] == 'yes') {
            Logs();
        }
        
        /* now time for plugins */
        LoadPlugins();
    } else {
             /* set default logging */
             $GLOBALS['CONFIG_LOGGING'] = 'yes';
 
             CLI_MSG(TR_18, '0');
             CLI_MSG(TR_19.' CONFIG.INI '.PHP_EOL, '0');

             /* Create default config */
             CreateDefaultConfig('../CONFIG.INI');
    }
//---------------------------------------------------------------------------------------------------------
}
//---------------------------------------------------------------------------------------------------------
function SetDefaultData()
{
    /* if variable empty in config load default one */
    if (empty($GLOBALS['CONFIG_PORT'])) {
        $GLOBALS['CONFIG_PORT'] = '6667';
    }
    if (empty($GLOBALS['CONFIG_TRY_CONNECT'])) {
        $GLOBALS['CONFIG_TRY_CONNECT'] = '10';
    }
    if (empty($GLOBALS['CONFIG_CONNECT_DELAY'])) {
        $GLOBALS['CONFIG_CONNECT_DELAY'] = '3';
    }
    if (empty($GLOBALS['CONFIG_OWNERS_PASSWD'])) {
        $GLOBALS['CONFIG_OWNERS_PASSWD'] = '47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed';
    }
    if (empty($GLOBALS['CONFIG_BOT_RESPONSE'])) {
        $GLOBALS['CONFIG_BOT_RESPONSE'] = 'channel';
    }
    if (empty($GLOBALS['CONFIG_AUTO_OP'])) {
        $GLOBALS['CONFIG_AUTO_OP'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_AUTO_REJOIN'])) {
        $GLOBALS['CONFIG_AUTO_REJOIN'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_KEEP_NICK'])) {
        $GLOBALS['CONFIG_KEEP_NICK'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_AUTO_JOIN'])) {
        $GLOBALS['CONFIG_AUTO_JOIN'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_CMD_PREFIX'])) {
        $GLOBALS['CONFIG_CMD_PREFIX'] = '!';
    }
    if (empty($GLOBALS['CONFIG_CTCP_RESPONSE'])) {
        $GLOBALS['CONFIG_CTCP_RESPONSE'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_CHANNEL_DELAY'])) {
        $GLOBALS['CONFIG_CHANNEL_DELAY'] = '1.5';
    }
    if (empty($GLOBALS['CONFIG_PRIVATE_DELAY'])) {
        $GLOBALS['CONFIG_PRIVATE_DELAY'] = '1';
    }
    if (empty($GLOBALS['CONFIG_NOTICE_DELAY'])) {
        $GLOBALS['CONFIG_NOTICE_DELAY'] = '1';
    }
    if (empty($GLOBALS['CONFIG_LOGGING'])) {
        $GLOBALS['CONFIG_LOGGING'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_LANGUAGE'])) {
        $GLOBALS['CONFIG_LANGUAGE'] = 'EN';
    }
    if (empty($GLOBALS['CONFIG_WEB_LOGIN'])) {
        $GLOBALS['CONFIG_WEB_LOGIN'] = 'changeme';
    }
    if (empty($GLOBALS['CONFIG_WEB_PASSWORD'])) {
        $GLOBALS['CONFIG_WEB_PASSWORD'] = 'changeme';
    }
    if (empty($GLOBALS['CONFIG_TIMEZONE'])) {
        $GLOBALS['CONFIG_TIMEZONE'] = 'Europe/Warsaw';
    }
    if (empty($GLOBALS['CONFIG_FETCH_SERVER'])) {
        $GLOBALS['CONFIG_FETCH_SERVER'] = 'https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master';
    }
    if (empty($GLOBALS['CONFIG_SHOW_LOGO'])) {
        $GLOBALS['CONFIG_SHOW_LOGO'] = 'yes';
    }
    if (empty($GLOBALS['CONFIG_SHOW_RAW'])) {
        $GLOBALS['CONFIG_SHOW_RAW'] = 'no';
    }

    /* set timezone */
    date_default_timezone_set($GLOBALS['CONFIG_TIMEZONE']);
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
try_connect      = \'10\'

; delay (in seconds) after new connection to server
connect_delay    = \'3\'

[ADMIN]

; bot administrator information
bot_admin        = \'S3x0r <olisek@gmail.com>\'

; bot will give op\'s if this hosts join channel 
auto_op_list     = \'S3x0r!S3x0r@Clk-945A43A3, nick!ident@some.other.host\'

; BOT OWNER HOSTS
bot_owners       = \'S3x0r!S3x0r@Clk-945A43A3, nick!ident@some.other.host\'

; owner password (SHA256)
owner_password   = \'47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed\'

[RESPONSE]

; where bot should response, you can choose from: channel, notice, priv
bot_response     = \'channel\'

[AUTOMATIC]

; bot will give op when join to channel from auto_op_list: \'yes\', \'no\'
auto_op          = \'yes\'

; bot will auto rejoin channel when kicked: \'yes\', \'no\'
auto_rejoin      = \'yes\'

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

[DEBUG]

; show raw output on CLI window
show_raw         = \'no\'';

    /* Save default config to file if no config */
    SaveToFile($filename, $default_config, 'w');

    /* remove variable */
    unset($default_config);

    if (file_exists($filename)) {
        /* Load config again */
        LoadConfig($filename);
    } elseif (!file_exists($filename)) {
              CLI_MSG('[ERROR]: '.TR_20, '0');
              die();
    }
}
//---------------------------------------------------------------------------------------------------------
function Logs()
{
    global $log_file;

    if (!is_dir('../LOGS')) {
        mkdir('../LOGS');
    }

    /* random data to prevent fetch file from panel server */
    $a = random_str('5');

    $log_file = '../LOGS/log_'.date('dmY_H-i-s').'('.$a.').TXT';

    $data = "-------------------------".TR_22." ".date('d.m.Y | H:i:s')."-------------------------\r\n";

    SaveToFile($log_file, $data, 'a');

    unset($data);
}
//---------------------------------------------------------------------------------------------------------
function LoadPlugins()
{
    $count1 = count(glob("../PLUGINS/OWNER/*.php", GLOB_BRACE));
    $GLOBALS['OWNER_PLUGINS'] = null;

    if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
        /* CORE COMMANDS */
        CLI_MSG('Core Commands (3):', '0');
        echo '------------------------------------------------------------------------------'.PHP_EOL;
        echo 'load -- Loads specified plugins to BOT: !load <plugin>'.PHP_EOL;
        echo 'panel -- Starts web admin panel for BOT: !panel help'.PHP_EOL;
        echo 'unload -- Unloads specified plugin from BOT: !unload <plugin>'.PHP_EOL;
        echo '------------------------------------------------------------------------------'.PHP_EOL;
        
        /* OWNERS PLUGINS */
        CLI_MSG(TR_23." ($count1):", '0');
        echo '------------------------------------------------------------------------------'.PHP_EOL;
    }
    foreach (glob('../PLUGINS/OWNER/*.php') as $plugin_name) {
        include_once($plugin_name);
        $GLOBALS['OWNER_PLUGINS'] .= $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' ';
        $plugin_name = basename($plugin_name, '.php');
        if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
            echo "$plugin_name -- $plugin_description".PHP_EOL;
        }
    }
    if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
        echo '------------------------------------------------------------------------------'.PHP_EOL;
    }
//---------------------------------------------------------------------------------------------------------
    $count2 = count(glob("../PLUGINS/USER/*.php", GLOB_BRACE));

    $GLOBALS['USER_PLUGINS'] = null;

    if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
        CLI_MSG(TR_24." ($count2):", '0');
        echo '------------------------------------------------------------------------------'.PHP_EOL;
    }
    foreach (glob('../PLUGINS/USER/*.php') as $plugin_name) {
        include_once($plugin_name);
        $GLOBALS['USER_PLUGINS'] .= $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' ';
        $plugin_name = basename($plugin_name, '.php');
        if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
            echo "$plugin_name -- $plugin_description".PHP_EOL;
        }
    }
    $tot = $count1+$count2+3;
    
    if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
        echo "----------------------------------------------------------".TR_25." ($tot)---------".PHP_EOL;
    }

    /* OWNER Plugins array */
    $GLOBALS['OWNER_PLUGINS'] = explode(" ", $GLOBALS['OWNER_PLUGINS']);
    
    /* USER Plugins array */
    $GLOBALS['USER_PLUGINS'] = explode(" ", $GLOBALS['USER_PLUGINS']);

    /* remove variables */
    unset($count1);
    unset($count2);
    unset($tot);
    unset($plugin_name);
    unset($plugin_command);
    unset($plugin_description);

    /* Now its time to connect */
    Connect();
}
//---------------------------------------------------------------------------------------------------------
function Connect()
{
    CLI_MSG(TR_27.' '.$GLOBALS['CONFIG_SERVER'].', '.TR_26.' '.$GLOBALS['CONFIG_PORT'].PHP_EOL, '1');

    $i=0;

    while ($i++ < $GLOBALS['CONFIG_TRY_CONNECT']) {
           $GLOBALS['socket'] = fsockopen($GLOBALS['CONFIG_SERVER'], $GLOBALS['CONFIG_PORT']);
           //socket_set_blocking($GLOBALS['socket'], false);
        if ($GLOBALS['socket']==false) {
            CLI_MSG(TR_28, '1');
            usleep($GLOBALS['CONFIG_CONNECT_DELAY'] * 1000000);
            if ($i==$GLOBALS['CONFIG_TRY_CONNECT']) {
                CLI_MSG(TR_29, '1');
                die();
            }
        } else {
                 Identify();
                 unset($i);
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function Identify()
{
    /* send PASSWORD / NICK / USER to server */

    if (!empty($GLOBALS['CONFIG_SERVER_PASSWD'])) {
        fputs($GLOBALS['socket'], 'PASS '.$GLOBALS['CONFIG_SERVER_PASSWD']."\n");
    }

    fputs($GLOBALS['socket'], 'NICK '.$GLOBALS['CONFIG_NICKNAME']."\n");

    fputs($GLOBALS['socket'], 'USER '.$GLOBALS['CONFIG_IDENT'].' 8 * :'.$GLOBALS['CONFIG_NAME']."\n");

    /* time for socket loop */
    Engine();
}
//---------------------------------------------------------------------------------------------------------
function WebEntry()
{
    $data = "[MAIN]
WEB_VERSION         = ".VER."
WEB_START_TIME      = ".START_TIME."
WEB_PHP_VERSION     = ".PHP_VER."
WEB_BOT_CONFIG_FILE = ".$GLOBALS['config_file'];
    
    /* save some variables to web.ini */
    SaveToFile('PANEL\web.ini', $data, 'w');

    $config_file = '../CONFIG.INI';
    $cfg = new IniParser($config_file);
    $GLOBALS['CONFIG_WEB_LOGIN'] = $cfg->get('PANEL', 'web_login');
    $GLOBALS['CONFIG_WEB_PASSWORD'] = $cfg->get('PANEL', 'web_password');
    
    /* generate random string for cookie salt */
    $string = random_str('16');

    /* save data to panel config */
    SaveData('PANEL\web.ini', 'PANEL', 'web_login', $GLOBALS['CONFIG_WEB_LOGIN']);
    SaveData('PANEL\web.ini', 'PANEL', 'web_password', $GLOBALS['CONFIG_WEB_PASSWORD']);
    SaveData('PANEL\web.ini', 'PANEL', 'web_salt', $string);
}
//---------------------------------------------------------------------------------------------------------
function random_str($length)
{
    $seed = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i=0; $i < $length; $i++) {
         $str .= substr($seed, mt_rand(0, strlen($seed) -1), 1);
    }
    return $str;
}
//---------------------------------------------------------------------------------------------------------
function WebSave($v1, $v2)
{
    $cfg = new IniParser('PANEL\web.ini');
    $cfg->setValue("MAIN", "$v1", "$v2");
    $cfg->save();
}
//---------------------------------------------------------------------------------------------------------
function Engine()
{
    global $args;
    global $args1;
    global $USER;
    global $USER_IDENT;
    global $USER_HOST;
    global $host;
    global $piece1;
    global $piece2;
    global $piece3;
    global $piece4;
    global $ex;
    global $rawcmd;
    global $mask;
    global $srv_msg;

    global $BOT_CHANNELS;
    global $BOT_NICKNAME;
    global $channel;
    global $I_USE_RND_NICKNAME;

//---------------------------------------------------------------------------------------------------------
    /* set initial */
    $USER_IDENT = null;
    $host  = null;
    $BOT_NICKNAME = $GLOBALS['CONFIG_NICKNAME'];
    $channel = $GLOBALS['CONFIG_CNANNEL'];
    $I_USE_RND_NICKNAME = null;
    $BOT_CHANNELS = array();

    /* save data for web panel */
    WebEntry();

    /* set timers */
    $GLOBALS['TIMER1'] = time();
    $GLOBALS['TIMER2'] = time();
    $GLOBALS['TIMER3'] = time();
    $GLOBALS['TIMER4'] = time();
    $GLOBALS['TIMER5'] = time();
    $GLOBALS['TIMER6'] = time();
    $GLOBALS['TIMER7'] = time();
    $GLOBALS['TIMER8'] = time();
    $GLOBALS['TIMER9'] = time();
    $GLOBALS['TIMER10'] = time();
    $GLOBALS['TIMER11'] = time();
    $GLOBALS['TIMER12'] = time();
    $GLOBALS['TIMER13'] = time();
//---------------------------------------------------------------------------------------------------------
    /* main socket loop */
    while (1) {
        while (!feof($GLOBALS['socket'])) {
            $mask = null;

            /* get data */
            $data = fgets($GLOBALS['socket'], 1024);
//---------------------------------------------------------------------------------------------------------
            if ($GLOBALS['CONFIG_SHOW_RAW'] == 'yes') {
                if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
                    echo $data;
                }
            }
//---------------------------------------------------------------------------------------------------------            
            /* put data to array */
            $ex = explode(' ', trim($data));
    
            /* get channel from ex[2] */
            if (isset($ex[2])) {
                $channel = str_replace(':#', '#', $ex[2]);
            }
//---------------------------------------------------------------------------------------------------------
            /* PING PONG game */
            if (isset($ex[0]) && $ex[0] == 'PING') {
                on_server_ping();
            }
//---------------------------------------------------------------------------------------------------------
            /* parse vars from ex[0] */
            if (preg_match('/^:(.*)\!(.*)\@(.*)$/', $ex[0], $source)) {
                $USER        = $source[1];
                $USER_IDENT  = $source[2];
                $host        = $source[3];
                $USER_HOST   = $USER_IDENT.'@'.$host;
            } else {
                    /* put server to var and remove ':' */
                    $server = str_replace(':', '', $ex[0]);
            }
//---------------------------------------------------------------------------------------------------------
            /* ON JOIN */
            if (isset($ex[1]) && $ex[1] == 'JOIN') {
                on_join();
            }
//---------------------------------------------------------------------------------------------------------
            /* ON PART */
            if (isset($ex[1]) && $ex[1] == 'PART') {
                on_part();
            }
//---------------------------------------------------------------------------------------------------------
            /* ON KICK */
            if (isset($ex[1]) && $ex[1] == 'KICK') {
                on_kick();
            }
//---------------------------------------------------------------------------------------------------------
            /* ON TOPIC */
            if (isset($ex[1]) && $ex[1] == 'TOPIC') {
                on_topic();
            }
//---------------------------------------------------------------------------------------------------------
            /* ON PRIVMSG */
            if (isset($ex[1]) && $ex[1] == 'PRIVMSG') {
                on_privmsg();
            }
//---------------------------------------------------------------------------------------------------------
            /* ON MODE */
            if (isset($ex[1]) && $ex[1] == 'MODE') {
                on_mode();
            }
//---------------------------------------------------------------------------------------------------------
            /* ON NICK */
            if (isset($ex[1]) && $ex[1] == 'NICK') {
                on_nick();
            }
//---------------------------------------------------------------------------------------------------------
            /* ON QUIT */
            if (isset($ex[1]) && $ex[1] == 'QUIT') {
                on_quit();
            }
//---------------------------------------------------------------------------------------------------------
            if (count($ex) < 4) {
                continue;
            }

            $rawcmd = explode(':', $ex[3]);

            /* Case sensitive */
            if (isset($rawcmd[1])) {
                $rawcmd[1] = strtolower($rawcmd[1]);
            }

            $args = null; for ($i=4; $i < count($ex); $i++) {
                $args .= $ex[$i].'';
            }
            $args1 = null; for ($i=4; $i < count($ex); $i++) {
                $args1 .= $ex[$i].' ';
            }
            $srv_msg = null; for ($i=3; $i < count($ex); $i++) {
                $srv_msg .= str_replace(':', '', $ex[$i]).' ';
            }

            if (isset($USER)) {
                $mask = $USER.'!'.$USER_IDENT.'@'.$host;
            }

            $pieces = explode(" ", $args1);

            if (isset($pieces[0])) {
                $piece1 = $pieces[0];
            } else {
                     $piece1 = '';
            }
            if (isset($pieces[1])) {
                $piece2 = $pieces[1];
            } else {
                     $piece2 = '';
            }
            if (isset($pieces[2])) {
                $piece3 = $pieces[2];
            } else {
                     $piece3 = '';
            }
            if (isset($pieces[3])) {
                $piece4 = $pieces[3];
            } else {
                     $piece4 = '';
            }
//---------------------------------------------------------------------------------------------------------
            if (isset($ex[1])) {
                switch ($ex[1]) {
//---------------------------------------------------------------------------------------------------------
                    case '001': /* server welcome message */
                        on_001();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '002': /* host, version server */
                        on_002();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '003': /* server creation time */
                        on_003();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '332': /* topic */
                        on_332();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '433': /* if nick already exists */
                        on_432();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '432': /* if nick reserved */
                        on_432();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '422': /* join if no motd */
                        on_376();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '376': /* join after motd */
                        on_376();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '324': /* channel modes */
                        on_324();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '353': /* on channel join inf */
                        on_353();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '471': /* if +limit on channel */
                        on_471();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '473': /* if +invite on channel */
                        on_473();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '474': /* if bot +banned on channel */
                        on_474();
                        break;
//---------------------------------------------------------------------------------------------------------
                    case '475': /* if +key on channel */
                        on_475();
                        break;
//---------------------------------------------------------------------------------------------------------
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* TIMERS - 1 minute */
            if (time()-$GLOBALS['TIMER1'] > 60) {
                every_1_minute();
                $GLOBALS['TIMER1'] = time();
            }
            /* TIMERS - 5 minutes */
            if (time()-$GLOBALS['TIMER2'] > 300) {
                every_5_minutes();
                $GLOBALS['TIMER2'] = time();
            }
            /* TIMERS - 10 minutes */
            if (time()-$GLOBALS['TIMER3'] > 600) {
                every_10_minutes();
                $GLOBALS['TIMER3'] = time();
            }
            /* TIMERS - 15 minutes */
            if (time()-$GLOBALS['TIMER4'] > 900) {
                every_15_minutes();
                $GLOBALS['TIMER4'] = time();
            }
            /* TIMERS - 20 minutes */
            if (time()-$GLOBALS['TIMER5'] > 1200) {
                every_20_minutes();
                $GLOBALS['TIMER5'] = time();
            }
            /* TIMERS - 25 minutes */
            if (time()-$GLOBALS['TIMER6'] > 1500) {
                every_25_minutes();
                $GLOBALS['TIMER6'] = time();
            }
            /* TIMERS - 30 minutes */
            if (time()-$GLOBALS['TIMER7'] > 1800) {
                every_30_minutes();
                $GLOBALS['TIMER7'] = time();
            }
            /* TIMERS - 35 minutes */
            if (time()-$GLOBALS['TIMER8'] > 2100) {
                every_35_minutes();
                $GLOBALS['TIMER8'] = time();
            }
            /* TIMERS - 40 minutes */
            if (time()-$GLOBALS['TIMER9'] > 2400) {
                every_40_minutes();
                $GLOBALS['TIMER9'] = time();
            }
            /* TIMERS - 45 minutes */
            if (time()-$GLOBALS['TIMER10'] > 2700) {
                every_45_minutes();
                $GLOBALS['TIMER10'] = time();
            }
            /* TIMERS - 50 minutes */
            if (time()-$GLOBALS['TIMER11'] > 3000) {
                every_50_minutes();
                $GLOBALS['TIMER11'] = time();
            }
            /* TIMERS - 55 minutes */
            if (time()-$GLOBALS['TIMER12'] > 3300) {
                every_55_minutes();
                $GLOBALS['TIMER12'] = time();
            }
            /* TIMERS - 60 minutes */
            if (time()-$GLOBALS['TIMER13'] > 3600) {
                every_60_minutes();
                $GLOBALS['TIMER13'] = time();
            }
//---------------------------------------------------------------------------------------------------------
            /* CTCP */
            if ($GLOBALS['CONFIG_CTCP_RESPONSE'] == 'yes' && isset($rawcmd[1])) {
                switch ($rawcmd[1]) {
                    case 'version':
                        fputs($GLOBALS['socket'], "NOTICE $USER :VERSION ".$GLOBALS['CONFIG_CTCP_VERSION']."\n");
                        CLI_MSG('CTCP VERSION '.TR_48.' '.$USER, '1');
                        break;

                    case 'clientinfo':
                        fputs($GLOBALS['socket'], "NOTICE $USER :CLIENTINFO ".$GLOBALS['CONFIG_CTCP_VERSION']."\n");
                        CLI_MSG('CTCP CLIENTINFO '.TR_48.' '.$USER, '1');
                        break;

                    case 'source':
                        fputs($GLOBALS['socket'], "NOTICE $USER :SOURCE http://github.com/S3x0r/MINION\n");
                        CLI_MSG('CTCP SOURCE '.TR_48.' '.$USER, '1');
                        break;

                    case 'userinfo':
                        fputs($GLOBALS['socket'], "NOTICE $USER :USERINFO Powered by Minions!\n");
                        CLI_MSG('CTCP USERINFO '.TR_48.' '.$USER, '1');
                        break;

                    case 'finger':
                        fputs($GLOBALS['socket'], "NOTICE $USER :FINGER ".$GLOBALS['CONFIG_CTCP_FINGER']."\n");
                        CLI_MSG('CTCP FINGER '.TR_48.' '.$USER, '1');
                        break;

                    case 'ping':
                        $a = str_replace(" ", "", $args);
                        fputs($GLOBALS['socket'], "NOTICE $USER :PING ".$a."\n");
                        CLI_MSG('CTCP PING '.TR_48.' '.$USER, '1');
                        break;

                    case 'time':
                        $a = date("F j, Y, g:i a");
                        fputs($GLOBALS['socket'], "NOTICE $USER :TIME ".$a."\n");
                        CLI_MSG('CTCP TIME '.TR_48.' '.$USER, '1');
                        break;
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* Panel Core command */
            if (isset($rawcmd[1]) && HasOwner($mask) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'panel') {
                if (OnEmptyArg('panel <help> to list commands')) {
                } else {
                    switch ($GLOBALS['args']) {
                        case 'help':
                              NICK_MSG('Panel commands:');
                              NICK_MSG('start <port> - Start panel with specified port: '
                             .$GLOBALS['CONFIG_CMD_PREFIX'].'panel start <eg. 3131>');
                              NICK_MSG('stop         - Stop panel: '.$GLOBALS['CONFIG_CMD_PREFIX'].'panel stop');
                            break;
                    }
                    switch ($GLOBALS['piece1']) {
                        case 'start':
                            if (!isset($GLOBALS['OS_TYPE'])) {
                                $port = $GLOBALS['piece2'];
                                if (!empty($port)) {
                                    chdir('panel/');
                                    $commandString = 'start /b serv.exe --http-host=0.0.0.0 --http-port='.
                                        $port.' --no-https';
                                    pclose(popen($commandString, 'r'));
                                    chdir('../');
                                    BOT_RESPONSE('Runned.');
                                    CLI_MSG('[BOT] Panel Runned at port: '.$port, '1');
                                } else {
                                         BOT_RESPONSE('I need port to run server!');
                                }
                            } else {
                                     BOT_RESPONSE('This plugin works on windows only at this time.');
                            }
                            break;
                        case 'stop':
                              exec('taskkill /IM serv.exe /F');
                              BOT_RESPONSE('Panel Closed');
                              CLI_MSG('[BOT] Panel Closed', '1');
                            break;
                    }
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* Load Core command */
            if (isset($rawcmd[1]) && HasOwner($mask) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'load') {
                if (empty($GLOBALS['args'])) {
                    BOT_RESPONSE(TR_46.' '.$GLOBALS['CONFIG_CMD_PREFIX'].'load <'.TR_45.'>');
                } else {
                    if (!empty($GLOBALS['piece1'])) {
                        LoadPlugin($GLOBALS['piece1']);
                    }
                }
            }
//---------------------------------------------------------------------------------------------------------
           /* Unload Core command */
            if (isset($rawcmd[1]) && HasOwner($mask) && $rawcmd[1] == $GLOBALS['CONFIG_CMD_PREFIX'].'unload') {
                if (empty($GLOBALS['args'])) {
                    BOT_RESPONSE(TR_46.' '.$GLOBALS['CONFIG_CMD_PREFIX'].'unload <'.TR_45.'>');
                } else {
                    if (!empty($GLOBALS['piece1'])) {
                         UnloadPlugin($GLOBALS['piece1']);
                    }
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* register 'password' Core command */
            if (isset($rawcmd[1]) && $rawcmd[1] == 'register') {
                on_register_to_bot();
            }
//---------------------------------------------------------------------------------------------------------
            /* plugins commands */
            if (HasOwner($mask) && isset($rawcmd[1])) {
                $pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);

                if (in_array($rawcmd[1], $GLOBALS['OWNER_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }

                if (in_array($rawcmd[1], $GLOBALS['USER_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }
            } elseif (!HasOwner($mask) && isset($rawcmd[1])) {
                $pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);
                if (in_array($rawcmd[1], $GLOBALS['USER_PLUGINS'])) {
                    call_user_func('plugin_'.$pn);
                }
            }

            if (!function_exists('plugin_')) {
                function plugin_()
                {
                }
            }
//---------------------------------------------------------------------------------------------------------
            /* keep nick - check every 60 sec */
            if ($GLOBALS['CONFIG_KEEP_NICK']=='yes' && isset($I_USE_RND_NICKNAME)) {
                if (time()-$GLOBALS['first_time'] > 60) {
                    fputs($GLOBALS['socket'], "ISON :".$GLOBALS['NICKNAME_FROM_CONFIG']."\n");
                    $GLOBALS['first_time'] = time();
                }
                if ($ex[1] == '303' && $ex[3] == ':') {
                    fputs($GLOBALS['socket'], "NICK ".$GLOBALS['NICKNAME_FROM_CONFIG']."\n");
                    $BOT_NICKNAME = $GLOBALS['NICKNAME_FROM_CONFIG'];
                    unset($I_USE_RND_NICKNAME);
                    CLI_MSG('[BOT]: '.TR_37, '1');
                    /* wcli extension */
                    wcliExt();
                }
            }
//---------------------------------------------------------------------------------------------------------
        }
        exit;
    }
}
//---------------------------------------------------------------------------------------------------------
function UnloadPlugin($plugin)
{
    try {
           $with_prefix = $GLOBALS['CONFIG_CMD_PREFIX'].$plugin;
           $without_prefix = $plugin;

        if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            if (($key = array_search($with_prefix, $GLOBALS['OWNER_PLUGINS'])) !== false) {
                unset($GLOBALS['OWNER_PLUGINS'][$key]);
                //todo rename function
                if (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS'])) {
                    CLI_MSG('[Plugin]: \''.$without_prefix.'\' '.TR_39, '1');
                    BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' '.TR_39);
                }
            }
            if (($key = array_search($with_prefix, $GLOBALS['USER_PLUGINS'])) !== false) {
                unset($GLOBALS['USER_PLUGINS'][$key]);
                //todo rename function
                if (!in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
                    CLI_MSG('[Plugin]: \''.$without_prefix.'\' '.TR_39, '1');
                    BOT_RESPONSE(TR_40.' \''.$without_prefix.'\' '.TR_39);
                }
            }
        } else {
                  CLI_MSG('[PLUGIN]: '.TR_42, '1');
                  BOT_RESPONSE(TR_42);
        }
    } catch (Exception $e) {
                              BOT_RESPONSE(TR_49.' '.__FUNCTION__.' '.TR_50);
                              CLI_MSG('[ERROR]: '.TR_49.' '.__FUNCTION__.' '.TR_50, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function LoadPlugin($plugin)
{
    try {
           $with_prefix    = $GLOBALS['CONFIG_CMD_PREFIX'].$plugin;
           $without_prefix = $plugin;

        if (in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) || in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            BOT_RESPONSE(TR_41);

          /* if there is no plugin name in plugins array */
        } elseif (!in_array($with_prefix, $GLOBALS['OWNER_PLUGINS']) ||
            !in_array($with_prefix, $GLOBALS['USER_PLUGINS'])) {
            /* if no plugin in array & file exists in dir */
            if (file_exists('PLUGINS/OWNER/'.$without_prefix.'.php')) {
                /* include that file */
                include_once('PLUGINS/OWNER/'.$without_prefix.'.php');

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['OWNER_PLUGINS'], $with_prefix);
 
                /* bot responses */
                BOT_RESPONSE(TR_40.' \''.$without_prefix.'\''.TR_38);
                CLI_MSG('[PLUGIN]: '.TR_61.' '.$without_prefix.', '.TR_48.' '.$GLOBALS['USER'], '1');
            }

            /* if no plugin in array & file exists in dir */
            if (file_exists('PLUGINS/USER/'.$without_prefix.'.php')) {
                /* include that file */
                include_once('PLUGINS/USER/'.$without_prefix.'.php');

                /* add prefix & plugin name to plugins array */
                array_push($GLOBALS['USER_PLUGINS'], $with_prefix);

                /* bot responses */
                BOT_RESPONSE(TR_40.' \''.$without_prefix.'\''.TR_38);
                CLI_MSG('[PLUGIN]: '.TR_61.' '.$without_prefix.', '.TR_48.' '.$GLOBALS['USER'], '1');
            }
        }
    } catch (Exception $e) {
                             BOT_RESPONSE(TR_49.' '.__FUNCTION__.' '.TR_50);
                             CLI_MSG('[ERROR]: '.TR_49.' '.__FUNCTION__.' '.TR_50, '1');
    }
}
//---------------------------------------------------------------------------------------------------------
function set_channel_modes()
{
    $sleep = '2';
    
    fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel']."\n");
    
    if (BotOpped() == true) {
        if (isset($GLOBALS['CHANNEL_MODES']) && $GLOBALS['CHANNEL_MODES'] != $GLOBALS['CONFIG_CHANNEL_MODES']) {
            sleep(1);
            fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' -'.$GLOBALS['CHANNEL_MODES']."\n");
            sleep(1);
            fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +'.$GLOBALS['CONFIG_CHANNEL_MODES']."\n");
        }
        if (empty($GLOBALS['CHANNEL_MODES'])) {
            if (!empty($GLOBALS['CONFIG_CHANNEL_MODES'])) {
                sleep(1);
                fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['channel'].' +'.$GLOBALS['CONFIG_CHANNEL_MODES']."\n");
            }
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function wcliExt()
{
    if (extension_loaded('wcli')) {
        if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
            wcli_set_console_title('MINION '.VER.' ('.TR_51.' '.$GLOBALS['CONFIG_SERVER'].':'
            .$GLOBALS['CONFIG_PORT'].' | '.TR_52.' '.$GLOBALS['BOT_NICKNAME'].' | '.TR_53.' '
            .$GLOBALS['CONFIG_CNANNEL'].')');
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function parse_ex3()
{
    $a = $GLOBALS['ex'];
    $current = '';
    $index = 3;
    
    while (isset($a[$index])) {
           $current .= $a[$index].' ';
           $index++;
    }
    $b = preg_replace('/^:/', '', $current, 1);
    return $b;
}
//---------------------------------------------------------------------------------------------------------
function msg_without_command()
{
    $input = null;
    for ($i=3; $i <= (count($GLOBALS['ex'])); $i++) {
         $input .= $GLOBALS['ex'][$i]." ";
    }
      
    $in = rtrim($input);
    $data = str_replace($GLOBALS['rawcmd'][1].' ', '', $in);

    return $data;
}
//---------------------------------------------------------------------------------------------------------
function HasAccess($mask)
{
    global $admins;

    if ($mask == null) {
    }
    if ($mask == null) {
        return false;
    }
    foreach ($admins as $admin) {
        if (fnmatch($admin, $mask, 16)) {
            return true;
            return false;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function HasOwner($mask)
{
    global $owners;

    $owners_c = $GLOBALS['CONFIG_OWNERS'];
    $pieces = explode(", ", $owners_c);
    $owners = $pieces;

    if ($mask == null) {
    }
    if ($mask == null) {
        return false;
    }
    foreach ($owners as $owner) {
        if (fnmatch($owner, $mask, 16)) {
            return true;
            return false;
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function SaveToFile($f1, $f2, $f3)
{
    $file = $f1;
    $data = $f2;
    $f=fopen($file, $f3);
    flock($f, 2);
    fwrite($f, $data);
    flock($f, 3);
    fclose($f);
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
function CLI_MSG($msg, $log)
{
    if ($GLOBALS['silent_mode'] == 'no' or empty($GLOBALS['silent_mode'])) {
        $line='['.@date('H:i:s').'] '.$msg.PHP_EOL;

        if (isset($GLOBALS['CONFIG_LOGGING']) && $GLOBALS['CONFIG_LOGGING'] == 'yes') {
            if ($log=='1') {
                SaveToFile($GLOBALS['log_file'], $line, 'a');
            }
        }
        echo $line;
    }
}
//---------------------------------------------------------------------------------------------------------
function BOT_RESPONSE($msg)
{
    switch ($GLOBALS['CONFIG_BOT_RESPONSE']) {
        case 'channel':
            fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['channel']." :$msg\n");
            usleep($GLOBALS['CONFIG_CHANNEL_DELAY'] * 1000000);
            break;

        case 'notice':
            fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['USER']." :$msg\n");
            usleep($GLOBALS['CONFIG_NOTICE_DELAY'] * 1000000);
            break;

        case 'priv':
            fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['USER']." :$msg\n");
            usleep($GLOBALS['CONFIG_PRIVATE_DELAY'] * 1000000);
            break;
    }
}
//---------------------------------------------------------------------------------------------------------
function NICK_MSG($msg)
{
    fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['USER']." :$msg\n");
    usleep($GLOBALS['CONFIG_PRIVATE_DELAY'] * 1000000);
}
//---------------------------------------------------------------------------------------------------------
function JOIN_CHANNEL($channel)
{
    fputs($GLOBALS['socket'], 'JOIN '.$channel."\n");
}
//---------------------------------------------------------------------------------------------------------
function ErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    switch ($errno) {
        case E_USER_ERROR:
            CLI_MSG("[ERROR]: [$errno] $errstr", '1');
            CLI_MSG(TR_54." $errline ".TR_55." $errfile, PHP".PHP_VERSION." (".PHP_OS.")", '1');
            CLI_MSG(TR_56, '1');
            exit(1);
            break;

        case E_USER_WARNING:
            CLI_MSG("[WARNING]: [$errno] $errstr", '1');
            break;

        case E_USER_NOTICE:
            CLI_MSG("[NOTICE]: [$errno] $errstr", '1');
            break;

        default:
            CLI_MSG("[UNKOWN]: error type [$errno] $errstr", '1');
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
//---------------------------------------------------------------------------------------------------------
function DEBUG($where)
{
    if (isset($where)) {
        echo PHP_EOL.PHP_EOL.'[DEBUG] WHERE: $where'.PHP_EOL;
    } else {
              echo PHP_EOL.PHP_EOL.'[DEBUG] ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][0])) {
        echo "[DEBUG] EX0: ".$GLOBALS['ex'][0].PHP_EOL;
    } else {
              echo '[DEBUG] EX0: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][1])) {
        echo '[DEBUG] EX1: '.$GLOBALS['ex'][1].PHP_EOL;
    } else {
              echo '[DEBUG] EX1: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][2])) {
        echo '[DEBUG] EX2: '.$GLOBALS['ex'][2].PHP_EOL;
    } else {
              echo '[DEBUG] EX2: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][3])) {
        echo '[DEBUG] EX3: '.$GLOBALS['ex'][3].PHP_EOL;
    } else {
              echo '[DEBUG] EX3: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][4])) {
        echo '[DEBUG] EX4: '.$GLOBALS['ex'][4].PHP_EOL;
    } else {
              echo '[DEBUG] EX4: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][5])) {
        echo '[DEBUG] EX5: '.$GLOBALS['ex'][5].PHP_EOL;
    } else {
              echo '[DEBUG] EX5: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['ex'][6])) {
        echo '[DEBUG] EX6: '.$GLOBALS['ex'][6].PHP_EOL;
    } else {
              echo '[DEBUG] EX6: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['channel'])) {
        echo '[DEBUG] CHANNEL: '.$GLOBALS['channel'].PHP_EOL;
    } else {
              echo '[DEBUG] CHANNEL: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['BOT_NICKNAME'])) {
        echo '[DEBUG] NICKNAME: '.$GLOBALS['BOT_NICKNAME'].PHP_EOL;
    } else {
              echo '[DEBUG] NICKNAME: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['BOT_OPPED'])) {
        echo '[DEBUG] BOT_OPPED: '.$GLOBALS['BOT_OPPED'].PHP_EOL;
    } else {
              echo '[DEBUG] BOT_OPPED: ---'.PHP_EOL;
    }
    if (isset($GLOBALS['CHANNEL_MODES'])) {
        echo '[DEBUG] CHANNEL_MODES: '.$GLOBALS['CHANNEL_MODES'].PHP_EOL.PHP_EOL;
    } else {
              echo '[DEBUG] CHANNEL_MODES: ---'.PHP_EOL.PHP_EOL;
    }
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
//---------------------------------------------------------------------------------------------------------
function CountLines($exts = array('php'))
{
    $fpath = '../';
    $files=array();

    $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fpath));
    foreach ($it as $file) {
        if ($file->isDir()) {
            continue;
        }
           $parts = explode('.', $file->getFilename());
           $extension=end($parts);
        if (in_array($extension, $exts)) {
            $files[$file->getPathname()]=count(file($file->getPathname()));
        }
    }
    return $files;
}
//---------------------------------------------------------------------------------------------------------
function TotalLines()
{
    return array_sum(CountLines());
}
//---------------------------------------------------------------------------------------------------------
