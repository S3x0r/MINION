<?php
    error_reporting(0);
    require 'api.php';
    $cfg = new IniParser('../web.ini');
    $username = $cfg->get('PANEL', 'web_login');
    $password = $cfg->get('PANEL', 'web_password');
    $salt     = $cfg->get('PANEL', 'web_salt');

if (isset($_COOKIE['xs']) && $_COOKIE['xs'] == hash('sha512', $password.$salt)) {
?>

<!DOCTYPE HTML>
<html>

<head>
  <title></title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="src/style.css" title="style" />
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <h1><a href="main.php">MINION - Admin Panel</a></h1>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <li class="selected"><a href="main.php">Index</a></li>
          <li><a href="plugins.php">Plugins</a></li>
          <li><a href="logs.php">Logs</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div class="sidebar">
     <!-- right -->
      </div>
      <div id="content">
<!-- left -->
<br><br>
<?php

 GetDataFromBotConfig();
 GetAllData();

 $time = uptime_parse(microtime(true) - $GLOBALS['WEB_START_TIME']);

 echo '<h3>Bot version:<br>'.$GLOBALS['WEB_VERSION'].'</h3><br>';
 echo '<h3>Php version:<br>'.$GLOBALS['WEB_PHP_VERSION'].'</h3><br>';
 echo '<h3>Uptime:<br>';
 echo "<h4>Running since (".date('d.m.Y, H:i:s', $GLOBALS['WEB_START_TIME']).
      ") and been running for ".$time.'</h4></h3><br>';
 echo '<h3>Bot admin:<br>';
 echo '<h4>'.$GLOBALS['CONFIG_BOT_ADMIN'].'</h4></h3><br>';
 echo '<h3>Bot owners:<br>';
 echo '<h4>'.$GLOBALS['CONFIG_OWNERS'].'</h4></h3><br>';

?>
      </div>
    </div>
    <div id="footer">
    </div>
  </div>
</body>
</html>

<?php
} else {
         exit;
}
?>
