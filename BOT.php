<?php
//------------------------------------------------------------------------------------------------
define('VER', '0.3.2');
define('START_TIME',time());
//------------------------------------------------------------------------------------------------
Start();
//------------------------------------------------------------------------------------------------
function Start()
{
 if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }
 
 /* wcli extension */
 if (extension_loaded('wcli')) {
 wcli_clear();
 wcli_maximize();
 wcli_set_console_title('davybot ('.VER.')');
 wcli_hide_cursor();
 }

 $php_ver = phpversion();

 echo "
      __                      __           __ 
  .--|  |.---.-.--.--.--.--. |  |--.-----.|  |_
  |  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|
  |_____||___._|\___/|___  | |_____|_____||____|
                     |_____|    version ".VER."

    Author: S3x0r, contact: olisek@gmail.com 
      Web: https://github.com/S3x0r/davybot 
	       PHP version: ".$php_ver."
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
   $GLOBALS['C_NICKNAME']       = $cfg->get("BOT","nickname");
   $GLOBALS['C_NAME']           = $cfg->get("BOT","name");
   $GLOBALS['C_IDENT']          = $cfg->get("BOT","ident");
  /* SERVER */ 
   $GLOBALS['C_SERVER']         = $cfg->get("SERVER","server");
   $GLOBALS['C_PORT']           = $cfg->get("SERVER","port");
   $GLOBALS['C_TRY_CONNECT']    = $cfg->get("SERVER","try_connect");
   $GLOBALS['C_CONNECT_DELAY']  = $cfg->get("SERVER","connect_delay"); 
  /* ADMIN */
   $GLOBALS['C_AUTO_OP_LIST']   = $cfg->get("ADMIN","auto_op_list");
   $GLOBALS['C_OWNERS']         = $cfg->get("ADMIN","bot_owners");
   $GLOBALS['C_OWNER_PASSWD']   = $cfg->get("ADMIN","owner_password");
  /* AUTOMATIC */
   $GLOBALS['C_AUTO_OP']        = $cfg->get("AUTOMATIC","auto_op");
   $GLOBALS['C_AUTO_REJOIN']    = $cfg->get("AUTOMATIC","auto_rejoin");
  /* CHANNEL */
   $GLOBALS['C_CNANNEL']        = $cfg->get("CHANNEL","channel");
   $GLOBALS['C_AUTO_JOIN']      = $cfg->get("CHANNEL","auto_join");
  /* COMMAND PREFIX */ 
   $GLOBALS['C_CMD_PREFIX']     = $cfg->get("COMMAND","command_prefix");
  /* CTCP */
   $GLOBALS['C_CTCP_RESPONSE']  = $cfg->get("CTCP","ctcp_response");
   $GLOBALS['C_CTCP_VERSION']   = $cfg->get("CTCP","ctcp_version");
   $GLOBALS['C_CTCP_FINGER']    = $cfg->get("CTCP","ctcp_finger");
  /* FETCH */
   $GLOBALS['C_FETCH_SERVER']   = $cfg->get("FETCH","fetch_server");
  /* DEBUG */
   $GLOBALS['C_SHOW_RAW']       = $cfg->get("DEBUG","show_raw");

  /* show raw or no */
   if($GLOBALS['C_SHOW_RAW'] == 'yes') { error_reporting(E_ALL ^ E_NOTICE); } else { error_reporting(0); }

  /* if default master password, prompt for change it! */
  if($GLOBALS['C_OWNER_PASSWD'] == 'change_me!')
   { 
     CLI_MSG('Default owner bot password detected!');
	 CLI_MSG('For security please change it');

	 if(!defined("STDIN")) {
     define("STDIN", fopen('php://stdin','rb'));
     }

    echo 'New Password: ';
    $new_pwd = fread(STDIN, 30);
	$tr = rtrim($new_pwd, "\n\r");
    SaveData('../CONFIG.INI', 'ADMIN', 'owner_password', $tr);

    LoadConfig();
   }

   /* set data.ini */
   SaveToFile('../data.ini', '[DATA]nickname =', 'w');
   
   SaveData('../data.ini', 'DATA', 'nickname', $GLOBALS['C_NICKNAME']); /* saving nickname to data file */
   $GLOBALS['RND_NICKNAME'] = $GLOBALS['C_NICKNAME'].'|'.rand(0,99); /* set random nickname */
   
   CLI_MSG("1. Configuration Loaded from: CONFIG.INI");

   /* now time for plugins */
   LoadPlugins();
  }
  else {
	  CLI_MSG("ERROR: No configuration file");
	  CLI_MSG("Creating default configuration in: CONFIG.INI - !Configure it!\n");

/* if no config - creating default one */
$default_config = '[BOT]
nickname         = \'davybot\'
name             = \'http://github.com/S3x0r/davybot\'
ident            = \'http://github.com/S3x0r/davybot\'

[SERVER]
server           = \'minionki.com.pl\'
port             = \'6667\'
try_connect      = \'10\'
connect_delay    = \'3\'

[ADMIN]
auto_op_list     = \'S3x0r!S3x0r@Clk-945A43A3, nick!ident@some.other.host\'
bot_owners       = \'S3x0r!S3x0r@Clk-945A43A3, nick!ident@some.other.host\'
owner_password   = \'change_me!\'

[AUTOMATIC]
auto_op          = \'yes\'
auto_rejoin      = \'yes\'

[CHANNEL]
channel          = \'#davybot\'
auto_join        = \'yes\'

[COMMAND]
command_prefix   = \'!\'

[CTCP]
ctcp_response    = \'yes\'
ctcp_version     = \'davybot ('.VER.')\'
ctcp_finger      = \'davybot\'

[FETCH]
fetch_server     = \'https://raw.githubusercontent.com/S3x0r/davybot_repository_plugins/master\'

[DEBUG]
show_raw         = \'no\'';

    /* Save default config to file if no config */
    SaveToFile($conf_file, $default_config, 'w');

   /* after default config load it again :) */
	LoadConfig();
  }
}
//------------------------------------------------------------------------------------------------
function LoadPlugins()
{
 $a = count(glob("../PLUGINS/*.php",GLOB_BRACE));
 $b = fopen('../plugins.ini', 'w');
 
 CLI_MSG("2. My Plugins($a):");
 
 echo "--------------------------------------------------------\n";
  
   foreach(glob('../PLUGINS/*.php') as $plugin_name)
  {
   include_once($plugin_name);
   fwrite($b, $GLOBALS['C_CMD_PREFIX'].''.$plugin_command.' ');
   $plugin_name = basename($plugin_name, '.php');
   echo "$plugin_name -- $plugin_description\n";
  }
 echo "--------------------------------------------------------\n";
 
 fclose($b);

 $GLOBALS['PLUGINS'] = file_get_contents("../plugins.ini"); 
 $GLOBALS['PLUGINS'] = explode(" ", $GLOBALS['PLUGINS']);

 if($GLOBALS['C_SHOW_RAW'] == 'yes') { print_r($GLOBALS['PLUGINS']); }

 /* Now its time to connect */
 Connect();
}
//------------------------------------------------------------------------------------------------
function Connect()
{
 CLI_MSG('3. Connecting to: '.$GLOBALS['C_SERVER'].', port: '.$GLOBALS['C_PORT']);

 $i = 0;

/* loop if something goes wrong */
 while ($i++ < $GLOBALS['C_TRY_CONNECT'])
 {
  $GLOBALS['socket'] = fsockopen($GLOBALS['C_SERVER'], $GLOBALS['C_PORT']);

   if($GLOBALS['socket']==false) {
	 CLI_MSG('Unable to connect to server, im trying to connect again...');
     sleep($GLOBALS['C_CONNECT_DELAY']); 
    if($i==$GLOBALS['C_TRY_CONNECT']) {
     CLI_MSG('Unable to connect to server, exiting program.');
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
/* sending user/nick to server */
fputs($GLOBALS['socket'], 'USER '.$GLOBALS['C_NICKNAME'].' FORCE '.$GLOBALS['C_IDENT'].' :'.$GLOBALS['C_NAME']."\n");
fputs($GLOBALS['socket'], 'NICK '.$GLOBALS['C_NICKNAME']."\n");
Engine();
}
//------------------------------------------------------------------------------------------------
function Engine()
{
global $args;
global $nick;
global $hostname;
global $piece1;
global $piece2;
global $ex;
global $rawcmd;

/* main socket loop */
while(1) {
    while(!feof($GLOBALS['socket'])) {
        $mask = NULL;
        $data = fgets($GLOBALS['socket'], 512);
        if($GLOBALS['C_SHOW_RAW'] == 'yes') { echo $data; }

        flush();
        $ex = explode(' ', trim($data));

/* ping response */
		if($ex[0] == "PING") {
            fputs($GLOBALS['socket'], "PONG ".$ex[1]."\n");
            continue; 
        }
//---

/* rejoin when kicked */
		if($GLOBALS['C_AUTO_REJOIN'] == 'yes') {
	    	if($ex[1] == "KICK"){
				if($ex[3] == $GLOBALS['C_NICKNAME']){
					CLI_MSG("I was kicked from channel, joining again...");
					fputs($GLOBALS['socket'], "JOIN :".$ex[2]."\n");
					continue;
				}	} }
//---

		if (preg_match ('/^:(.*)\!(.*)\@(.*)$/', $ex[0], $source)) {

                $nick   = $source[1];
                $ident  = $source[2];
                $host   = $source[3];
        } else {
                $server = str_replace(':', '', $ex[0]);
        }

/* auto op */
		if($GLOBALS['C_AUTO_OP'] == 'yes') {
			$auto_op_list_c = $GLOBALS['C_AUTO_OP_LIST'];
			$pieces = explode(", ", $auto_op_list_c);

			$mask2 = $nick.'!'.$ident.'@'.$host;

			if($ex[1] == "JOIN" && in_array($mask2,  $pieces))
			{	
			 CLI_MSG("I have nick: ".$nick." on auto op list, giving op");
			 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['C_CNANNEL'].' +o '.$nick."\n");
			 continue;
			}
		}
//---
		if(count ($ex) < 4)
        continue;
        
		$rawcmd = explode (':', $ex[3]);
        $args = NULL; for($i=4; $i < count($ex); $i++) { $args .= $ex[$i].''; }
        $args1 = NULL; for($i=4; $i < count($ex); $i++) { $args1 .= $ex[$i].' '; }

        $pieces = explode(" ", $args1);
        $piece1 = $pieces[0];
		$piece2 = $pieces[1];
		//-
        if (isset($nick)) { $mask = $nick . "!" . $ident . "@" . $host; }

		$hostname = $ident . "@" . $host;
//-----------
switch ($ex[1]){
	
	case '433': /* if nick already exists */
	case '432': /* if nick reserved */
	    CLI_MSG('-- Nickname already used, changing to alternative nick: '.$GLOBALS['RND_NICKNAME']);
		fputs($GLOBALS['socket'],'NICK '.$GLOBALS['RND_NICKNAME']."\n");
		SaveData('../data.ini', 'DATA', 'nickname', $GLOBALS['RND_NICKNAME']);
        break;

	case '422': /* join if no motd */
	case '376': /* join after motd */
	     LoadData('../data.ini', 'DATA', 'nickname');
		 CLI_MSG('4. OK im connected, my nickname is: '.$GLOBALS['LOADED']);
		
		 /* wcli extension */
		 if (extension_loaded('wcli')) {
		 wcli_set_console_title('davybot '.VER.' (server: '.$GLOBALS['C_SERVER'].':'.$GLOBALS['C_PORT'].' | nickname: '.$GLOBALS['LOADED'].' | channel: '.$GLOBALS['C_CNANNEL'].')');
		 }
		 
		 /* if autojoin */
		 if($GLOBALS['C_AUTO_JOIN'] == 'yes') { 
		 CLI_MSG('5. Joining channel: '.$GLOBALS['C_CNANNEL']);
		 JOIN_CHANNEL($GLOBALS['C_CNANNEL']);
		 break;
		 }
		break;

	case 'QUIT': /* quit message */
		CLI_MSG('* '.$nick.' ('.$ident.'@'.$host.') Quit');
		//todo:save_to_database(); /* Saving to database -> !seen */
		break;
}

 /* CTCP */
     if($GLOBALS['C_CTCP_RESPONSE'] == 'yes') {
             if ($rawcmd[1] == "VERSION") {
                    fputs($GLOBALS['socket'], "NOTICE $nick :VERSION ".$GLOBALS['C_CTCP_VERSION']."\n");
             }
             elseif ($rawcmd[1] == "FINGER") {
                    fputs($GLOBALS['socket'], "NOTICE $nick :FINGER ".$GLOBALS['C_CTCP_FINGER']."\n");
             }
             elseif ($rawcmd[1] == "PING") {
                    $a = str_replace(" ","",$args);
                    fputs($GLOBALS['socket'], "NOTICE $nick :PING ".$a."\n");
             }
             elseif ($rawcmd[1] == "TIME") {
                    $a = date("F j, Y, g:i a");
                    fputs($GLOBALS['socket'], "NOTICE $nick :TIME ".$a."\n");
             }
	       }
 
 /* if owner register -> add host to owner list in config */
 if($rawcmd[1] == 'register' && $args == $GLOBALS['C_OWNER_PASSWD'])
    {
	 LoadData('../CONFIG.INI', 'ADMIN', 'bot_owners');

     $owners_list = $GLOBALS['LOADED'];
     $new         = trim($mask2);
     $new_list    = $owners_list.', '.$new;

     SaveData('../CONFIG.INI', 'ADMIN', 'bot_owners', $new_list);

	 $commands = file_get_contents('../plugins.ini');

     NICK_MSG('From now you are on my owners list, enjoy.');
     NICK_MSG('My Commands:');
     NICK_MSG($commands);

     CLI_MSG('I have new owner in list: '.$GLOBALS['C_CNANNEL'].', added: '.$mask2);

     /* give op before restart */
     fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['C_CNANNEL'].' +o '.$GLOBALS['nick']."\n");

     fputs($GLOBALS['socket'],"QUIT :Restarting...\n");
     CLI_MSG('Restarting BOT...');
     system('restart.bat');
     die();

	 }
//---

 /* plugins commands */
	if(HasOwner($mask)) 
	{
		$pn = str_replace($GLOBALS['C_CMD_PREFIX'], '', $rawcmd[1]);
		if (in_array($rawcmd[1], $GLOBALS['PLUGINS'])) { call_user_func('plugin_'.$pn); }
	}
   }
   exit;
 }
}
//------------------------------------------------------------------------------------------------
function msg_without_command()
{
  $input = NULL;
  for($i=3; $i <= (count($GLOBALS['ex'])); $i++) { $input .= $GLOBALS['ex'][$i]." "; }
      
  $in = rtrim($input); 
  $data = str_replace($GLOBALS['rawcmd'][1].' ', '', $in);

  return $data;
}
//------------------------------------------------------------------------------------------------
function HasAccess($mask)
{
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
function HasOwner($mask)
{
 global $owners;

 $owners_c = $GLOBALS['C_OWNERS'];
 $pieces = explode(", ", $owners_c);
 $owners = $pieces;

        if ($mask == NULL)
        if ($mask == NULL)
                return FALSE;
        foreach ($owners as $owner)
                if (fnmatch($owner, $mask, 16))
                        return TRUE;
        return FALSE;
}
//------------------------------------------------------------------------------------------------
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
//------------------------------------------------------------------------------------------------
function SaveData($v1, $v2, $v3, $v4)
{
 $cfg = new iniParser($v1);
 $cfg->setValue("$v2", "$v3", "$v4");
 $cfg->save();
}
//------------------------------------------------------------------------------------------------
function LoadData($v1, $v2, $v3)
{
 $cfg = new iniParser($v1);
 $GLOBALS['LOADED'] = $cfg->get("$v2", "$v3");
}
//------------------------------------------------------------------------------------------------
function CLI_MSG($msg)
{
 $line = '[' . @date( 'H:i:s' ) . '] ' . $msg . "\r\n";
 echo $line;
}
//------------------------------------------------------------------------------------------------
function CHANNEL_MSG($msg)
{
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['C_CNANNEL']." :$msg\n");
}
//------------------------------------------------------------------------------------------------
function NICK_MSG($msg)
{
 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['nick']." :$msg\n");
}
//------------------------------------------------------------------------------------------------
function JOIN_CHANNEL($channel)
{
 fputs($GLOBALS['socket'],'JOIN '.$channel."\n");
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
				fwrite($desc, "[" . $sec . "]\r\n" );
				foreach($array as $key => $value) {
					fwrite( $desc, "$key = '$value'\r\n" );
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

?>