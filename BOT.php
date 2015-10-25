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
define('VER', '0.1.4');
//------------------------------------------------------------------------------------------------
Start();
//------------------------------------------------------------------------------------------------
function Start()
{
 if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser.'); }

 $GLOBALS['StartTime'] = time();
 echo "
     __                      __           __ 
 .--|  |.---.-.--.--.--.--. |  |--.-----.|  |_
 |  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|
 |_____||___._|\___/|___  | |_____|_____||____|
                    |_____|    version ".VER."

    Author: S3x0r, contact: olisek@gmail.com 
 \n\n";

 LoadConfig();
}
//------------------------------------------------------------------------------------------------ 
function LoadConfig()
{
 global $cfg;

 $conf_file = '../CONFIG.INI';
  
  if(file_exists($conf_file)) {
   
   set_time_limit(0);
   
   $cfg = new iniParser($conf_file);
 
  /* load configuration */

  /* BOT */
   $GLOBALS['nickname']         = $cfg->get("BOT","nickname");
   $GLOBALS['alternative_nick'] = $cfg->get("BOT","alternative_nick");
   $GLOBALS['name']             = $cfg->get("BOT","name");
   $GLOBALS['ident']            = $cfg->get("BOT","ident");
  /* SERVER */ 
   $GLOBALS['server']           = $cfg->get("SERVER","server");
   $GLOBALS['port']             = $cfg->get("SERVER","port");
   $GLOBALS['try_connect']      = $cfg->get("SERVER","try_connect");
   $GLOBALS['connect_delay']    = $cfg->get("SERVER","connect_delay");   
  /* CHANNEL */
   $GLOBALS['channel']          = $cfg->get("CHANNEL","channel");
   $GLOBALS['auto_join']        = $cfg->get("CHANNEL","auto_join");
   
   $GLOBALS['command_prefix']   = $cfg->get("COMMAND","command_prefix");
  /* CTCP */
   $GLOBALS['ctcp_response']    = $cfg->get("CTCP","ctcp_response");
   $GLOBALS['ctcp_version']     = $cfg->get("CTCP","ctcp_version");
   $GLOBALS['ctcp_finger']      = $cfg->get("CTCP","ctcp_finger");
  /* DEBUG */
   $GLOBALS['show_raw']         = $cfg->get("DEBUG","show_raw");

   if($GLOBALS['show_raw'] == 'yes') { error_reporting(E_ALL ^ E_NOTICE); } else { error_reporting(0); }
   
   MSG("1. Configuration Loaded from: CONFIG.INI");
   /* now time for plugins */
   LoadPlugins();
  }
  else {
	  MSG("ERROR: No configuration file");
	  MSG("Creating default configuration in: CONFIG.INI - !Configure it!\n");

/* if no config - creating default one */
$default_config = '[BOT]
nickname         = \'davybot\'
alternative_nick = \'davybot-\'
name             = \'http://github.com/S3x0r/davybot\'
ident            = \'http://github.com/S3x0r/davybot\'

[SERVER]
server           = \'localhost\'
port             = \'6667\'
try_connect      = \'10\'
connect_delay    = \'3\'

[CHANNEL]
channel          = \'#davybot\'
auto_join        = \'yes\'

[COMMAND]
command_prefix   = \'!\'

[CTCP]
ctcp_response    = \'yes\'
ctcp_version     = \'davybot\'
ctcp_finger      = \'davybot\'

[DEBUG]
show_raw         = \'no\'';

	$f=fopen($conf_file, 'w');
	flock($f, 2);
	fwrite($f, $default_config);
	flock($f, 3);
	fclose($f); 
   /* after created load config again :) */
	LoadConfig();
  }
}
//------------------------------------------------------------------------------------------------
function LoadPlugins()
{
MSG("2. My Plugins:");
echo "--------------------------------------------------------\n";
 foreach(glob('../PLUGINS/*.php') as $plugin_name)
 {
  include_once($plugin_name);
  $plugin_name = basename($plugin_name, '.php');
  echo "$plugin_name - $plugin_description\n";
 }
echo "--------------------------------------------------------\n";
/* we are now connecting to server */
Connect();
}
//------------------------------------------------------------------------------------------------
function Connect()
{
global $socket;
global $nickname;
global $try_connect;

 MSG('3. Connecting to: '.$GLOBALS['server'].', port: '.$GLOBALS['port']);

 $i = 0;

/* loop if something goes wrong */
 while ($i++ < $try_connect)
 {
  $socket = fsockopen($GLOBALS['server'], $GLOBALS['port']);

   if($socket==false) {
	 MSG('Unable to connect to server, im trying to connect again...');
     sleep($GLOBALS['connect_delay']); 
    if($i==$try_connect) {
     MSG('Unable to connect to server, exiting program.');
	 die(); /* TODO: send email that terminated program? */
	 }
   }
	else {
          Identify();
	     }
  }
}
//------------------------------------------------------------------------------------------------
function Identify()
{
global $socket;
global $nickname;

/* sending user/nick to server */
fputs($socket, 'USER '.$nickname.' FORCE '.$GLOBALS['ident'].' :'.$GLOBALS['name']."\n");
fputs($socket, 'NICK '.$nickname."\n");

Engine();
}
//------------------------------------------------------------------------------------------------
function Engine()
{
global $socket;
global $alternative_nick;
global $args;
global $command_prefix;
global $nickname;

/* main socket loop */
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
		 MSG('4. OK im connected, my nickname is: '.$nickname);
		
		 if($GLOBALS['auto_join'] == 'yes') { 
		 MSG('5. Joining channel: '.$GLOBALS['channel']);
	     fputs($socket,'JOIN '.$GLOBALS['channel']."\n");
		 }
		break;
	
	/* join if no motd */
	case "422":
         MSG('4. OK im connected, my nickname is: '.$nickname);

		 if($GLOBALS['auto_join'] == 'yes') {
		 MSG('5. Joining channel: '.$GLOBALS['channel']);
		 fputs($socket,'JOIN '.$GLOBALS['channel']."\n");
		 }
		break;

	/* quit message */
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


 /* commands */
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
/* configuration file parser */
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