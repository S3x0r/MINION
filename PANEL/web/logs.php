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
          <h1><a href="main.php">davybot - Admin Panel</a></h1>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <li><a href="main.php">Index</a></li>
          <li><a href="plugins.php">Plugins</a></li>
          <li class="selected"><a href="logs.php">Logs</a></li>
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
<h3>Log Files:</h3>
<br>

<?php

 $path = "../../LOGS/";
 $files = glob($path . "*.TXT");

foreach ($files as $file) {
    echo '<h4><a href=\logs\\'.$file.'>'.basename($file).'</a></h4><br>';
}

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
         die();
}
?>
