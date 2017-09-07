<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Checks ip or host address and shows results: !ripe <ip or host>';
 $plugin_command = 'ripe';


function plugin_ripe()
{
  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['CONFIG_CMD_PREFIX'].'ripe <ip or host>'); } 
  
  else { 
	     BOT_RESPONSE(ripe_check_ip($GLOBALS['args']));
         CLI_MSG('!ripe on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'].', address: '.$GLOBALS['args'], '1');
	   }
}

function ripe_check_ip($args) 
{
  if(!filter_var($args, FILTER_VALIDATE_IP) === false) {
  $result = json_decode(file_get_contents("https://stat.ripe.net/data/whois/data.json?resource=".urlencode($args)."/32"),true);
   if($result["status"]=!"ok") {
    $returnstring = 'Cannot check, no connection to server';
    } elseif(count($result["data"]["records"])==0) {
      $returnstring = 'Error, no results';
    } else {
            $data = 'IP-block: ';
            foreach($result["data"]["records"][0] as $record) {
                switch($record["key"]) {
                    case 'inetnum':
                    case 'netname':
                    case 'descr':
                    case 'country':
                    $data .= "[".$record["value"]."] ";        
                    default:
                } 
            }
            foreach($result["data"]["irr_records"][0] as $record) {
                switch($record["key"]) {
                    case 'origin':
                        $data .= "| Network: AS".$record["value"]." |";
                    default:
                }
            }
            $returnstring = "Info about ".$args.": ".$data." rDNS: ".gethostbyaddr($args);    
        }
    } else {
        $ip = gethostbyname($args);
        if(!filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return ripe_check_ip($ip);
        } else {
            $returnstring = 'Cannot resolve, Enter valid ip or address!';
        }
    }
    return $returnstring;
}

?>