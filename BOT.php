<?php

/*
  
   v0.0.5 changes:
   - moved source to GitHub
   - more in English
   - changed config system to ini file style
   - moved admin hosts to config file
   - moved show_raw to config file
   - added changing nick when reserved by server
   - added readme.txt file
   - some cosmetic changes
   - fixed uptime plugin and other changes in plugins

   v0.0.4 changes:
   - php interpreter have now less MB
   - comments looks more natural
   - mail added in info
   - more functions/var are now in English
   - addded time in messages
   - cleaned some code: globals, etc.
   - !listadmins /New command added


TODO:
  !AddAdmin <user@host>
  !identify <passwd>
  RAW <command>
  fix loop
  fix !reconnect, !quit / quit msg

*/

set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);

define('VER', 'v0.0.5');
//------------------------------------------------------------------------------------------------
Start();
LoadConfig();
LoadPlugins();
Connect();
//------------------------------------------------------------------------------------------------
function Start()
{
$GLOBALS['StartTime'] = time();
echo "
    __                      __           __   
.--|  |.---.-.--.--.--.--. |  |--.-----.|  |_ 
|  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|
|_____||___._|\___/|___  | |_____|_____||____| ".VER."
                   |_____|                    
				   (olisek@gmail.com)
\n\n";
}
//------------------------------------------------------------------------------------------------
function LoadConfig()
{
$GLOBALS['cfg'] = new iniParser("../CONFIG/CONFIG.INI");

$GLOBALS['nickname']			= $GLOBALS['cfg']->get("Configuration","nickname");
$GLOBALS['alternative_nick']	= $GLOBALS['cfg']->get("Configuration","alternative_nick");
$GLOBALS['name']				= $GLOBALS['cfg']->get("Configuration","name");	
$GLOBALS['ident']				= $GLOBALS['cfg']->get("Configuration","ident");			
$GLOBALS['server']				= $GLOBALS['cfg']->get("Configuration","server");			
$GLOBALS['port']				= $GLOBALS['cfg']->get("Configuration","port");			
$GLOBALS['channel']				= $GLOBALS['cfg']->get("Configuration","channel");	
$GLOBALS['admin1']				= $GLOBALS['cfg']->get("Admins","admin1");		
$GLOBALS['show_raw']			= $GLOBALS['cfg']->get("Debug","show_raw");

MSG("1. Configuration Loaded from: CONFIG.INI\n");
}
//------------------------------------------------------------------------------------------------
function LoadPlugins()
{
	
MSG("2. My Plugins:\n");
 foreach ( glob( '../PLUGINS/*.php' ) as $plugin_name )
 {
  include_once($plugin_name);
  $plugin_name = basename($plugin_name, '.php');
  echo "$plugin_name - $plugin_description\n";
 }
echo "--------------------------------------------------------\n";
}
//------------------------------------------------------------------------------------------------
function Connect()
{
$GLOBALS['socket'] = fsockopen($GLOBALS['server'], $GLOBALS['port']);
MSG('3. Connecting to: '.$GLOBALS['server'].', port: '.$GLOBALS['port']);
fputs($GLOBALS['socket'], 'USER '.$GLOBALS['nickname'].' FORCE '.$GLOBALS['ident'].' :'.$GLOBALS['name']."\n");
fputs($GLOBALS['socket'], 'NICK ' . $GLOBALS['nickname'] . "\n");
MSG('4. My nickname is: '.$GLOBALS['nickname']);
}
//------------------------------------------------------------------------------------------------
while(1) {
	while($data = fgets($GLOBALS['socket'])) {
      if($GLOBALS['show_raw'] == 'yes') { echo $data; }
	  flush(); 

       	$ex = explode(' ', $data);
		$rawcmd = explode(':', $ex[3]);
        $chann = $ex[2];
		$nicka = explode('@', $ex[0]);
		$nickb = explode('!', $nicka[0]);
		$nickc = explode(':', $nickb[0]);

		$host = $nicka[1];
		$nick = $nickc[1];

if($ex[1] == '432') { 
	MSG('   ` Nickname reserved, changing to alternative nick: '.$GLOBALS['alternative_nick']);
	fputs($GLOBALS['socket'],'NICK '.$GLOBALS['alternative_nick']."\n");
	}

if($ex[1] == '433') { 
	MSG('   ` Nickname already used, changing to alternative nick: '.$GLOBALS['alternative_nick']);
	fputs($GLOBALS['socket'],'NICK '.$GLOBALS['alternative_nick']."\n");
	}

if($ex[1] == '376') { 
	MSG('5. OK im connected! ]:)');
	MSG('6. Joining channel: '.$GLOBALS['channel']);
	fputs($GLOBALS['socket'],'JOIN '.$GLOBALS['channel']."\n");
	}

if($ex[0] == 'PING'){
	fputs($GLOBALS['socket'], 'PONG '.$ex[1]."\n");
    }

$GLOBALS['args'] = NULL; for ($i = 4; $i < count($ex); $i++) { $GLOBALS['args'] .= $ex[$i]; }

$userhost = $nickb[1] .'@'. $nicka[1];

if($userhost == $GLOBALS['admin1']) {

if ($rawcmd[1] == '!uptime')      {		if(function_exists('uptime'))		{ uptime(); } }

if ($rawcmd[1] == '!md5')	      {		if(function_exists('emd5'))			{ emd5(); } }
if ($rawcmd[1] == '!info')	      {		if(function_exists('info'))			{ info(); } }

if ($rawcmd[1] == '!op')	      {		if(function_exists('op'))			{ op(); } }
if ($rawcmd[1] == '!deop')	      {		if(function_exists('deop'))			{ deop(); } }

if ($rawcmd[1] == '!join')	      {		if(function_exists('joinc'))		{ joinc(); } }
if ($rawcmd[1] == '!j')		      {		if(function_exists('joinc'))		{ joinc(); } }

if ($rawcmd[1] == '!leave')	      {		if(function_exists('leave'))		{ leave(); } }
if ($rawcmd[1] == '!part')        {		if(function_exists('leave'))		{ leave(); } }

if ($rawcmd[1] == '!quit')	      {		if(function_exists('quit'))			{ quit(); } }
if ($rawcmd[1] == '!die')	      {		if(function_exists('quit'))			{ quit(); } }

if ($rawcmd[1] == '!topic')	      {		if(function_exists('topic'))		{ topic(); } }

if ($rawcmd[1] == '!cham')	      {		if(function_exists('cham'))			{ cham(); } }

if ($rawcmd[1] == '!newnick')     {		if(function_exists('newnick'))		{ newnick(); } }

if ($rawcmd[1] == '!commands')    {		if(function_exists('commands'))		{ commands(); } }

if ($rawcmd[1] == '!showconfig')  {		if(function_exists('showconfig'))	{ showconfig(); } }

if ($rawcmd[1] == '!savenick')	  {		if(function_exists('savenick'))		{ savenick(); } }
if ($rawcmd[1] == '!savealtnick') {		if(function_exists('savealtnick'))	{ savealtnick(); } }
if ($rawcmd[1] == '!saveident')   {		if(function_exists('saveident'))	{ saveident(); } }
if ($rawcmd[1] == '!savename')    {		if(function_exists('savename'))		{ savename(); } }
if ($rawcmd[1] == '!saveport')    {		if(function_exists('saveport'))		{ saveport(); } }
if ($rawcmd[1] == '!saveserver')  {		if(function_exists('saveserver'))	{ saveserver(); } }
if ($rawcmd[1] == '!savechannel') {		if(function_exists('savechannel'))	{ savechannel(); } }

if ($rawcmd[1] == '!reconnect')	  {		if(function_exists('reconnect'))	{ reconnect(); } }

if ($rawcmd[1] == '!listadmins')  {		if(function_exists('listadmins'))	{ listadmins(); } }
  }
 }
}
//------------------------------------------------------------------------------------------------
function MSG($msg)
{
 $line = '[' . @date( 'H:i:s' ) . '] ' . $msg . "\r\n";
 echo $line;
}
//------------------------------------------------------------------------------------------------
class iniParser {
	
	var $_iniFilename = '';
	var $_iniParsedArray = array();
	
	function iniParser($filename)
	{
		$this->_iniFilename = $filename;
		if($this->_iniParsedArray = parse_ini_file($filename, true) ) {
			return true;
		} else {
			return false;
		} 
	}
	function getSection($key)
	{
		return $this->_iniParsedArray[$key];
	}
	function getValue($section, $key)
	{
		if(!isset($this->_iniParsedArray[$section])) return false;
		return $this->_iniParsedArray[$section][$key];
	}
	function get($section, $key=NULL)
	{
		if(is_null($key)) return $this->getSection($section);
		return $this->getValue($section, $key);
	}
	function setSection($section, $array)
	{
		if(!is_array($array)) return false;
		return $this->_iniParsedArray[$section] = $array;
	}
	function setValue($section, $key, $value)
	{
		if($this->_iniParsedArray[$section][$key] = $value) return true;
	}
	function set($section, $key, $value=NULL)
	{
		if(is_array($key) && is_null($value)) return $this->setSection($section, $key);
		return $this->setValue($section, $key, $value);
	}
	function save( $filename = null )
	{
		if($filename == null) $filename = $this->_iniFilename;
		if(is_writeable($filename) ) {
			$SFfdescriptor = fopen($filename, "w");
			foreach($this->_iniParsedArray as $section => $array){
				fwrite($SFfdescriptor, "[" . $section . "]\n" );
				foreach($array as $key => $value) {
					fwrite( $SFfdescriptor, "$key = '$value'\n" );
				}
				fwrite($SFfdescriptor, "\n");
			}
			fclose($SFfdescriptor);
			return true;
		} else {
			return false;
		}
	}
}

?>