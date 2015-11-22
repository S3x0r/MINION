@echo off
title davybot
del INSTALL.BAT
cd PHP
cls
php -c "php.ini" -f "../BOT.php"
