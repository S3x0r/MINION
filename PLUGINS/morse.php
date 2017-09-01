<?php
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Converts to morse code: !morse <text>';
 $plugin_command = 'morse';

function plugin_morse()
{

  if(empty($GLOBALS['args'])) { BOT_RESPONSE('Usage: '.$GLOBALS['C_CMD_PREFIX'].'morse <text>'); } 
  
  else {

   $morse_code = array('a'  =>  '.-', 'b'  =>  '-...', 'c'  =>  '-.-.', 'd'  =>  '-..', 'e'  =>  '.',
                       'f'  =>  '..-.', 'g'  =>  '--.', 'h'  =>  '....', 'i'  =>  '..', 'j'  =>  '.---',
                       'k'  =>  '-.-', 'l'  =>  '.-..', 'm'  =>  '--', 'n'  =>  '-.', 'o'  =>  '---',
                       'p'  =>  '.--.', 'q'  =>  '--.-', 'r'  =>  '.-.', 's'  =>  '...', 't'  =>  '-',
                       'u'  =>  '..-', 'v'  =>  '...-', 'w'  =>  '.--', 'x'  =>  '-..-', 'y'  =>  '-.--',
                       'z'  =>  '--..', '0'  =>  '-----', '1'  =>  '.----', '2'  =>  '..---', '3'  =>  '...--',
                       '4'  =>  '....-', '5'  =>  '.....', '6'  =>  '-....', '7'  =>  '--...', '8'  =>  '---..',
                       '9'  =>  '----.', '.'  =>  '.-.-.-', ','  =>  '--..--', '?'  =>  '..--..', '\''  =>  '.----.',
                       '!'  =>  '-.-.--', '/'  =>  '-..-.', '-'  =>  '-....-', '"'  =>  '.-..-.', '('  =>  '-.--.-',
                       ')'  =>  '-.--.-', ' '  =>  '/',);

   $string = strtolower(msg_without_command());
   $len = strlen($string);
   $final = NULL;
   for($pos = 0; $pos < $len; $pos++) 
	   {
         $care = $string[$pos];
         if(array_key_exists($care, $morse_code))
			 {
               $final .= $morse_code[$care]." ";
             }
       }
   BOT_RESPONSE($string.' converted to morse: '.rtrim($final));
   CLI_MSG('!morse on: '.$GLOBALS['C_CNANNEL'].', by: '.$GLOBALS['nick'], '1');

 }
}

?>