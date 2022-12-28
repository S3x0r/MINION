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

function LoadConfig()
{
    $cfg = new IniParser($GLOBALS['configFile']);

    /* load configuration */

    /* BOT */
    $GLOBALS['CONFIG.NICKNAME']       = $cfg->get('BOT', 'nickname');
    $GLOBALS['CONFIG.NAME']           = $cfg->get('BOT', 'name');
    $GLOBALS['CONFIG.IDENT']          = $cfg->get('BOT', 'ident');

    /* SERVER */
    !isset($GLOBALS['CONFIG.SERVER']) ? $GLOBALS['CONFIG.SERVER'] = $cfg->get('SERVER', 'server') : false;
    !isset($GLOBALS['CONFIG.PORT'])   ? $GLOBALS['CONFIG.PORT']   = $cfg->get('SERVER', 'port')   : false;
    
    $GLOBALS['CONFIG.SERVER.PASSWD']  = $cfg->get('SERVER', 'server.password');
    $GLOBALS['CONFIG.TRY.CONNECT']    = $cfg->get('SERVER', 'try.connect');
    $GLOBALS['CONFIG.CONNECT.DELAY']  = $cfg->get('SERVER', 'connect.delay');
    $GLOBALS['CONFIG.SHOW.MOTD']      = $cfg->get('SERVER', 'show.motd');

    /* OWNER */
    $GLOBALS['CONFIG.BOT.ADMIN']      = $cfg->get('OWNER', 'bot.admin');
    $GLOBALS['CONFIG.AUTO.OP.LIST']   = $cfg->get('OWNER', 'auto.op.list');
    $GLOBALS['CONFIG.OWNERS']         = $cfg->get('OWNER', 'bot.owners');
    $GLOBALS['CONFIG.OWNER.PASSWD']   = $cfg->get('OWNER', 'owner.password');

    /* ADMIN */
    $GLOBALS['CONFIG.ADMIN.LIST']     = $cfg->get('ADMIN', 'admin.list');

    /* BOT RESPONSE */
    $GLOBALS['CONFIG.BOT.RESPONSE']   = $cfg->get('RESPONSE', 'bot.response');

    /* AUTOMATIC */
    $GLOBALS['CONFIG.AUTO.OP']        = $cfg->get('AUTOMATIC', 'auto.op');
    $GLOBALS['CONFIG.AUTO.REJOIN']    = $cfg->get('AUTOMATIC', 'auto.rejoin');
    $GLOBALS['CONFIG.KEEPCHAN.MODES'] = $cfg->get('AUTOMATIC', 'keep.chan.modes');
    $GLOBALS['CONFIG.KEEP.NICK']      = $cfg->get('AUTOMATIC', 'keep.nick');

    /* CHANNEL */
    $GLOBALS['CONFIG.CHANNEL']        = $cfg->get('CHANNEL', 'channel');
    $GLOBALS['CONFIG.AUTO.JOIN']      = $cfg->get('CHANNEL', 'auto.join');
    $GLOBALS['CONFIG.CHANNEL.MODES']  = $cfg->get('CHANNEL', 'channel.modes');
    $GLOBALS['CONFIG.CHANNEL.KEY']    = $cfg->get('CHANNEL', 'channel.key');

    /* BANS */
    $GLOBALS['CONFIG.BAN.LIST']       = $cfg->get('BANS', 'ban.list');

    /* COMMAND PREFIX */
    $GLOBALS['CONFIG.CMD.PREFIX']     = $cfg->get('COMMAND', 'command.prefix');

    /* CTCP */
    $GLOBALS['CONFIG.CTCP.RESPONSE']  = $cfg->get('CTCP', 'ctcp.response');
    $GLOBALS['CONFIG.CTCP.VERSION']   = $cfg->get('CTCP', 'ctcp.version');
    $GLOBALS['CONFIG.CTCP.FINGER']    = $cfg->get('CTCP', 'ctcp.finger');

    /* DELAYS */
    $GLOBALS['CONFIG.CHANNEL.DELAY']  = $cfg->get('DELAYS', 'channel.delay');
    $GLOBALS['CONFIG.PRIVATE.DELAY']  = $cfg->get('DELAYS', 'private.delay');
    $GLOBALS['CONFIG.NOTICE.DELAY']   = $cfg->get('DELAYS', 'notice.delay');

    /* LOGGING */
    $GLOBALS['CONFIG.LOGGING']        = $cfg->get('LOGS', 'logging');

    /* PANEL */
    $GLOBALS['CONFIG.WEB.LOGIN']      = $cfg->get('PANEL', 'web.login');
    $GLOBALS['CONFIG.WEB.PASSWORD']   = $cfg->get('PANEL', 'web.password');

    /* TIMEZONE */
    $GLOBALS['CONFIG.TIMEZONE']       = $cfg->get('TIME', 'time.zone');

    /* FETCH */
    $GLOBALS['CONFIG.FETCH.SERVER']   = $cfg->get('FETCH', 'fetch.server');

    /* PROGRAM */
    $GLOBALS['CONFIG.PLAY.SOUNDS']    = $cfg->get('PROGRAM', 'play.sounds');

    /* DEBUG */
    $GLOBALS['CONFIG.SHOW.RAW']             = $cfg->get('DEBUG', 'show.raw');
    $GLOBALS['CONFIG.OWN.MSGS.IN.RAW.MODE'] = $cfg->get('DEBUG', 'show.own.messages.in.raw.mode');

    /* from what file config loaded */
    cliLog("[bot] Configuration Loaded from: {$GLOBALS['configFile']}");
    
    line();
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
server.password  = \'\'

; try connect \'n\' (in seconds) times to server, if cannot then quit
try.connect      = \'99\'

; delay (in seconds) after new connection to server
connect.delay    = \'6\'

; show message of the day
show.motd        = \'yes\'

[OWNER]

; bot administrator information
bot.admin        = \'S3x0r <user@localhost>\'

; bot will give op\'s if this hosts join channel <nick!ident@hostname>
auto.op.list     = \'\'

; BOT OWNER HOSTS <nick!ident@hostname>
bot.owners       = \'\'

; owner password (SHA256)
owner.password   = \'47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed\'

[ADMIN]

; bot admin list <nick!ident@hostname>
admin.list       = \'\'

[RESPONSE]

; where bot should response, you can choose from: channel, notice, priv
bot.response     = \'notice\'

[AUTOMATIC]

; bot will give op when join to channel from auto.op.list: \'yes\', \'no\'
auto.op          = \'yes\'

; bot will auto rejoin channel when kicked: \'yes\', \'no\'
auto.rejoin      = \'yes\'

; do we want to keep channel modes from channel.modes option?
keep.chan.modes  = \'yes\'

; this setting makes the bot try to get his original nickname back if its primary nickname is already in use
keep.nick        = \'yes\'

[CHANNEL]

; channel where to join when connected
channel          = \'#minion\'

; auto join channel when connected: \'yes\', \'no\'
auto.join        = \'yes\'

; set channel modes on bot join
channel.modes   = \'nt\'

; channel key if exists
channel.key      = \'\'

[BANS]

; ban users from this list
ban.list         = \'nick!ident@hostname, *!ident@hostname, *!*@onlyhost\'

[COMMAND]

; bot commands prefix eg. !info, you can change to what you want
command.prefix   = \'!\'

[CTCP]

; response to ctcp requests? \'yes\', \'no\'
ctcp.response    = \'yes\'

; ctcp version response (please do not change it:)
ctcp.version     = \'MINION ('.VER.') powered by minions!\'

; ctcf finger response
ctcp.finger      = \'MINION\'

[DELAYS]

; bot response delay on channel (in seconds)
channel.delay   = \'1.5\'

; bot response delay on private messages (in seconds)
private.delay   = \'1\'

; bot response delay on notice messages (in seconds)
notice.delay    = \'1\'

[LOGS]

; log CLI messages to LOGS folder? \'yes\', \'no\'
logging          = \'yes\'

[PANEL]

; web panel login
web.login        = \'changeme\'

; web panel password
web.password     = \'changeme\'

[TIME]

; bot time zone
time.zone        = \'Europe/Warsaw\'

[FETCH]

; bot plugin repository address
fetch.server     = \'https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master\'

[PROGRAM]

; if we want to play sounds?: \'yes\', \'no\'
play.sounds      = \'yes\'

[DEBUG]

; show raw output on CLI window
show.raw         = \'no\'
show.own.messages.in.raw.mode = \'no\'';

    /* Save default config to file if no config */
    SaveToFile($filename, $defaultConfigData, 'w');

    /* remove variable */
    unset($defaultConfigData);
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
