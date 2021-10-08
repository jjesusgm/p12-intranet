<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conMiscelanea = "localhost";
$database_conMiscelanea = "miscelanea";
$username_conMiscelanea = "miscelanea";
$password_conMiscelanea = "aw21qs";
$conMiscelanea = mysql_pconnect($hostname_conMiscelanea, $username_conMiscelanea, $password_conMiscelanea) or trigger_error(mysql_error(),E_USER_ERROR); 
?>