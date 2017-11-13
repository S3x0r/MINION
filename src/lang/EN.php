<?php

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}


/* English Language File */

define('TR_10', 'author:');
define('TR_11', 'contact:');
define('TR_12', 'Total Lines of code:');
define('TR_13', 'Default owner bot password detected!');
define('TR_14', 'For security please change it');
define('TR_15', 'New Password:');
define('TR_16', 'Password too short, password must be at least 6 characters long');
define('TR_17', 'Configuration Loaded from:');
define('TR_18', 'Configuration file missing!');
define('TR_19', 'Creating default config in:');
define('TR_20', 'Cannot make default config! Exiting');
define('TR_21', 'need to be configured');
define('TR_22', 'LOG CREATED:');
define('TR_23', 'Owner Plugins');
define('TR_24', 'User Plugins');
define('TR_25', 'Total:');
define('TR_26', 'port:');
define('TR_27', 'Connecting to:');
define('TR_28', 'Unable to connect to server, im trying to connect again..');
define('TR_29', 'Unable to connect to server, exiting program.');
define('TR_30', 'I was kicked from channel, joining again..');
define('TR_31', 'I have nick:');
define('TR_32', 'on auto op list, giving op');
define('TR_33', 'Nickname is reserved, changing nick to:');
define('TR_34', 'Register to bot by typing');
define('TR_35', 'Joining channel:');
define('TR_36', 'From now you are on my owners list, enjoy.');
define('TR_37', 'I recovered my original nickname');
define('TR_38', 'loaded.');
define('TR_39', 'unloaded.');
define('TR_40', 'Plugin:');
define('TR_41', 'Plugin already Loaded!');
define('TR_42', 'No such plugin to unload');
define('TR_43', 'New OWNER added');
define('TR_44', 'New AUTO_OP added');
define('TR_45', 'plugin_name');
define('TR_46', 'Usage');
define('TR_47', 'added:');
define('TR_48', 'by:');
define('TR_49', 'Function:');
define('TR_50', 'failed');
define('TR_51', 'server:');
define('TR_52', 'nickname:');
define('TR_53', 'channel:');
define('TR_54', 'Fatal error on line');
define('TR_55', 'in file');
define('TR_56', 'Aborting..');
define('TR_57', 'error type');
define('TR_58', 'OK im connected, my nickname is:');
define('TR_59', 'Owner Commands:');
define('TR_60', 'User Commands:');
define('TR_61', 'Plugin Loaded:');
define('TR_62', 'Bot cli commands usage: BOT.php [option]');
define('TR_63', '<config_file> loads config');
define('TR_64', '<password> hash password to SHA256');
define('TR_65', 'silent mode (no output from bot)');
define('TR_66', 'prints bot version');
define('TR_67', 'this help');
define('TR_68', 'I will hash your password to SHA256');
define('TR_69', 'Password:');
define('TR_70', 'Hashed:');
define('TR_71', 'MINION version:');
