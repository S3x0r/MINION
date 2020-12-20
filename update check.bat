@echo off
title MINION - UPDATE CHECK
cd src
cd PHP
php -c "php.ini" ../../BOT.php -u
pause