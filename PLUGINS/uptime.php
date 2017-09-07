<?php
 
if(PHP_SAPI !== 'cli') { die('This script can\'t be run from a web browser. Use CLI to run it.'); }

 $plugin_description = 'Shows BOT uptime: !uptime';
 $plugin_command = 'uptime';

function plugin_uptime()
{
  $time = uptime_parse(microtime(true) - START_TIME);

  BOT_RESPONSE("I've been running since ".date('d.m.Y, H:i:s', START_TIME)." and been running for ".$time);

  CLI_MSG('!Uptime on: '.$GLOBALS['CONFIG_CNANNEL'].', by: '.$GLOBALS['nick'], '1');
}

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
  if(!empty($weeks) && $days > 0)
   $result .= $weeks . " week";
  if($weeks > 1)
   $result .= "s";
  if(!empty($days) && $days > 0)
   $result .= $days . " day";
  if($days > 1)
   $result .= "s";
  if(!empty($hours) && $hours > 0)
   $result .= $hours . " hour";
  if($hours > 1)
   $result .= "s";
  if(!empty($minutes) && $minutes > 0)
   $result .= " " . $minutes . " minute";
  if($minutes > 1)
   $result .= "s";
  if(!empty($seconds) && $seconds > 0)
   $result .= " " . $seconds . " second";
  if($seconds > 1)
   $result .= "s";
        
  return trim($result);
}
?>