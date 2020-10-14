<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conBecas = "localhost";
$database_conBecas = "becas";
$username_conBecas = "becas";
$password_conBecas = "p12Becas";
$conBecas = mysql_pconnect($hostname_conBecas, $username_conBecas, $password_conBecas) or trigger_error(mysql_error(),E_USER_ERROR); 
?>