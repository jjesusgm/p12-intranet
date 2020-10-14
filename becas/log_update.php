<?php require_once('../Connections/conBecas.php'); ?>
<?php 
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// INICIO: Actualizar en la base de datos de becas la última actualización hecha por el alumno
if(! $conBecas ) {
   die('No se pudo conectar: ' . mysql_error());
}

date_default_timezone_set('America/Mexico_City');
$user_id = $_GET['codigo'];
$current_date_time = date('Y-m-d H:i:s');

$sql = "UPDATE padron_bbbj ". "SET ult_act = '$current_date_time' ". 
   "WHERE codigo = '$user_id'" ;
mysql_select_db('becas');
$retval = mysql_query( $sql, $conBecas );

if(! $retval ) {
   die('No se pudo actualizar la informacion: ' . mysql_error());
}
echo "Informacion actualizada correctamente\n";

mysql_close($conBecas);
// FIN: Actualizar en la base de datos de becas la última actualización hecha por el alumno

// Redirigimos al usuario al archivo index.php
header('Location: index.php');
?>