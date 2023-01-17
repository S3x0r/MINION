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

function CreateDefaultDataConfigFile()
{
    /* default config */
    $configData =
"<?php exit; ?>

[BOT]

; bot nickname
nickname         = 'minion'

; bot name
name             = 'http://github.com/S3x0r/MINION'

; bot ident
ident            = 'minion'

[SERVER]

; irc server
server           = 'irc.pirc.pl'

; irc server port
port             = '6667'

; If the irc server requires a password to connect, set it here
server.password  = ''

; Try to connect to the server the amount set here, if after a certain amount the bot fails to connect then terminate the program
try.connect      = '99'

; Pause (seconds) between connections to the server
connect.delay    = '6'

; show server message of the day
show.motd        = 'no'

[OWNER]

; bot administrator information
bot.admin        = 'minion <user@localhost>'

; owner bot password (SHA256)
owner.password   = '47a8f9b32ec41bd93d79bf6c1c924aaecaa26d9afe88c39fc3a638f420f251ed'

[PRIVILEGES]

; BOT OWNER HOSTS <nick!ident@hostname>
OWNER = ''
; other users hosts
ADMIN = ''
; other users hosts
USER = ''

[USERSLEVELS]

;users access levels
; Owner = 0
; Normal User = 999
; others from 1 to 998
OWNER  = '0'
ADMIN  = '1'
USER   = '999'

[RESPONSE]

; where bot should response, you can choose from: channel, notice, priv
bot.response     = 'notice'

[AUTOMATIC]

; bot will give op when join to channel from auto.op.list: 'yes', 'no'
auto.op          = 'yes'

; bot will give op when hosts join channel <nick!ident@hostname>
auto.op.list     = ''

; bot will auto rejoin channel when kicked: 'yes', 'no'
auto.rejoin      = 'yes'

; do we want to keep channel modes from channel.modes option?
keep.chan.modes  = 'yes'

; this setting makes the bot try to get his original nickname back if its primary nickname is already in use
keep.nick        = 'yes'

[CHANNEL]

; channel where to join when connected
channel          = '#minion'

; auto join channel when connected: 'yes', 'no'
auto.join        = 'yes'

; set channel modes on bot join
channel.modes   = 'nt'

; channel key if exists
channel.key      = ''

[BANS]

; ban users from this list
ban.list         = 'nick!ident@hostname, *!ident@hostname, *!*@onlyhost'

[COMMAND]

; bot commands prefix eg. !info, you can change to what you want
command.prefix   = '!'

[CTCP]

; response to ctcp requests? 'yes', 'no'
ctcp.response    = 'yes'

; ctcp version response (please do not change it:)
ctcp.version     = 'minion (".VER.") powered by minions!'

; ctcf finger response
ctcp.finger      = 'minion'

[DELAYS]

; bot response delay on channel (in seconds)
channel.delay   = '1.5'

; bot response delay on private messages (in seconds)
private.delay   = '1'

; bot response delay on notice messages (in seconds)
notice.delay    = '1'

[LOGS]

; log CLI messages to LOGS folder? 'yes', 'no'
logging          = 'yes'

[TIME]

; bot time zone
time.zone        = 'Europe/Warsaw'

[FETCH]

; bot plugin repository address
fetch.server     = 'https://raw.githubusercontent.com/S3x0r/minion_repository_plugins/master'

[PROGRAM]

; if we want to play sounds?: 'yes', 'no'
play.sounds      = 'yes'

[DEBUG]

; show raw output on CLI window
show.raw         = 'no'
show.own.messages.in.raw.mode = 'no'
show.debug       = 'no'
";

    /* Save default config to file if no config */
    SaveToFile(getConfigFileName(), $configData, 'w');
}
//---------------------------------------------------------------------------------------------------------
function isConfigProvidedFromArgument()
{   
    if (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == '-c' && !empty($_SERVER['argv'][2]) && is_file($_SERVER['argv'][2])) {
        return true;
    } else {
             return false;
    }
}
//---------------------------------------------------------------------------------------------------------
function getConfigFileName()
{
    if (isConfigProvidedFromArgument()) {
        return $_SERVER['argv'][2];
    } else {
             return CONFIGFILE;
    }
}
//---------------------------------------------------------------------------------------------------------
function loadValueFromConfigFile($section, $value)
{
    $cfg = new IniParser(getConfigFileName());
    return $cfg->get("$section", "$value");
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
            fwrite($desc, "<?php exit; ?>\r\n");
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
