<?php

/* OWNERS HOSTS - EDIT IT FOR USING BOT COMMANDS!

            nick ! ident@   host
              |      |       |
   example: S3x0r!~S3x0r@85-220-98-249.dsl.dynamic.simnet.is
*/

$GLOBALS['owners'] = Array ('S3x0r!S3x0r@validation.sls.microsoft.com','');
$GLOBALS['admins'] = Array ('' , ''); 
//------------------------------------------------------------------------------------------------
set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);
define('VER', '0.1.1');

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
|_____||___._|\___/|___  | |_____|_____||____| v".VER."
                   |_____|                    
				   (olisek@gmail.com)
\n\n";
}
//------------------------------------------------------------------------------------------------
function LoadConfig()
{
global $cfg; 
  
  if(file_exists('../CONFIG.INI')) {
   
   $cfg = new iniParser("../CONFIG.INI");
 
   $GLOBALS['nickname']			= $cfg->get("Configuration","nickname");
   $GLOBALS['alternative_nick']	= $cfg->get("Configuration","alternative_nick");
   $GLOBALS['name']				= $cfg->get("Configuration","name");	
   $GLOBALS['ident']			= $cfg->get("Configuration","ident");			
   $GLOBALS['server']			= $cfg->get("Configuration","server");			
   $GLOBALS['port']				= $cfg->get("Configuration","port");			
   $GLOBALS['channel']			= $cfg->get("Configuration","channel");	
   $GLOBALS['show_raw']			= $cfg->get("Debug","show_raw");

   MSG("1. Configuration Loaded from: CONFIG.INI\n");
  }
  else {
	  MSG("ERROR: You need configuration file (CONFIG.INI)");
	  sleep(6);
	  die();
  }
}
//------------------------------------------------------------------------------------------------
function LoadPlugins()
{
MSG("2. My Plugins:\n");
 foreach(glob('../PLUGINS/*.php') as $plugin_name)
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
global $socket;
global $nickname;

$socket = fsockopen($GLOBALS['server'], $GLOBALS['port']);

if ($socket===false) {
    echo "Cannot connect to server, probably wrong address or no internet connection.\n";
    die();
}

MSG('3. Connecting to: '.$GLOBALS['server'].', port: '.$GLOBALS['port']);

fputs($socket, 'USER '.$nickname.' FORCE '.$GLOBALS['ident'].' :'.$GLOBALS['name']."\n");
fputs($socket, 'NICK '.$nickname."\n");

MSG('4. My nickname is: '.$nickname);
Engine();
}
//------------------------------------------------------------------------------------------------
function Engine()
{
global $socket;
global $alternative_nick;
global $args;
while(1) {
    while(!feof($socket)) {
        $mask   = NULL;
        $data = fgets ($socket, 512);
        if($GLOBALS['show_raw'] == 'yes') { echo $data; }

        flush();
        $ex = explode(' ', trim($data));
      
		if($ex[0] == "PING") {
            fputs($socket, "PONG ".$ex[1]."\n");
            continue; 
        }
        
		if (preg_match ('/^:(.*)\!(.*)\@(.*)$/', $ex[0], $source)) {
                $nick   = $source[1];
                $ident  = $source[2];
                $host   = $source[3];
        } else {
                $server = str_replace(':', '', $ex[0]);
        }
        if (count ($ex) < 4)
                continue;
        $rawcmd = explode (':', $ex[3]);
        $args = NULL; for ($i = 4; $i < count($ex); $i++) { $args .= $ex[$i] . ' '; }
        $wordlist = explode(' ', $args);
        if (isset($nick))
                $mask = $nick . "!" . $ident . "@" . $host;
		
 /* Server Actions */
		if($ex[1] == '432') { 
		MSG('   ` Nickname reserved, changing to alternative nick: '.$alternative_nick);
		fputs($socket,'NICK '.$alternative_nick."\n");
		}

		if($ex[1] == '433') { 
		MSG('   ` Nickname already used, changing to alternative nick: '.$alternative_nick);
		fputs($socket,'NICK '.$alternative_nick."\n");
		}

		if($ex[1] == '376') { 
		MSG('5. OK im connected! ]:)');
		MSG('6. Joining channel: '.$GLOBALS['channel']);
		fputs($socket,'JOIN '.$GLOBALS['channel']."\n");
		}
// join if no motd
		if($ex[1] == '422') { 
		MSG('5. OK im connected! ]:)');
		MSG('6. Joining channel: '.$GLOBALS['channel']);
		fputs($socket,'JOIN '.$GLOBALS['channel']."\n");
		}


 /* CTCP */
             if ($rawcmd[1] == "VERSION") {
                    fputs($socket, "NOTICE $nick :VERSION http://github.com/S3x0r/davybot\n");
             }
             elseif ($rawcmd[1] == "FINGER") {
                    fputs($socket, "NOTICE $nick :FINGER http://github.com/S3x0r/davybot\n");
             }
             elseif ($rawcmd[1] == "PING") {
                    $a = str_replace(" ","",$args);
                    fputs($socket, "NOTICE $nick :PING ".$a."\n");
             }
             elseif ($rawcmd[1] == "TIME") {
                    $a = date("F j, Y, g:i a");
                    fputs($socket, "NOTICE $nick :TIME ".$a."\n");
             }

 /* Commands */
		if (HasOwner ($mask)) 
		{
		if ($rawcmd[1] == '!voice')        {	voice();			}
		if ($rawcmd[1] == '!devoice')      {	devoice();			}
		if ($rawcmd[1] == '!update')       {	update();			}
		if ($rawcmd[1] == '!restart')      {	restart();			}
		if ($rawcmd[1] == '!uptime')       {	uptime();			}
		if ($rawcmd[1] == '!md5')	       {	emd5();				}
		if ($rawcmd[1] == '!info')	       {	info();				}
		if ($rawcmd[1] == '!op')	       {	op();				}
		if ($rawcmd[1] == '!deop')	       {	deop();				}
		if ($rawcmd[1] == '!join')	       {	joinc();			}
		if ($rawcmd[1] == '!j')		       {	joinc();			}
		if ($rawcmd[1] == '!leave')	       {	leave();			}
		if ($rawcmd[1] == '!part')         {	leave();			}
		if ($rawcmd[1] == '!quit')	       {	quit();				}
		if ($rawcmd[1] == '!die')	       {	quit();				}
		if ($rawcmd[1] == '!topic')	       {	topic();			}
		if ($rawcmd[1] == '!cham')	       {	cham();				}
		if ($rawcmd[1] == '!newnick')      {	newnick();			}
		if ($rawcmd[1] == '!commands')     {	commands();			}
		if ($rawcmd[1] == '!showconfig')   {	showconfig();		}
		if ($rawcmd[1] == '!savenick')	   {	savenick();			}
		if ($rawcmd[1] == '!savealtnick')  {	savealtnick();		}
		if ($rawcmd[1] == '!saveident')    {	saveident();		}
		if ($rawcmd[1] == '!savename')     {	savename();			}
		if ($rawcmd[1] == '!saveport')     {	saveport();			}
		if ($rawcmd[1] == '!saveserver')   {	saveserver();		}
		if ($rawcmd[1] == '!savechannel')  {	savechannel();		}
		if ($rawcmd[1] == '!listadmins')   {	listadmins();		}
		}
       }
   exit;
 }
}
//------------------------------------------------------------------------------------------------
function HasAccess ($mask) {
        global $admins;
        if ($mask == NULL)
        if ($mask == NULL)
                return FALSE;
        foreach ($admins as $admin)
                if (fnmatch($admin, $mask, 16))
                        return TRUE;
        return FALSE;
}
//------------------------------------------------------------------------------------------------
function HasOwner ($mask) {
        global $owners;
        if ($mask == NULL)
        if ($mask == NULL)
                return FALSE;
        foreach ($owners as $owner)
                if (fnmatch($owner, $mask, 16))
                        return TRUE;
        return FALSE;
}
//------------------------------------------------------------------------------------------------
function MSG($msg)
{
 $line = '[' . @date( 'H:i:s' ) . '] ' . $msg . "\r\n";
 echo $line;
}
//------------------------------------------------------------------------------------------------
// Configuration File Parser
class iniParser {
	
	var $_iniFilename = '';
	var $_iniParsedArray = array();
	
	function iniParser($file)
	{
		$this->_iniFilename = $file;
		if($this->_iniParsedArray = parse_ini_file($file, true) ) {
			return true;
		} else {
			return false;
		} 
	}
	function getSection($key)
	{
		return $this->_iniParsedArray[$key];
	}
	function getValue($sec, $key)
	{
		if(!isset($this->_iniParsedArray[$sec])) return false;
		return $this->_iniParsedArray[$sec][$key];
	}
	function get($sec, $key=NULL)
	{
		if(is_null($key)) return $this->getSection($sec);
		return $this->getValue($sec, $key);
	}
	function setSection($sec, $array)
	{
		if(!is_array($array)) return false;
		return $this->_iniParsedArray[$sec] = $array;
	}
	function setValue($sec, $key, $value)
	{
		if($this->_iniParsedArray[$sec][$key] = $value) return true;
	}
	function set($sec, $key, $value=NULL)
	{
		if(is_array($key) && is_null($value)) return $this->setSection($sec, $key);
		return $this->setValue($sec, $key, $value);
	}
	function save( $file = null )
	{
		if($file == null) $file = $this->_iniFilename;
		if(is_writeable($file) ) {
			$desc = fopen($file, "w");
			foreach($this->_iniParsedArray as $sec => $array){
				fwrite($desc, "[" . $sec . "]\n" );
				foreach($array as $key => $value) {
					fwrite( $desc, "$key = '$value'\n" );
				}
				fwrite($desc, "\n");
			}
			fclose($desc);
			return true;
		} else {
			return false;
		}
	}
}

?>