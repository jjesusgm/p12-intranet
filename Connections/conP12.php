<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conP12 = "localhost";
$database_conP12 = "prepa12";
$username_conP12 = "prepa12";
$password_conP12 = "p12.WebApp";
$conP12 = mysql_pconnect($hostname_conP12, $username_conP12, $password_conP12) or trigger_error(mysql_error(),E_USER_ERROR); 
?>