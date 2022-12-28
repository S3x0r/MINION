<?php

function GetDataFromBotConfig()
{
    $cfg = new IniParser('../web.ini');
    $GLOBALS['WEB_BOT_CONFIG_FILE'] = $cfg->get("MAIN", "WEB_BOT_CONFIG_FILE");

    $cfg = new IniParser('../../'.$GLOBALS['WEB_BOT_CONFIG_FILE']);
        /* BOT */
        $GLOBALS['CONFIG.NICKNAME']       = $cfg->get("BOT", "nickname");
        $GLOBALS['CONFIG.NAME']           = $cfg->get("BOT", "name");
        $GLOBALS['CONFIG.IDENT']          = $cfg->get("BOT", "ident");
        /* SERVER */
        $GLOBALS['CONFIG.SERVER']         = $cfg->get("SERVER", "server");
        $GLOBALS['CONFIG.PORT']           = $cfg->get("SERVER", "port");
        $GLOBALS['CONFIG.SERVER.PASSWD']  = $cfg->get("SERVER", "server.password");
        $GLOBALS['CONFIG.TRY.CONNECT']    = $cfg->get("SERVER", "try.connect");
        $GLOBALS['CONFIG.CONNECT.DELAY']  = $cfg->get("SERVER", "connect.delay");
        /* OWNER */
        $GLOBALS['CONFIG.BOT.ADMIN']      = $cfg->get("OWNER", "bot.admin");
        $GLOBALS['CONFIG.AUTO.OP.LIST']   = $cfg->get("OWNER", "auto.op.list");
        $GLOBALS['CONFIG.OWNERS']         = $cfg->get("OWNER", "bot.owners");
        $GLOBALS['CONFIG.OWNER.PASSWD']   = $cfg->get("OWNER", "owner.password");
        /* BOT RESPONSE */
        $GLOBALS['CONFIG.BOT.RESPONSE']   = $cfg->get("RESPONSE", "bot.response");
        /* AUTOMATIC */
        $GLOBALS['CONFIG.AUTO.OP']        = $cfg->get("AUTOMATIC", "auto.op");
        $GLOBALS['CONFIG.AUTO.REJOIN']    = $cfg->get("AUTOMATIC", "auto.rejoin");
        $GLOBALS['CONFIG.KEEP.NICK']      = $cfg->get("AUTOMATIC", "keep.nick");
        /* CHANNEL */
        $GLOBALS['CONFIG.CHANNEL']        = $cfg->get("CHANNEL", "channel");
        $GLOBALS['CONFIG.AUTO.JOIN']      = $cfg->get("CHANNEL", "auto.join");
        $GLOBALS['CONFIG.CHANNEL.MODES']  = $cfg->get("CHANNEL", "channel.modes");
        $GLOBALS['CONFIG.CHANNEL.KEY']    = $cfg->get("CHANNEL", "channel.key");
        /* COMMAND PREFIX */
        $GLOBALS['CONFIG.CMD.PREFIX']     = $cfg->get("COMMAND", "command.prefix");
        /* CTCP */
        $GLOBALS['CONFIG.CTCP.RESPONSE']  = $cfg->get("CTCP", "ctcp.response");
        $GLOBALS['CONFIG.CTCP.VERSION']   = $cfg->get("CTCP", "ctcp.version");
        $GLOBALS['CONFIG.CTCP.FINGER']    = $cfg->get("CTCP", "ctcp.finger");
        /* DELAYS */
        $GLOBALS['CONFIG.CHANNEL.DELAY']  = $cfg->get("DELAYS", "channel.delay");
        $GLOBALS['CONFIG.PRIVATE.DELAY']  = $cfg->get("DELAYS", "private.delay");
        $GLOBALS['CONFIG.NOTICE.DELAY']   = $cfg->get("DELAYS", "notice.delay");
        /* LOGGING */
        $GLOBALS['CONFIG.LOGGING']        = $cfg->get("LOGS", "logging");
        /* TIMEZONE */
        $GLOBALS['CONFIG.TIMEZONE']       = $cfg->get("TIME", "time.zone");
        /* FETCH */
        $GLOBALS['CONFIG.FETCH.SERVER']   = $cfg->get("FETCH", "fetch.server");
        /* PROGRAM */
        /* DEBUG */
        $GLOBALS['CONFIG.SHOW.RAW']       = $cfg->get("DEBUG", "show.raw");
}
//-------------------------------------------------------------------------------------------------------
function GetAllData()
{
    $cfg = new IniParser('../web.ini');
    $GLOBALS['WEB_VERSION']      = $cfg->get("MAIN", "WEB_VERSION");
    $GLOBALS['WEB_PHP_VERSION']  = $cfg->get("MAIN", "WEB_PHP_VERSION");
    $GLOBALS['WEB_START_TIME']   = $cfg->get("MAIN", "WEB_START_TIME");
}
//-------------------------------------------------------------------------------------------------------
function uptime_parse($seconds)
{
    $weeks = (floor($seconds / (60 * 60) / 24)) / 7;
    $days = (floor($seconds / (60 * 60) / 24)) % 7;
    $hours = (floor($seconds / (60 * 60))) % 24;

    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);

    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);

    $result = "";
    if (!empty($weeks) && $days > 0) {
        $result .= $weeks . " week";
    }
    if ($weeks > 1) {
        $result .= "s";
    }
    if (!empty($days) && $days > 0) {
        $result .= $days . " day";
    }
    if ($days > 1) {
        $result .= "s";
    }
    if (!empty($hours) && $hours > 0) {
        $result .= $hours . " hour";
    }
    if ($hours > 1) {
        $result .= "s";
    }
    if (!empty($minutes) && $minutes > 0) {
        $result .= " " . $minutes . " minute";
    }
    if ($minutes > 1) {
        $result .= "s";
    }
    if (!empty($seconds) && $seconds > 0) {
        $result .= " " . $seconds . " second";
    }
    if ($seconds > 1) {
        $result .= "s";
    }

    return trim($result);
}
//-------------------------------------------------------------------------------------------------------
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
//-------------------------------------------------------------------------------------------------------
function ListPlugins()
{
        /* count plugins */
        $count1 = count(glob("../../../".PLUGINSDIR."/OWNER/*.php", GLOB_BRACE));
        $count2 = count(glob("../../../".PLUGINSDIR."/ADMIN/*.php", GLOB_BRACE));
        $count3 = count(glob("../../../".PLUGINSDIR."/USER/*.php", GLOB_BRACE));

        $tot = $count1+$count2+$count3+6;
        
        echo "<h4>All Plugins: $tot</h4><br>";
    
        /* CORE COMMANDS */
        echo 'Core Commands (6):<br>';
        echo '- load<br>';
        echo '- panel<br>';
        echo '- pause<br>';
        echo '- seen<br>';
        echo '- unload<br>';
        echo '- unpause<br>';
        echo '<br>';

        /* OWNERS PLUGINS */
        echo "Owner Plugins ($count1):<br>";

    foreach (glob('../../../'.PLUGINSDIR.'/OWNER/*.php') as $plugin_name) {
        $plugin_name = basename($plugin_name, '.php');
        echo "- $plugin_name<br>";
    }
        echo '<br>';

        /* ADMIN PLUGINS */
        echo "Admin Plugins ($count2):<br>";

    foreach (glob('../../../'.PLUGINSDIR.'/ADMIN/*.php') as $plugin_name) {
        $plugin_name = basename($plugin_name, '.php');
        echo "- $plugin_name<br>";
    }
        echo '<br>';

        /* USER PLUGINS */
        echo "User Plugins ($count3):<br>";

    foreach (glob('../../../'.PLUGINSDIR.'/USER/*.php') as $plugin_name) {
        $plugin_name = basename($plugin_name, '.php');
        echo "- $plugin_name<br>";
    }
}
