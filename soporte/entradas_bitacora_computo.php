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
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../login.php";
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

$colname_rsEquipoComputo = "-1";
if (isset($_GET['id_dispositivo'])) {
  $colname_rsEquipoComputo = $_GET['id_dispositivo'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsEquipoComputo = sprintf("SELECT * FROM inv_computo WHERE id_dispositivo = %s", GetSQLValueString($colname_rsEquipoComputo, "int"));
$rsEquipoComputo = mysql_query($query_rsEquipoComputo, $conP12) or die(mysql_error());
$row_rsEquipoComputo = mysql_fetch_assoc($rsEquipoComputo);
$totalRows_rsEquipoComputo = mysql_num_rows($rsEquipoComputo);

$colname_rsEntradasBitacora = "-1";
if (isset($_GET['id_dispositivo'])) {
  $colname_rsEntradasBitacora = $_GET['id_dispositivo'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsEntradasBitacora = sprintf("SELECT * FROM bitacoras_computo WHERE id_dispositivo = %s ORDER BY fecha ASC", GetSQLValueString($colname_rsEntradasBitacora, "int"));
$rsEntradasBitacora = mysql_query($query_rsEntradasBitacora, $conP12) or die(mysql_error());
$row_rsEntradasBitacora = mysql_fetch_assoc($rsEntradasBitacora);
$totalRows_rsEntradasBitacora = mysql_num_rows($rsEntradasBitacora);
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Soporte &gt; Bitácoras de cómputo &gt; Entradas bitácora</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <table width="90%" align="center">
    <tr>
      <td><h1 class="H_Estilo1">Entradas de la bitácora</h1></td>
    </tr>
    <tr>
      <td><table width="100%" class="tabla_usuario">
        <tr>
          <td colspan="4" align="center"><h1 class="margin_none">Escuela Preparatoria No. 12</h1></td>
          </tr>
        <tr>
          <td colspan="4" align="center"><h3 class="margin_none">Unidad de Soporte a Sistemas de Cómputo</h3></td>
          </tr>
        <tr>
          <td colspan="4" align="center"><h3 class="margin_none">Bitácora de Mantenimiento de Equipo de Cómputo</h3></td>
          </tr>
        <tr>
          <td width="25%" align="right">Equipo:</td>
          <td width="25%"><?php echo $row_rsEquipoComputo['nombre_dispositivo']; ?></td>
          <td width="25%" align="right">No. de inventario:</td>
          <td width="25%"><?php echo $row_rsEquipoComputo['numero_inv_udg']; ?></td>
        </tr>
        <tr>
          <td width="25%" align="right">Ubicación:</td>
          <td width="25%"><?php echo $row_rsEquipoComputo['id_ubicacion']; ?></td>
          <td width="25%" align="right">No. de serie:</td>
          <td width="25%"><?php echo $row_rsEquipoComputo['numero_serie']; ?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td align="center"><h3 class="margin_both"><a href="entrada_bitacora_computo_add.php?id_dispositivo=<?php echo $row_rsEquipoComputo['id_dispositivo']; ?>" class="estilo3">Agregar entrada de bitácora</a></h3></td>
    </tr>
    <?php if ($totalRows_rsEntradasBitacora > 0) { // Show if recordset not empty ?>
    <tr>
      <td><table width="100%" class="TablaListaInventario">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Tipo de mantenimiento</th>
            <th width="40%" scope="col">Descripción del mantenimiento</th>
            <th scope="col">Refacción utilizada</th>
            <th scope="col">Fecha</th>
            <th scope="col">Fecha próximo mantenimiento</th>
          </tr>
<?php do { ?>
          <tr>
            <td align="center"><?php echo $row_rsEntradasBitacora['id_bitacora']; ?></td>
            <td align="center"><?php echo $row_rsEntradasBitacora['tipo_mant']; ?></td>
            <td width="40%"><?php echo $row_rsEntradasBitacora['descripcion']; ?></td>
            <td align="center"><?php echo $row_rsEntradasBitacora['refaccion']; ?></td>
            <td align="center"><?php echo $row_rsEntradasBitacora['fecha']; ?></td>
            <td align="center"><?php echo $row_rsEntradasBitacora['fecha_prox_mant']; ?></td>
          </tr>
          <?php } while ($row_rsEntradasBitacora = mysql_fetch_assoc($rsEntradasBitacora)); ?>
      </table></td>
    </tr>
    <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_rsEntradasBitacora == 0) { // Show if recordset empty ?>
  <tr>
    <td align="center"><h2 class="H_Estilo2 margin_both">No hay ninguna entrada en la bitácora</h2></td>
  </tr>
  <?php } // Show if recordset empty ?>
<tr>
      <td align="center">&nbsp;</td>
    </tr>
  </table>
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
mysql_free_result($rsEquipoComputo);

mysql_free_result($rsEntradasBitacora);
?>
