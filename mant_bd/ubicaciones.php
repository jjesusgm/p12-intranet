﻿<?php require_once('../Connections/conP12.php'); ?>
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
$MM_authorizedUsers = "Administrador";
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rdUbicaciones = 20;
$pageNum_rdUbicaciones = 0;
if (isset($_GET['pageNum_rdUbicaciones'])) {
  $pageNum_rdUbicaciones = $_GET['pageNum_rdUbicaciones'];
}
$startRow_rdUbicaciones = $pageNum_rdUbicaciones * $maxRows_rdUbicaciones;

mysql_select_db($database_conP12, $conP12);
$query_rdUbicaciones = "SELECT * FROM p12_ubicaciones ORDER BY ub_nombre ASC";
$query_limit_rdUbicaciones = sprintf("%s LIMIT %d, %d", $query_rdUbicaciones, $startRow_rdUbicaciones, $maxRows_rdUbicaciones);
$rdUbicaciones = mysql_query($query_limit_rdUbicaciones, $conP12) or die(mysql_error());
$row_rdUbicaciones = mysql_fetch_assoc($rdUbicaciones);

if (isset($_GET['totalRows_rdUbicaciones'])) {
  $totalRows_rdUbicaciones = $_GET['totalRows_rdUbicaciones'];
} else {
  $all_rdUbicaciones = mysql_query($query_rdUbicaciones);
  $totalRows_rdUbicaciones = mysql_num_rows($all_rdUbicaciones);
}
$totalPages_rdUbicaciones = ceil($totalRows_rdUbicaciones/$maxRows_rdUbicaciones)-1;

$queryString_rdUbicaciones = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rdUbicaciones") == false && 
        stristr($param, "totalRows_rdUbicaciones") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rdUbicaciones = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rdUbicaciones = sprintf("&totalRows_rdUbicaciones=%d%s", $totalRows_rdUbicaciones, $queryString_rdUbicaciones);
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Mantenimiento a BD &gt; Uso general &gt; Ubicaciones</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Mostrando
registros <?php echo ($startRow_rdUbicaciones + 1) ?> al <?php echo min($startRow_rdUbicaciones + $maxRows_rdUbicaciones, $totalRows_rdUbicaciones) ?> de <?php echo $totalRows_rdUbicaciones ?> </h2>
  <h3 class="center"><a href="ubic_add.php" class="estilo3">Agregar una ubicación</a></h3>
    <table border="0" align="center">
      <tr>
        <td>Navegación:</td>
        <td><a href="<?php printf("%s?pageNum_rdUbicaciones=%d%s", $currentPage, 0, $queryString_rdUbicaciones); ?>" class="estilo3">Primera</a> | <a href="<?php printf("%s?pageNum_rdUbicaciones=%d%s", $currentPage, max(0, $pageNum_rdUbicaciones - 1), $queryString_rdUbicaciones); ?>" class="estilo3">Anterior</a> | <a href="<?php printf("%s?pageNum_rdUbicaciones=%d%s", $currentPage, min($totalPages_rdUbicaciones, $pageNum_rdUbicaciones + 1), $queryString_rdUbicaciones); ?>" class="estilo3">Siguiente</a> | <a href="<?php printf("%s?pageNum_rdUbicaciones=%d%s", $currentPage, $totalPages_rdUbicaciones, $queryString_rdUbicaciones); ?>" class="estilo3">&Uacute;ltima</a></td>
      </tr>
    </table>
  <table width="700" align="center" class="tabla_contactos">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">UBICACION</th>
      <th scope="col">ACCION</th>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_rdUbicaciones['id_ubicacion']; ?></td>
        <td><?php echo $row_rdUbicaciones['ub_nombre']; ?></td>
        <td align="center"><a href="ubic_edit.php?id_ubicacion=<?php echo $row_rdUbicaciones['id_ubicacion']; ?>" class="estilo3">Editar</a> | <a href="ubic_delete.php?id_ubicacion=<?php echo $row_rdUbicaciones['id_ubicacion']; ?>" class="estilo3">Eliminar</a></td>
      </tr>
      <?php } while ($row_rdUbicaciones = mysql_fetch_assoc($rdUbicaciones)); ?>
  </table>
  <table border="0" align="center">
    <tr>
      <td>Navegación:</td>
      <td><a href="<?php printf("%s?pageNum_rdUbicaciones=%d%s", $currentPage, 0, $queryString_rdUbicaciones); ?>" class="estilo3">Primera</a> | <a href="<?php printf("%s?pageNum_rdUbicaciones=%d%s", $currentPage, max(0, $pageNum_rdUbicaciones - 1), $queryString_rdUbicaciones); ?>" class="estilo3">Anterior</a> | <a href="<?php printf("%s?pageNum_rdUbicaciones=%d%s", $currentPage, min($totalPages_rdUbicaciones, $pageNum_rdUbicaciones + 1), $queryString_rdUbicaciones); ?>" class="estilo3">Siguiente</a> | <a href="<?php printf("%s?pageNum_rdUbicaciones=%d%s", $currentPage, $totalPages_rdUbicaciones, $queryString_rdUbicaciones); ?>" class="estilo3">&Uacute;ltimo</a></td>
    </tr>
  </table>
  <h3 class="center"><a href="ubic_add.php" class="estilo3">Agregar una ubicación</a></h3>
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
mysql_free_result($rdUbicaciones);
?>
