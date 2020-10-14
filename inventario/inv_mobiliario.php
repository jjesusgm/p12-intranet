<?php require_once('../Connections/conP12.php'); ?>
<?php mysql_set_charset('utf8'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Administrador,Soporte";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../pagina_restringida.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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

$currentPage = $_SERVER["PHP_SELF"];

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

mysql_select_db($database_conP12, $conP12);
$query_rsMarcas = "SELECT * FROM inv_mobiliario_marcas ORDER BY marca ASC";
$rsMarcas = mysql_query($query_rsMarcas, $conP12) or die(mysql_error());
$row_rsMarcas = mysql_fetch_assoc($rsMarcas);
$totalRows_rsMarcas = mysql_num_rows($rsMarcas);

mysql_select_db($database_conP12, $conP12);
$query_rsRsguardantes = "SELECT username, nivel_de_acceso, nombre_completo FROM usuarios WHERE nivel_de_acceso IN('Administrador','Administrativo','Profesor','Soporte') ORDER BY nombre_completo ASC";
$rsRsguardantes = mysql_query($query_rsRsguardantes, $conP12) or die(mysql_error());
$row_rsRsguardantes = mysql_fetch_assoc($rsRsguardantes);
$totalRows_rsRsguardantes = mysql_num_rows($rsRsguardantes);

mysql_select_db($database_conP12, $conP12);
$query_rsTipos = "SELECT * FROM inv_mobiliario_tipos ORDER BY tipo_mobiliario ASC";
$rsTipos = mysql_query($query_rsTipos, $conP12) or die(mysql_error());
$row_rsTipos = mysql_fetch_assoc($rsTipos);
$totalRows_rsTipos = mysql_num_rows($rsTipos);

mysql_select_db($database_conP12, $conP12);
$query_rsMateriales = "SELECT * FROM inv_mobiliario_material ORDER BY material ASC";
$rsMateriales = mysql_query($query_rsMateriales, $conP12) or die(mysql_error());
$row_rsMateriales = mysql_fetch_assoc($rsMateriales);
$totalRows_rsMateriales = mysql_num_rows($rsMateriales);

mysql_select_db($database_conP12, $conP12);
$query_rsColores = "SELECT * FROM inv_mobiliario_colores ORDER BY color ASC";
$rsColores = mysql_query($query_rsColores, $conP12) or die(mysql_error());
$row_rsColores = mysql_fetch_assoc($rsColores);
$totalRows_rsColores = mysql_num_rows($rsColores);

mysql_select_db($database_conP12, $conP12);
$query_rsUbicaciones = "SELECT * FROM p12_ubicaciones ORDER BY ub_nombre ASC";
$rsUbicaciones = mysql_query($query_rsUbicaciones, $conP12) or die(mysql_error());
$row_rsUbicaciones = mysql_fetch_assoc($rsUbicaciones);
$totalRows_rsUbicaciones = mysql_num_rows($rsUbicaciones);

$maxRows_rsInvMobiliario = 30;
$pageNum_rsInvMobiliario = 0;
if (isset($_GET['pageNum_rsInvMobiliario'])) {
  $pageNum_rsInvMobiliario = $_GET['pageNum_rsInvMobiliario'];
}
$startRow_rsInvMobiliario = $pageNum_rsInvMobiliario * $maxRows_rsInvMobiliario;

$varIdMarca_rsInvMobiliario = "%";
if (isset($_POST['fIdMarca'])) {
  $varIdMarca_rsInvMobiliario = $_POST['fIdMarca'];
}
$varIdUbicacion_rsInvMobiliario = "%";
if (isset($_POST['fIdUbicacion'])) {
  $varIdUbicacion_rsInvMobiliario = $_POST['fIdUbicacion'];
}
$varNumeroInvUdg_rsInvMobiliario = "%";
if (isset($_POST['fNumeroInvUDG'])) {
  $varNumeroInvUdg_rsInvMobiliario = $_POST['fNumeroInvUDG'];
}
$varNumeroSerie_rsInvMobiliario = "%";
if (isset($_POST['fNumeroSerie'])) {
  $varNumeroSerie_rsInvMobiliario = $_POST['fNumeroSerie'];
}
$varIdColor_rsInvMobiliario = "%";
if (isset($_POST['fIdColor'])) {
  $varIdColor_rsInvMobiliario = $_POST['fIdColor'];
}
$varIdMaterial_rsInvMobiliario = "%";
if (isset($_POST['fIdMaterial'])) {
  $varIdMaterial_rsInvMobiliario = $_POST['fIdMaterial'];
}
$varIdTipo_rsInvMobiliario = "%";
if (isset($_POST['fIdTipo'])) {
  $varIdTipo_rsInvMobiliario = $_POST['fIdTipo'];
}
$varIdResguardante_rsInvMobiliario = "%";
if (isset($_POST['fIdResguardante'])) {
  $varIdResguardante_rsInvMobiliario = $_POST['fIdResguardante'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsInvMobiliario = sprintf("SELECT im.*, mrk.marca, usr.nombre_completo, tm.tipo_mobiliario, mat.material, col.color FROM inv_mobiliario im INNER JOIN inv_mobiliario_marcas mrk ON im.id_marca = mrk.id_marca INNER JOIN usuarios usr ON im.id_resguardante = usr.username INNER JOIN inv_mobiliario_tipos tm ON im.id_tipo = tm.id_tipo_mobiliario INNER JOIN inv_mobiliario_material mat ON im.id_material = mat.id_material INNER JOIN inv_mobiliario_colores col ON im.id_color = col.id_color WHERE im.id_marca LIKE %s AND im.id_resguardante LIKE %s AND im.id_tipo LIKE %s AND im.id_material LIKE %s AND im.id_color LIKE %s AND im.numero_serie LIKE %s AND im.numero_inv_udg LIKE %s AND im.id_ubicacion LIKE %s ORDER BY id_mobiliario ASC", GetSQLValueString($varIdMarca_rsInvMobiliario, "text"),GetSQLValueString($varIdResguardante_rsInvMobiliario, "text"),GetSQLValueString($varIdTipo_rsInvMobiliario, "text"),GetSQLValueString($varIdMaterial_rsInvMobiliario, "text"),GetSQLValueString($varIdColor_rsInvMobiliario, "text"),GetSQLValueString($varNumeroSerie_rsInvMobiliario, "text"),GetSQLValueString($varNumeroInvUdg_rsInvMobiliario, "text"),GetSQLValueString($varIdUbicacion_rsInvMobiliario, "text"));
$query_limit_rsInvMobiliario = sprintf("%s LIMIT %d, %d", $query_rsInvMobiliario, $startRow_rsInvMobiliario, $maxRows_rsInvMobiliario);
$rsInvMobiliario = mysql_query($query_limit_rsInvMobiliario, $conP12) or die(mysql_error());
$row_rsInvMobiliario = mysql_fetch_assoc($rsInvMobiliario);

if (isset($_GET['totalRows_rsInvMobiliario'])) {
  $totalRows_rsInvMobiliario = $_GET['totalRows_rsInvMobiliario'];
} else {
  $all_rsInvMobiliario = mysql_query($query_rsInvMobiliario);
  $totalRows_rsInvMobiliario = mysql_num_rows($all_rsInvMobiliario);
}
$totalPages_rsInvMobiliario = ceil($totalRows_rsInvMobiliario/$maxRows_rsInvMobiliario)-1;

$queryString_rsInvMobiliario = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsInvMobiliario") == false && 
        stristr($param, "totalRows_rsInvMobiliario") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsInvMobiliario = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsInvMobiliario = sprintf("&totalRows_rsInvMobiliario=%d%s", $totalRows_rsInvMobiliario, $queryString_rsInvMobiliario);
?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/t_intranet_p12.dwt" codeOutsideHTMLIsLocked="false" -->
<!-- InstanceParam name="body" type="text" value="" -->
<!-- InstanceParam name="focusFormElement" type="text" value="" -->
<!-- InstanceBeginEditable name="RegionHead" -->
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="author" content="Jose de Jesus Gutierrez Martinez" />
<title>Intranet P12</title>
<script language="javascript" type="text/javascript">
function valida(f){
	if(!f.fNumeroSerie.value){
		f.fNumeroSerie.value = "%";
	}else{
		f.fNumeroSerie.value = "%"+f.fNumeroSerie.value+"%";
	}
	if(!f.fNumeroInvUDG.value){
		f.fNumeroInvUDG.value = "%";
	}else{
		f.fNumeroInvUDG.value = "%"+f.fNumeroInvUDG.value+"%";
	}
	return true;
}
</script>
<style type="text/css">
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<link href="../css/divs.css" rel="stylesheet" type="text/css" />
<link href="../css/enlaces.css" rel="stylesheet" type="text/css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<link href="../css/imagenes.css" rel="stylesheet" type="text/css" />
<link href="../css/menu1.css" rel="stylesheet" type="text/css" />
<link href="../css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/varios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/menus.js"></script>
</head>
<!-- InstanceEndEditable -->
<body onload="">
<div id="div_hdr_links">
<?php if(isset($_SESSION['MM_Username'])){?>
Bienvenido, <strong><?php echo $_SESSION['MM_Username']; ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $logoutAction; ?>">Cerrar sesion</a>
<?php }else{ ?>
Ninguna sesion iniciada&nbsp;&nbsp;&nbsp;&nbsp;<a href="../login.php">Iniciar sesion</a>
<?php } ?></div>
<div id="div_hdr_logo"><a href="http://www.udg.mx/" target="_blank"><img src="../imagenes/logo.jpg" alt="Banner Red Universitaria de Jaliasco" width="350" height="82" class="opaca" /></a></div>
<div id="div_hdr_sitename">&nbsp;&nbsp;&nbsp;<a href="http://www.prepa12.sems.udg.mx/" class="estilo1">Intranet de la Escuela Preparatoria 12</a></div>
<!-- InstanceBeginEditable name="MenuPrincipal" -->
<div id="div_hdr_menu">
<script language="javascript" type="text/javascript">muestraMenuMain("<?php if(isset($_SESSION['MM_UserGroup'])){echo $_SESSION['MM_UserGroup'];}else {echo '';} ?>", "../", "");</script>
</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Path" -->
<div id="div_hdr_path">&nbsp;Inicio &gt; Inventario &gt; Mobiliario</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
<h2 class="center">Mostrando Mobiliario
 <?php echo ($startRow_rsInvMobiliario + 1) ?> al <?php echo min($startRow_rsInvMobiliario + $maxRows_rsInvMobiliario, $totalRows_rsInvMobiliario) ?> de <?php echo $totalRows_rsInvMobiliario ?> </h2>
<div class="div_filtro">
  <form id="form1" name="form1" method="post" action="" onsubmit="return valida(this)">
    <fieldset>
      <legend>Filtrar resultados por:</legend>
      <table width="100%">
        <tr>
          <td align="right"><label for="fIdMarca">Marca</label></td>
          <td><select name="fIdMarca" id="fIdMarca">
            <option value="%">Todas</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsMarcas['id_marca']?>"><?php echo $row_rsMarcas['marca']?></option>
            <?php
} while ($row_rsMarcas = mysql_fetch_assoc($rsMarcas));
  $rows = mysql_num_rows($rsMarcas);
  if($rows > 0) {
      mysql_data_seek($rsMarcas, 0);
	  $row_rsMarcas = mysql_fetch_assoc($rsMarcas);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td align="right"><label for="fIdResguardante">Resguardante</label></td>
          <td><select name="fIdResguardante" id="fIdResguardante">
            <option value="%">Todos</option>
            <option value="Sin asignar">Sin asignar</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsRsguardantes['username']?>"><?php echo $row_rsRsguardantes['nombre_completo']?></option>
            <?php
} while ($row_rsRsguardantes = mysql_fetch_assoc($rsRsguardantes));
  $rows = mysql_num_rows($rsRsguardantes);
  if($rows > 0) {
      mysql_data_seek($rsRsguardantes, 0);
	  $row_rsRsguardantes = mysql_fetch_assoc($rsRsguardantes);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td align="right"><label for="fIdTipo">Tipo</label></td>
          <td><select name="fIdTipo" id="fIdTipo">
            <option value="%">Todos</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsTipos['id_tipo_mobiliario']?>"><?php echo $row_rsTipos['tipo_mobiliario']?></option>
            <?php
} while ($row_rsTipos = mysql_fetch_assoc($rsTipos));
  $rows = mysql_num_rows($rsTipos);
  if($rows > 0) {
      mysql_data_seek($rsTipos, 0);
	  $row_rsTipos = mysql_fetch_assoc($rsTipos);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td align="right"><label for="fIdMaterial">Material</label></td>
          <td><select name="fIdMaterial" id="fIdMaterial">
            <option value="%">Todos</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsMateriales['id_material']?>"><?php echo $row_rsMateriales['material']?></option>
            <?php
} while ($row_rsMateriales = mysql_fetch_assoc($rsMateriales));
  $rows = mysql_num_rows($rsMateriales);
  if($rows > 0) {
      mysql_data_seek($rsMateriales, 0);
	  $row_rsMateriales = mysql_fetch_assoc($rsMateriales);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td align="right"><label for="fIdColor">Color</label></td>
          <td><select name="fIdColor" id="fIdColor">
            <option value="%">Todos</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsColores['id_color']?>"><?php echo $row_rsColores['color']?></option>
            <?php
} while ($row_rsColores = mysql_fetch_assoc($rsColores));
  $rows = mysql_num_rows($rsColores);
  if($rows > 0) {
      mysql_data_seek($rsColores, 0);
	  $row_rsColores = mysql_fetch_assoc($rsColores);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td align="right"><label for="fNumeroSerie">No. de serie</label></td>
          <td><input name="fNumeroSerie" type="text" id="fNumeroSerie" size="20" maxlength="20" /></td>
        </tr>
        <tr>
          <td align="right"><label for="fNumeroInvUDG">No. inventario UdeG</label></td>
          <td><input name="fNumeroInvUDG" type="text" id="fNumeroInvUDG" size="20" maxlength="20" /></td>
        </tr>
        <tr>
          <td align="right"><label for="fIdUbicacion">Ubicación</label></td>
          <td><select name="fIdUbicacion" id="fIdUbicacion">
            <option value="%">Todas</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsUbicaciones['id_ubicacion']?>"><?php echo $row_rsUbicaciones['ub_nombre']?></option>
            <?php
} while ($row_rsUbicaciones = mysql_fetch_assoc($rsUbicaciones));
  $rows = mysql_num_rows($rsUbicaciones);
  if($rows > 0) {
      mysql_data_seek($rsUbicaciones, 0);
	  $row_rsUbicaciones = mysql_fetch_assoc($rsUbicaciones);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="submit" name="fAgregarFiltro" id="fAgregarFiltro" value="Agregar filtro" /></td>
          </tr>
      </table>
    </fieldset>
  </form>
</div>
<h3 class="center"><a href="inv_mob_add.php" class="estilo3">Agregar nuevo Mobiliario</a></h3>
<table align="center">
  <tr>
    <td>Navegación:</td>
    <td><a href="<?php printf("%s?pageNum_rsInvMobiliario=%d%s", $currentPage, 0, $queryString_rsInvMobiliario); ?>" class="estilo3">Primera</a> | <a href="<?php printf("%s?pageNum_rsInvMobiliario=%d%s", $currentPage, max(0, $pageNum_rsInvMobiliario - 1), $queryString_rsInvMobiliario); ?>" class="estilo3">Anterior</a> | <a href="<?php printf("%s?pageNum_rsInvMobiliario=%d%s", $currentPage, min($totalPages_rsInvMobiliario, $pageNum_rsInvMobiliario + 1), $queryString_rsInvMobiliario); ?>" class="estilo3">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsInvMobiliario=%d%s", $currentPage, $totalPages_rsInvMobiliario, $queryString_rsInvMobiliario); ?>" class="estilo3">Ultima</a></td>
  </tr>
</table>
<table width="100%" class="TablaListaInventario">
  <tr>
    <th>ID</th>
    <th>MARCA</th>
    <th>RESGUARDANTE</th>
    <th>TIPO</th>
    <th>MATERIAL</th>
    <th>COLOR</th>
    <th>No. SERIE</th>
    <th>No. INV</th>
    <th>DESCRIPCION</th>
    <th>UBICACION</th>
    <th>ACCION</th>
  </tr>
  <?php do { ?>
    <tr align="center">
      <td><?php echo $row_rsInvMobiliario['id_mobiliario']; ?></td>
      <td><?php echo $row_rsInvMobiliario['marca']; ?></td>
      <td><?php echo $row_rsInvMobiliario['id_resguardante']; ?>&nbsp;<a href="#" class="NtooltipSmall VAlignMiddle"><img src="../imagenes/ver.gif" width="12" height="12" alt="Ver nombre" /><span><?php echo $row_rsInvMobiliario['nombre_completo']; ?></span></a></td>
      <td><?php echo $row_rsInvMobiliario['tipo_mobiliario']; ?></td>
      <td><?php echo $row_rsInvMobiliario['material']; ?></td>
      <td><?php echo $row_rsInvMobiliario['color']; ?></td>
      <td><?php echo $row_rsInvMobiliario['numero_serie']; ?></td>
      <td><?php echo $row_rsInvMobiliario['numero_inv_udg']; ?></td>
      <td align="left"><?php echo $row_rsInvMobiliario['descripcion']; ?></td>
      <td><?php echo $row_rsInvMobiliario['id_ubicacion']; ?></td>
      <td><a href="inv_mob_edit.php?id_mobiliario=<?php echo $row_rsInvMobiliario['id_mobiliario']; ?>">Editar</a> | <a href="inv_mob_delete.php?id_mobiliario=<?php echo $row_rsInvMobiliario['id_mobiliario']; ?>">Eliminar</a></td>
    </tr>
    <?php } while ($row_rsInvMobiliario = mysql_fetch_assoc($rsInvMobiliario)); ?>
</table>
<table align="center">
  <tr>
    <td>Navegación:</td>
    <td><a href="<?php printf("%s?pageNum_rsInvMobiliario=%d%s", $currentPage, 0, $queryString_rsInvMobiliario); ?>" class="estilo3">Primera</a> | <a href="<?php printf("%s?pageNum_rsInvMobiliario=%d%s", $currentPage, max(0, $pageNum_rsInvMobiliario - 1), $queryString_rsInvMobiliario); ?>" class="estilo3">Anterior</a> | <a href="<?php printf("%s?pageNum_rsInvMobiliario=%d%s", $currentPage, min($totalPages_rsInvMobiliario, $pageNum_rsInvMobiliario + 1), $queryString_rsInvMobiliario); ?>" class="estilo3">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsInvMobiliario=%d%s", $currentPage, $totalPages_rsInvMobiliario, $queryString_rsInvMobiliario); ?>" class="estilo3">Ultima</a></td>
  </tr>
</table>
<h3 class="center"><a href="inv_mob_add.php" class="estilo3">Agregar nuevo Mobiliario</a></h3>
</div>
<!-- InstanceEndEditable -->
<div id="div_footer">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <th scope="col">&nbsp;</th>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="2%">&nbsp;</td>
              <td colspan="2"><img src="../imagenes/footer_red_universitaria_jalisco.png" width="329" height="51" alt="Banner Gris Red Universitaria de Jalisco" /></td>
              </tr>
            <tr>
              <th width="2%" align="left">&nbsp;</th>
              <th width="4%" align="left">&nbsp;</th>
              <th align="left">ESCUELA PREPARATORIA 12</th>
            </tr>
            <tr>
              <td width="2%">&nbsp;</td>
              <td width="4%">&nbsp;</td>
              <td>Corregidora No. 500 (calle 40), C.P. 44420, Guadalajara, Jalisco, México.</td>
            </tr>
            <tr>
              <td width="2%">&nbsp;</td>
              <td width="4%">&nbsp;</td>
              <td>Teléfono(s): (33) 3617-1980, 3617-1870</td>
            </tr>
          </table></td>
          <td valign="top"><!-- InstanceBeginEditable name="MenuFooterRegion" -->
<script language="javascript" type="text/javascript">muestraMenuFooter("<?php if(isset($_SESSION['MM_UserGroup'])){echo $_SESSION['MM_UserGroup'];}else {echo '';} ?>", "../", "");</script>
		  <!-- InstanceEndEditable -->
          </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">Derechos reservados ©1997 - 2019. Universidad de Guadalajara. Sitio desarrollado por <a href="https://www.facebook.com/jjesusgm" target="_blank">JJGM</a> | <a href="../creditos_del_sitio.php">Créditos de sitio</a> | <a href="../ppmd.php">Política de privacidad y manejo de datos</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsInvMobiliario);

mysql_free_result($rsMarcas);

mysql_free_result($rsRsguardantes);

mysql_free_result($rsTipos);

mysql_free_result($rsMateriales);

mysql_free_result($rsColores);

mysql_free_result($rsUbicaciones);
?>
