<html>
<head>

<title></title>

</head>

<body bgcolor="#7AC4CB">

<style type="text/css">
input:focus { 
    outline: none !important;
    border-color: #719ECE;
    box-shadow: 0 0 10px #719ECE;
}
textarea:focus { 
    outline: none !important;
    border-color: #719ECE;
    box-shadow: 0 0 10px #719ECE;
}
</style>

<script type="text/javascript">
function focus() {
setTimeout( function(){ try{
d = document.getElementById('user');
d.focus();
d.select();
} catch(e){}
}, 200);
}
focus();
</script>

<br>
<br>
<div align="center">
<p>
<img src="src/logo.png">
</p>
<?php
if (isset($_GET['s']) && $_GET['s'] == '1') {
    echo '<b>Wrong Login or Password</b>';
}
    
if (isset($_GET['d']) && $_GET['d'] == '1') {
    echo '<b>You need to change default login/password in config! No entry</b>';
}
?>
<form action="<?php echo $_SERVER['PHP_SELF'].'?p=l'; ?>" method="post" enctype="url-form-encoded">
<font face="Verdana">Login:</font>
<br>
<input enctype="url-form-encoded" type="text" name="user" id="user">
<br>
<p>
<font face="Verdana">Password:</font>
<br>
<input enctype="url-form-encoded" type="password" name="keypass" id="keypass">
</p>
<input type="submit" id="submit" value="Login">
</form>

<?php
    error_reporting(0);
    require 'api.php';
    $cfg = new IniParser('../web.ini');
    $username = $cfg->get('PANEL', 'web_login');
    $password = $cfg->get('PANEL', 'web_password');
    $salt     = $cfg->get('PANEL', 'web_salt');

if (isset($_GET['p']) && $_GET['p'] == "l") {
    if ($_POST['user'] == '' && $_POST['keypass'] == '') {
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    } elseif ($_POST['user'] != $username) {
              header('Location: '.$_SERVER['PHP_SELF'].'?s=1');
              exit;
    } elseif ($_POST['keypass'] != $password) {
              header('Location: '.$_SERVER['PHP_SELF'].'?s=1');
              exit;
    } elseif ($_POST['user'] == 'changeme' && $_POST['keypass'] == 'changeme') {
              header('Location: '.$_SERVER['PHP_SELF'].'?d=1');
              exit;
    } elseif ($_POST['user'] == $username && $_POST['keypass'] == $password) {
              setcookie('xs', hash('sha512', $_POST['keypass'].$salt));
              header('Location: main.php');
              exit;
    } else {
            echo '<h2>error</h2>';
    }
}
?>