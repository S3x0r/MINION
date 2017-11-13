<?php

function GetDataFromBotConfig()
{
    $cfg = new IniParser('../web.ini');
    $GLOBALS['WEB_BOT_CONFIG_FILE'] = $cfg->get("MAIN", "WEB_BOT_CONFIG_FILE");

    $cfg = new IniParser('../../../'.$GLOBALS['WEB_BOT_CONFIG_FILE']);
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
        $GLOBALS['CONFIG_BOT_ADMIN']      = $cfg->get("OWNER", "bot_admin");
        $GLOBALS['CONFIG_AUTO_OP_LIST']   = $cfg->get("OWNER", "auto_op_list");
        $GLOBALS['CONFIG_OWNERS']         = $cfg->get("OWNER", "bot_owners");
        $GLOBALS['CONFIG_OWNER_PASSWD']   = $cfg->get("OWNER", "owner_password");
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
}
//-------------------------------------------------------------------------------------------------------
function GetAllData()
{
    $cfg = new IniParser('../web.ini');
    $GLOBALS['WEB_VERSION']      = $cfg->get("MAIN", "WEB_VERSION");
    $GLOBALS['WEB_PHP_VERSION']  = $cfg->get("MAIN", "WEB_PHP_VERSION");
    $GLOBALS['WEB_START_TIME']   = $cfg->get("MAIN", "WEB_START_TIME");
    $GLOBALS['WEB_BOT_CHANNELS'] = $cfg->get("MAIN", "WEB_BOT_CHANNELS");
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
        $count1 = count(glob("../../../PLUGINS/OWNER/*.php", GLOB_BRACE));
        $count2 = count(glob("../../../PLUGINS/USER/*.php", GLOB_BRACE));
        $tot = $count1+$count2+3;
        
        echo "<h4>All Plugins: $tot</h4><br>";
    
        /* CORE COMMANDS */
        echo 'Core Commands (3):<br>';
        echo '- load<br>';
        echo '- panel<br>';
        echo '- unload<br>';
        echo '<br>';

        /* OWNERS PLUGINS */
        echo "Owner Plugins ($count1):<br>";

    foreach (glob('../../../PLUGINS/OWNER/*.php') as $plugin_name) {
        $plugin_name = basename($plugin_name, '.php');
        echo "- $plugin_name<br>";
    }
        echo '<br>';

        /* USER PLUGINS */
        echo "User Plugins ($count2):<br>";

    foreach (glob('../../../PLUGINS/USER/*.php') as $plugin_name) {
        $plugin_name = basename($plugin_name, '.php');
        echo "- $plugin_name<br>";
    }
}
