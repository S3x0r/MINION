<?php

    error_reporting(0);
    require 'api.php';
    $cfg = new IniParser('../web.ini');
    $username = $cfg->get('PANEL', 'web.login');
    $password = $cfg->get('PANEL', 'web.password');
    $salt     = $cfg->get('PANEL', 'web.salt');

if (isset($_COOKIE['xs']) && $_COOKIE['xs'] == hash('sha512', $password.$salt)) {
    unset($_COOKIE['xs']);
    setcookie('xs', '', time() - 3600);
    header('Location: index.php');
} else {
         exit;
}
