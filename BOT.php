<?php
//------------------------------------------------------------------------------------------------
define('VER', '0.4.0');

define('START_TIME',time());
define('PHP_VER',phpversion());

set_error_handler('ErrorHandler');
error_reporting(E_ALL ^ E_NOTICE);
//------------------------------------------------------------------------------------------------
Start('../');
//------------------------------------------------------------------------------------------------
function Start($path)
{
  if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

  /* change default directory path */
  chdir($path);

  /* wcli extension */ 
  if (extension_loaded('wcli')) {
  wcli_clear();
  wcli_maximize();
  wcli_set_console_title('davybot ('.VER.')');
  wcli_hide_cursor();
  }

 echo "
      __                      __           __ 
  .--|  |.---.-.--.--.--.--. |  |--.-----.|  |_
  |  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|
  |_____||___._|\___/|___  | |_____|_____||____|
                     |_____|    version ".VER."

    Author: S3x0r, contact: olisek@gmail.com 
      Web: https://github.com/S3x0r/davybot 
	   Total Lines of code: ".TotalLines()." :)
	       PHP version: ".PHP_VER."
 \n\n";
  
  /* try to load config */
  LoadConfig('CONFIG.INI');
}
//------------------------------------------------------------------------------------------------ 
function LoadConfig($filename)
{
  global $cfg;
  global $config_file;

  if(isset($_SERVER["argv"][1])) { $config_file = $_SERVER["argv"][1]; }
  else { $config_file = $filename; }
  
  if(file_exists($config_file)) {
   
   $cfg = new iniParser($config_file);
 
  /* load configuration to variables */

  /* BOT */
   $GLOBALS['CONFIG_NICKNAME']       = $cfg->get("BOT","nickname");
   $GLOBALS['CONFIG_NAME']           = $cfg->get("BOT","name");
   $GLOBALS['CONFIG_IDENT']          = $cfg->get("BOT","ident");
  /* SERVER */ 
   $GLOBALS['CONFIG_SERVER']         = $cfg->get("SERVER","server");
   $GLOBALS['CONFIG_PORT']           = $cfg->get("SERVER","port");
   $GLOBALS['CONFIG_TRY_CONNECT']    = $cfg->get("SERVER","try_connect");
   $GLOBALS['CONFIG_CONNECT_DELAY']  = $cfg->get("SERVER","connect_delay"); 
  /* ADMIN */
   $GLOBALS['CONFIG_AUTO_OP_LIST']   = $cfg->get("ADMIN","auto_op_list");
   $GLOBALS['CONFIG_OWNERS']         = $cfg->get("ADMIN","bot_owners");
   $GLOBALS['CONFIG_OWNER_PASSWD']   = $cfg->get("ADMIN","owner_password");
  /* BOT RESPONSE */
   $GLOBALS['CONFIG_BOT_RESPONSE']   = $cfg->get("RESPONSE","bot_response");
  /* AUTOMATIC */
   $GLOBALS['CONFIG_AUTO_OP']        = $cfg->get("AUTOMATIC","auto_op");
   $GLOBALS['CONFIG_AUTO_REJOIN']    = $cfg->get("AUTOMATIC","auto_rejoin");
  /* CHANNEL */
   $GLOBALS['CONFIG_CNANNEL']        = $cfg->get("CHANNEL","channel");
   $GLOBALS['CONFIG_AUTO_JOIN']      = $cfg->get("CHANNEL","auto_join");
  /* COMMAND PREFIX */ 
   $GLOBALS['CONFIG_CMD_PREFIX']     = $cfg->get("COMMAND","command_prefix");
  /* CTCP */
   $GLOBALS['CONFIG_CTCP_RESPONSE']  = $cfg->get("CTCP","ctcp_response");
   $GLOBALS['CONFIG_CTCP_VERSION']   = $cfg->get("CTCP","ctcp_version");
   $GLOBALS['CONFIG_CTCP_FINGER']    = $cfg->get("CTCP","ctcp_finger");
   /* LOGGING */
   $GLOBALS['CONFIG_LOGGING']        = $cfg->get("LOGS","logging");
  /* TIMEZONE */
   $GLOBALS['CONFIG_TIMEZONE']       = $cfg->get("TIME","time_zone");
  /* FETCH */
   $GLOBALS['CONFIG_FETCH_SERVER']   = $cfg->get("FETCH","fetch_server");
  /* DEBUG */
   $GLOBALS['CONFIG_SHOW_RAW']       = $cfg->get("DEBUG","show_raw");

//------------------------------------------------------------------------------------------------
  /* if default master password, prompt for change it! */
  if($GLOBALS['CONFIG_OWNER_PASSWD'] == 'change_me!')
   { 
     CLI_MSG('Default owner bot password detected!', '0');
	 CLI_MSG('For security please change it', '0');

	 if(!defined("STDIN")) {
     define("STDIN", fopen('php://stdin','rb'));
     }

    echo '[' . @date( 'H:i:s' ) . '] New Password: ';
    $new_pwd = fread(STDIN, 30);
	$tr = rtrim($new_pwd, "\n\r");
    SaveData($config_file, 'ADMIN', 'owner_password', $tr);
    
    /* Set first time change variable */
    $GLOBALS['if_first_time_pwd_change'] = '1';

	/* load config again */
    LoadConfig($config_file);
   }
//------------------------------------------------------------------------------------------------    
   /* from what file config loaded */
   CLI_MSG('Configuration Loaded from: '.$config_file, '0');
   echo "------------------------------------------------------------------------------\n";
   
   /* Set default data */
   SetDefaultData();

   /* logging init */
   if($GLOBALS['CONFIG_LOGGING'] == 'yes') { Logs(); }

   /* now time for plugins */
   LoadPlugins();
  }
//------------------------------------------------------------------------------------------------  
 else {
	    CLI_MSG("ERROR: Configuration file missing!", '0');
	    CLI_MSG("Creating default config in: CONFIG.INI - (need to be configured)\n", '0');
	  
	    /* Create default config */
	    CreateDefaultConfig('CONFIG.INI');
	  }
//------------------------------------------------------------------------------------------------ 
}
//------------------------------------------------------------------------------------------------
function SetDefaultData()
{
  /* set unlimited time for our bot :) */
  set_time_limit(0);

  /* set timezone */
  date_default_timezone_set($GLOBALS['CONFIG_TIMEZONE']);

  /* set data.ini defaults */
  SaveToFile('data.ini', '[DATA]nickname =', 'w');
   
  /* saving nickname to data file */
  SaveData('data.ini', 'DATA', 'nickname', $GLOBALS['CONFIG_NICKNAME']);

  /* set random nickname */
  $GLOBALS['RND_NICKNAME'] = $GLOBALS['CONFIG_NICKNAME'].'|'.rand(0,99);
}
//------------------------------------------------------------------------------------------------
function CreateDefaultConfig($filename)
{
/* default config */
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

[RESPONSE]
bot_response     = \'channel\'

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

[LOGS]
logging          = \'yes\'

[TIME]
time_zone        = \'Europe/Warsaw\'

[FETCH]
fetch_server     = \'https://raw.githubusercontent.com/S3x0r/davybot_repository_plugins/master\'

[DEBUG]
show_raw         = \'no\'';

    /* Save default config to file if no config */
    SaveToFile($filename, $default_config, 'w');

	if(file_exists($filename))
	{
      /* Load config again */
      LoadConfig($filename);
	}

	else if(!file_exists($filename))
	{
	  CLI_MSG('ERROR: Cannot make default config! Exiting.', '1');
	  die();
	}
}
//------------------------------------------------------------------------------------------------ 
function Logs()
{
  global $log_file;

  if(!is_dir('LOGS')) { mkdir('LOGS'); }
  $log_file = 'LOGS/LOG-'.date('d.m.Y').'.TXT';
  $data = "------------------LOG CREATED: ".date('d.m.Y | H:i:s')."------------------\r\n";
  SaveToFile($log_file, $data, 'a');
}
//------------------------------------------------------------------------------------------------
function LoadPlugins()
{
  $count1 = count(glob("PLUGINS/OWNER/*.php",GLOB_BRACE));
  $b = fopen('plugins_owner.ini', 'w');

  CLI_MSG("Owner Plugins ($count1):", '0');

  echo "------------------------------------------------------------------------------\n";

  foreach(glob('PLUGINS/OWNER/*.php') as $plugin_name)
  {
    include_once($plugin_name);
	fwrite($b, $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' ');
    $plugin_name = basename($plugin_name, '.php');
    echo "$plugin_name -- $plugin_description\n";
  }

  echo "------------------------------------------------------------------------------\n";

  fclose($b);
//------
  $count2 = count(glob("PLUGINS/USER/*.php",GLOB_BRACE));
  $b = fopen('plugins_user.ini', 'w');

  CLI_MSG("User Plugins ($count2):", '0');

  echo "------------------------------------------------------------------------------\n";
  
  foreach(glob('PLUGINS/USER/*.php') as $plugin_name)
  {
	include_once($plugin_name);
	fwrite($b, $GLOBALS['CONFIG_CMD_PREFIX'].''.$plugin_command.' '); 
	$plugin_name = basename($plugin_name, '.php');
    echo "$plugin_name -- $plugin_description\n";
   }
 
  $tot = $count1+$count2;
    
  echo "----------------------------------------------------------Total: ($tot)---------\n";
  
  fclose($b);
  
  $GLOBALS['PLUGINS_OWNERS'] = file_get_contents("plugins_owner.ini"); 
  $GLOBALS['PLUGINS_OWNERS'] = explode(" ", $GLOBALS['PLUGINS_OWNERS']);

  $GLOBALS['PLUGINS_USERS'] = file_get_contents("plugins_user.ini"); 
  $GLOBALS['PLUGINS_USERS'] = explode(" ", $GLOBALS['PLUGINS_USERS']);

  /* Now its time to connect */
  Connect();
}
//------------------------------------------------------------------------------------------------
function Connect()
{
  CLI_MSG('Connecting to: '.$GLOBALS['CONFIG_SERVER'].', port: '.$GLOBALS['CONFIG_PORT'], '0');

  $i=0;

  /* loop if something goes wrong */
  while ($i++ < $GLOBALS['CONFIG_TRY_CONNECT'])
  {
    $GLOBALS['socket'] = fsockopen($GLOBALS['CONFIG_SERVER'], $GLOBALS['CONFIG_PORT']);

    if($GLOBALS['socket']==false) {
	 CLI_MSG('Unable to connect to server, im trying to connect again...', '1');
     sleep($GLOBALS['CONFIG_CONNECT_DELAY']); 
    if($i==$GLOBALS['CONFIG_TRY_CONNECT']) {
     CLI_MSG('Unable to connect to server, exiting program.', '1');
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
  fputs($GLOBALS['socket'], 'USER '.$GLOBALS['CONFIG_NICKNAME'].' FORCE '.$GLOBALS['CONFIG_IDENT'].' :'.$GLOBALS['CONFIG_NAME']."\n");
  fputs($GLOBALS['socket'], 'NICK '.$GLOBALS['CONFIG_NICKNAME']."\n");
  
  /* time for socket loop */
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
  global $piece3;
  global $piece4;
  global $ex;
  global $rawcmd;
  global $mask;

/* main socket loop */
while(1) {
    while(!feof($GLOBALS['socket'])) {
     $mask = NULL;
     $data = fgets($GLOBALS['socket'], 512);

        if($GLOBALS['CONFIG_SHOW_RAW'] == 'yes') { echo $data; }

        flush();
        $ex = explode(' ', trim($data));

/* ping response */
		if($ex[0] == "PING") {
            fputs($GLOBALS['socket'], "PONG ".$ex[1]."\n");
            continue; 
        }

/* rejoin when kicked */
		if($GLOBALS['CONFIG_AUTO_REJOIN'] == 'yes') {
	    	if($ex[1] == "KICK"){
				if($ex[3] == $GLOBALS['CONFIG_NICKNAME']){
					CLI_MSG("I was kicked from channel, joining again...", '1');
					fputs($GLOBALS['socket'], "JOIN :".$ex[2]."\n");
					continue;
				}	} }

		if (preg_match ('/^:(.*)\!(.*)\@(.*)$/', $ex[0], $source)) {

                $nick   = $source[1];
                $ident  = $source[2];
                $host   = $source[3];
        } else {
                $server = str_replace(':', '', $ex[0]);
        }

/* auto op */
		if($GLOBALS['CONFIG_AUTO_OP'] == 'yes') {
			$auto_op_list_c = $GLOBALS['CONFIG_AUTO_OP_LIST'];
			$pieces = explode(", ", $auto_op_list_c);

			$mask2 = $nick.'!'.$ident.'@'.$host;

			if($ex[1] == "JOIN" && in_array($mask2,  $pieces))
			{	
			 CLI_MSG("I have nick: ".$nick." on auto op list, giving op", '1');
			 fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['CONFIG_CNANNEL'].' +o '.$nick."\n");
			 continue;
			}
		}

		if(count ($ex) < 4)
        continue;
        
		$rawcmd = explode (':', $ex[3]);
        $args = NULL; for($i=4; $i < count($ex); $i++) { $args .= $ex[$i].''; }
        $args1 = NULL; for($i=4; $i < count($ex); $i++) { $args1 .= $ex[$i].' '; }

        $pieces = explode(" ", $args1);
        $piece1 = $pieces[0];
		$piece2 = $pieces[1];
		$piece3 = $pieces[2];
		$piece4 = $pieces[3];
		//-
        if (isset($nick)) { $mask = $nick . "!" . $ident . "@" . $host; }

		$hostname = $ident . "@" . $host;
//-----------
switch ($ex[1]){
	
	case '433': /* if nick already exists */
	case '432': /* if nick reserved */
	    CLI_MSG('-- Nickname already used, changing to alternative nick: '.$GLOBALS['RND_NICKNAME'], '1');
		fputs($GLOBALS['socket'],'NICK '.$GLOBALS['RND_NICKNAME']."\n");
		SaveData('data.ini', 'DATA', 'nickname', $GLOBALS['RND_NICKNAME']);
        continue;
//------------------------------------------------------------------------------------------------
	case '422': /* join if no motd */
	case '376': /* join after motd */
	     LoadData('data.ini', 'DATA', 'nickname');
		 CLI_MSG('OK im connected, my nickname is: '.$GLOBALS['LOADED'], '1');
		
		 /* register to bot info */
		 if($GLOBALS['if_first_time_pwd_change'] == '1') {
		 CLI_MSG('****************************************************', '0');
		 CLI_MSG('Register to bot by typing /msg '.$GLOBALS['LOADED'].' register '.$GLOBALS['CONFIG_OWNER_PASSWD'], '0');
		 CLI_MSG('****************************************************', '0');
		 }

		 /* wcli extension */
		 if (extension_loaded('wcli')) {
		 wcli_set_console_title('davybot '.VER.' (server: '.$GLOBALS['CONFIG_SERVER'].':'.$GLOBALS['CONFIG_PORT'].' | nickname: '.$GLOBALS['LOADED'].' | channel: '.$GLOBALS['CONFIG_CNANNEL'].')');
		 }

		 /* if autojoin */
		 if($GLOBALS['CONFIG_AUTO_JOIN'] == 'yes') { 
		 CLI_MSG('Joining channel: '.$GLOBALS['CONFIG_CNANNEL'], '1');
		 JOIN_CHANNEL($GLOBALS['CONFIG_CNANNEL']);
		 $GLOBALS['bot_in_channel']='1';
		 }
		continue;
//------------------------------------------------------------------------------------------------
	case 'QUIT': /* quit message */
		CLI_MSG('* '.$nick.' ('.$ident.'@'.$host.') Quit', '1');
		continue;
//------------------------------------------------------------------------------------------------
}

 /* CTCP */
     if($GLOBALS['CONFIG_CTCP_RESPONSE'] == 'yes') {
      
	  switch ($rawcmd[1]){
	
		case 'VERSION':
		fputs($GLOBALS['socket'], "NOTICE $nick :VERSION ".$GLOBALS['CONFIG_CTCP_VERSION']."\n");
		break;

		case 'FINGER':
		fputs($GLOBALS['socket'], "NOTICE $nick :FINGER ".$GLOBALS['CONFIG_CTCP_FINGER']."\n");
		break;

		case 'PING':
		$a = str_replace(" ","",$args);
        fputs($GLOBALS['socket'], "NOTICE $nick :PING ".$a."\n");
		break;

		case 'TIME':
        $a = date("F j, Y, g:i a");
        fputs($GLOBALS['socket'], "NOTICE $nick :TIME ".$a."\n");
		break;
	  }
	}	

 /* if owner register -> add host to owner list in config */
 if($rawcmd[1] == 'register' && $args == $GLOBALS['CONFIG_OWNER_PASSWD'])
    {
	 LoadData($GLOBALS['config_file'], 'ADMIN', 'bot_owners');

     $owners_list = $GLOBALS['LOADED'];
     $new         = trim($mask);
	 if($owners_list == '') { $new_list = $new.''; }
	 if($owners_list != '') { $new_list = $owners_list.', '.$new; }

     SaveData($GLOBALS['config_file'], 'ADMIN', 'bot_owners', $new_list);

     /* Add host to auto op list */
	 LoadData($GLOBALS['config_file'], 'ADMIN', 'auto_op_list');

     $auto_list   = $GLOBALS['LOADED'];
     $new         = trim($mask);
	 if($auto_list == '') { $new_list = $new.''; }
	 if($auto_list != '') { $new_list = $auto_list.', '.$new; }

	 SaveData($GLOBALS['config_file'], 'ADMIN', 'auto_op_list', $new_list);
     //-

	 $owner_commands = file_get_contents('plugins_owner.ini');
     $user_commands  = file_get_contents('plugins_user.ini');

     NICK_MSG('From now you are on my owners list, enjoy.');
     NICK_MSG('Owner Commands:');
     NICK_MSG($owner_commands);
	 NICK_MSG('User Commands:');
	 NICK_MSG($user_commands);

     CLI_MSG('New OWNER added, '.$GLOBALS['CONFIG_CNANNEL'].', added: '.$mask, '1');
	 CLI_MSG('New AUTO_OP added, '.$GLOBALS['CONFIG_CNANNEL'].', added: '.$mask, '1');
    
	 /* give op before restart */
     fputs($GLOBALS['socket'], 'MODE '.$GLOBALS['CONFIG_CNANNEL'].' +o '.$GLOBALS['nick']."\n");

     fputs($GLOBALS['socket'],"QUIT :Restarting...\n");
     CLI_MSG('Restarting BOT...', '1');
     system('START_BOT.BAT');
     die();

	 }
//---

 /* plugins commands */
	if(HasOwner($mask))
	{
		$pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);
		if(in_array($rawcmd[1], $GLOBALS['PLUGINS_OWNERS'])) { call_user_func('plugin_'.$pn); }
		if(in_array($rawcmd[1], $GLOBALS['PLUGINS_USERS'])) { call_user_func('plugin_'.$pn); }
	}
	else if(!HasOwner($mask))
	{
	  $pn = str_replace($GLOBALS['CONFIG_CMD_PREFIX'], '', $rawcmd[1]);
	  if(in_array($rawcmd[1], $GLOBALS['PLUGINS_USERS'])) { call_user_func('plugin_'.$pn); }
	}

   if(!function_exists('plugin_')) { function plugin_() { } }

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

  $owners_c = $GLOBALS['CONFIG_OWNERS'];
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
function CLI_MSG($msg, $log)
{
  $line = '[' . @date( 'H:i:s' ) . '] ' . $msg . "\r\n";

  if($GLOBALS['CONFIG_LOGGING'] == 'yes') 
	{
      if($log=='1') { SaveToFile($GLOBALS['log_file'], $line, 'a'); }
    }

  echo $line;
}
//------------------------------------------------------------------------------------------------
function BOT_RESPONSE($msg)
{
   switch($GLOBALS['CONFIG_BOT_RESPONSE']) {

	 case 'channel':
	 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['CONFIG_CNANNEL']." :$msg\n");
	 break;

	 case 'notice':
	 fputs($GLOBALS['socket'], 'NOTICE '.$GLOBALS['nick']." :$msg\n");
	 break;

	 case 'priv':
	 fputs($GLOBALS['socket'], 'PRIVMSG '.$GLOBALS['nick']." :$msg\n");	
	 break;
   }
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
function ErrorHandler($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    switch ($errno) {
    case E_USER_ERROR:
        CLI_MSG("My ERROR [$errno] $errstr", '1');
        CLI_MSG("Fatal error on line $errline in file $errfile, PHP".PHP_VERSION." (".PHP_OS.")", '1');
        CLI_MSG("Aborting...", '1');
        exit(1);
        break;

    case E_USER_WARNING:
        CLI_MSG("My WARNING [$errno] $errstr", '1');
        break;

    case E_USER_NOTICE:
        CLI_MSG("My NOTICE [$errno] $errstr", '1');
        break;

    default:
        CLI_MSG("Unknown error type: [$errno] $errstr", '1');
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
//------------------------------------------------------------------------------------------------
/* configuration file parser */
class iniParser {
	
 var $_iniFilename = '';
 var $_iniParsedArray = array();

function iniParser($file)
{
  $this->_iniFilename = $file;
  if($this->_iniParsedArray = parse_ini_file($file, true)) { return true; }
  else { return false; }
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

function save($file = null)
{
  if($file == null) $file = $this->_iniFilename;
   if(is_writeable($file)) {
	  $desc = fopen($file, "w");
	  foreach($this->_iniParsedArray as $sec => $array) {
	  fwrite($desc, "[" . $sec . "]\r\n" );
	  foreach($array as $key => $value) {
	  fwrite( $desc, "$key = '$value'\r\n" ); }
	  fwrite($desc, "\r\n");
	  }
		fclose($desc);
		return true;
	  } else { return false; }
 }
}
//------------------------------------------------------------------------------------------------
function CountLines($exts=array('php'))
{
  $fpath = '.'; $files=array(); 

  $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fpath));
  foreach($it as $file)
	  {
        if($file->isDir()) { continue; }
		$parts = explode('.', $file->getFilename());
		$extension=end($parts);
        if(in_array($extension, $exts))
        { $files[$file->getPathname()]=count(file($file->getPathname())); }
      } 
  return $files;
}
//------------------------------------------------------------------------------------------------
function TotalLines()
{
  return array_sum(CountLines());
}
//------------------------------------------------------------------------------------------------
?>