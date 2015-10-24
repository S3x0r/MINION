<?php

/* OWNERS HOSTS - EDIT IT FOR USING BOT COMMANDS!

            nick ! ident@   host
              |      |       |
   example: S3x0r!~S3x0r@85-123-48-249.dsl.dynamic.simnet.is
*/
//------------------------------------------------------------------------------------------------
$GLOBALS['owners'] = Array ('S3x0r!S3x0r@validation.sls.microsoft.com','');
$GLOBALS['admins'] = Array ('' , ''); 
//------------------------------------------------------------------------------------------------
error_reporting(E_ALL ^ E_NOTICE);
//------------------------------------------------------------------------------------------------
define('VER', '0.1.3');
//------------------------------------------------------------------------------------------------
Start();
LoadConfig();
LoadPlugins();
Connect();
//------------------------------------------------------------------------------------------------
function Start()
{
 if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser.'); }

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

 $conf_file = '../CONFIG.INI';
  
  if(file_exists($conf_file)) {
   
   set_time_limit(0);
   
   $cfg = new iniParser($conf_file);
 
  /* CONFIGURATION */
   $GLOBALS['nickname']         = $cfg->get("Configuration","nickname");
   $GLOBALS['alternative_nick'] = $cfg->get("Configuration","alternative_nick");
   $GLOBALS['name']             = $cfg->get("Configuration","name");	
   $GLOBALS['ident']            = $cfg->get("Configuration","ident");			
   $GLOBALS['server']           = $cfg->get("Configuration","server");			
   $GLOBALS['port']             = $cfg->get("Configuration","port");			
   $GLOBALS['channel']          = $cfg->get("Configuration","channel");
   $GLOBALS['auto_join']        = $cfg->get("Configuration","auto_join");
   $GLOBALS['command_prefix']   = $cfg->get("Configuration","command_prefix");
  /* CTCP */
   $GLOBALS['ctcp_response']    = $cfg->get("CTCP","ctcp_response");
   $GLOBALS['ctcp_version']     = $cfg->get("CTCP","ctcp_version");
   $GLOBALS['ctcp_finger']      = $cfg->get("CTCP","ctcp_finger");
  /* DEBUG */
   $GLOBALS['show_raw']         = $cfg->get("Debug","show_raw");

   MSG("1. Configuration Loaded from: CONFIG.INI\n");
  }
  else {
	  MSG("ERROR: You need configuration file (CONFIG.INI)");
	  MSG("Created default configuration, you need to change it.");

 /* Creating default config */
$default_config = '[Configuration]
nickname         = \'davybot\'
alternative_nick = \'davybot-\'
name             = \'http://github.com/S3x0r/davybot\'
ident            = \'http://github.com/S3x0r/davybot\'
server           = \'localhost\'
port             = \'6667\'
channel          = \'#davybot\'
auto_join        = \'yes\'
command_prefix   = \'!\'

[CTCP]
ctcp_response    = \'yes\'
ctcp_version     = \'davybot\'
ctcp_finger      = \'davybot\'

[Debug]
show_raw         = \'no\'';

	$f=fopen('../CONFIG.INI', 'w');
	flock($f, 2);
	fwrite($f, $default_config);
	flock($f, 3);
	fclose($f); 

	LoadConfig();
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
    //FIX IT: try connect some time next exit program if failed.
	Connect();
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
global $command_prefix;

while(1) {
    while(!feof($socket)) {
        $mask   = NULL;
        $data = fgets ($socket, 512);
        if($GLOBALS['show_raw'] == 'yes') { echo $data; }

        flush();
        $ex = explode(' ', trim($data));

/* ping response */      
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
		
//-----------
switch ($ex[1]){
	
	 /* if nick already exists */
	case "432":
		MSG('   ` Nickname reserved, changing to alternative nick: '.$alternative_nick);
		fputs($socket,'NICK '.$alternative_nick."\n");
		break;
		 
	/* if nick already exists */
	case "433":
		MSG('   ` Nickname already used, changing to alternative nick: '.$alternative_nick);
		fputs($socket,'NICK '.$alternative_nick."\n");
		break;

	/* join after motd */
	case "376":
		MSG('5. OK im connected! ]:)');
		
		 if($GLOBALS['auto_join'] == 'yes') { 
		 MSG('6. Joining channel: '.$GLOBALS['channel']);
	     fputs($socket,'JOIN '.$GLOBALS['channel']."\n");
		 }
		break;
	
	/* join if no motd */
	case "422":
		MSG('5. OK im connected! ]:)');

		 if($GLOBALS['auto_join'] == 'yes') {
		 MSG('6. Joining channel: '.$GLOBALS['channel']);
		 fputs($socket,'JOIN '.$GLOBALS['channel']."\n");
		 }
		break;

	/* Quit message */
	case "QUIT":
		MSG('* '.$nick.' ('.$ident.'@'.$host.') Quit');
		break;

//NAPRAWIÆ TYLKO JEDNO S£OWO POKAZUJE
	/* Changed Topic message */
//	case "TOPIC":
//		MSG('* '.$nick.' changes topic to \''.$ex[3].'\'');
//		break;

	}


 /* CTCP */
     if($GLOBALS['ctcp_response'] == 'yes') {
             if ($rawcmd[1] == "VERSION") {
                    fputs($socket, "NOTICE $nick :VERSION ".$GLOBALS['ctcp_version']."\n");
             }
             elseif ($rawcmd[1] == "FINGER") {
                    fputs($socket, "NOTICE $nick :FINGER ".$GLOBALS['ctcp_finger']."\n");
             }
             elseif ($rawcmd[1] == "PING") {
                    $a = str_replace(" ","",$args);
                    fputs($socket, "NOTICE $nick :PING ".$a."\n");
             }
             elseif ($rawcmd[1] == "TIME") {
                    $a = date("F j, Y, g:i a");
                    fputs($socket, "NOTICE $nick :TIME ".$a."\n");
             }
	       }


 /* Commands */
		if (HasOwner ($mask)) 
		{
		if ($rawcmd[1] == $command_prefix.'dns')          {	dns();           }
		if ($rawcmd[1] == $command_prefix.'voice')        {	voice();         }
		if ($rawcmd[1] == $command_prefix.'devoice')      {	devoice();       }
		if ($rawcmd[1] == $command_prefix.'update')       {	update();        }
		if ($rawcmd[1] == $command_prefix.'restart')      {	restart();       }
		if ($rawcmd[1] == $command_prefix.'uptime')       {	uptime();        }
		if ($rawcmd[1] == $command_prefix.'md5')          {	emd5();          }
		if ($rawcmd[1] == $command_prefix.'info')         {	info();          }
		if ($rawcmd[1] == $command_prefix.'op')           {	op();            }
		if ($rawcmd[1] == $command_prefix.'deop')         {	deop();          }
		if ($rawcmd[1] == $command_prefix.'join')         {	joinc();         }
		if ($rawcmd[1] == $command_prefix.'j')            {	joinc();         }
		if ($rawcmd[1] == $command_prefix.'leave')        {	leave();         }
		if ($rawcmd[1] == $command_prefix.'part')         {	leave();         }
		if ($rawcmd[1] == $command_prefix.'quit')         {	quit();          }
		if ($rawcmd[1] == $command_prefix.'die')          {	quit();          }
		if ($rawcmd[1] == $command_prefix.'topic')        {	topic();         }
		if ($rawcmd[1] == $command_prefix.'cham')         {	cham();          }
		if ($rawcmd[1] == $command_prefix.'newnick')      {	newnick();       }
		if ($rawcmd[1] == $command_prefix.'commands')     {	commands();      }
		if ($rawcmd[1] == $command_prefix.'showconfig')   {	showconfig();    }
		if ($rawcmd[1] == $command_prefix.'savenick')	  {	savenick();      }
		if ($rawcmd[1] == $command_prefix.'savealtnick')  {	savealtnick();   }
		if ($rawcmd[1] == $command_prefix.'saveident')    {	saveident();     }
		if ($rawcmd[1] == $command_prefix.'savename')     {	savename();      }
		if ($rawcmd[1] == $command_prefix.'saveport')     {	saveport();      }
		if ($rawcmd[1] == $command_prefix.'saveserver')   {	saveserver();    }
		if ($rawcmd[1] == $command_prefix.'savechannel')  {	savechannel();   }
		if ($rawcmd[1] == $command_prefix.'listadmins')   {	listadmins();    }
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