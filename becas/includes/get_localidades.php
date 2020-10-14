<?php require_once('../../Connections/conBecas.php'); ?>
<?php mysql_set_charset('utf8'); ?>
<?php
$default_estado = "";
$default_municipio = "";
if(empty($_GET['id_estado']) || !isset($_GET['id_estado']) || is_null($_GET['id_estado'])){
	$_GET['id_estado'] = "14";
	$default_estado = "14";
}
if(empty($_GET['id_municipio']) || !isset($_GET['id_municipio']) || is_null($_GET['id_municipio'])){
	$_GET['id_municipio'] = "039";
	$default_municipio = "039";
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colEstado_rsLocalidades = "14";
if (isset($_GET['id_estado'])) {
  $colEstado_rsLocalidades = $_GET['id_estado'];
}
$colMunicipio_rsLocalidades = "039";
if (isset($_GET['id_municipio'])) {
  $colMunicipio_rsLocalidades = $_GET['id_municipio'];
}
mysql_select_db($database_conBecas, $conBecas);
$query_rsLocalidades = sprintf("SELECT * FROM localidades WHERE id_estado = %s AND id_municipio = %s ORDER BY nom_localidad ASC", GetSQLValueString($colEstado_rsLocalidades, "text"),GetSQLValueString($colMunicipio_rsLocalidades, "text"));
$rsLocalidades = mysql_query($query_rsLocalidades, $conBecas) or die(mysql_error());
$row_rsLocalidades = mysql_fetch_assoc($rsLocalidades);
$totalRows_rsLocalidades = mysql_num_rows($rsLocalidades);
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<?php
//echo "<br>QUERY: ".$query_rsLocalidades;
//echo "<br>ESTADO DEFAULT: ".$default_estado;
//echo "<br>MUNICIPIO DEFAULT: ".$default_municipio;
?>
<select name="fClaLoc" id="fClaLoc" title="Localidad del domicilio del estudiante">
  <?php
do {  
?>
  <option value="<?php echo $row_rsLocalidades['id_localidad']?>"<?php if (!(strcmp($row_rsLocalidades['id_localidad'], $_GET['id_localidad']))) {echo " selected";} ?>><?php echo $row_rsLocalidades['nom_localidad']?></option>
  <?php
} while ($row_rsLocalidades = mysql_fetch_assoc($rsLocalidades));
  $rows = mysql_num_rows($rsLocalidades);
  if($rows > 0) {
      mysql_data_seek($rsLocalidades, 0);
	  $row_rsLocalidades = mysql_fetch_assoc($rsLocalidades);
  }
?>
</select>
</body>
</html>
<?php
mysql_free_result($rsLocalidades);
?>
