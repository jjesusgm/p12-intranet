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
$MM_authorizedUsers = "Administrativo,Administrador,Alumno,Profesor,Soporte,Servicio social";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO sop_solicitudes (id_tipo_sol, solicitante, usuario, sol_info_1, comentario) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fIdTipoSol'], "int"),
                       GetSQLValueString($_POST['fUsername'], "text"),
                       GetSQLValueString($_POST['fUsername'], "text"),
                       GetSQLValueString($_POST['fNip'], "text"),
                       GetSQLValueString($_POST['fComentario'], "text"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($insertSQL, $conP12) or die(mysql_error());

  $insertGoTo = "perfil_view.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_rsUsuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUsuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsUsuario = sprintf("SELECT * FROM usuarios WHERE username = %s", GetSQLValueString($colname_rsUsuario, "text"));
$rsUsuario = mysql_query($query_rsUsuario, $conP12) or die(mysql_error());
$row_rsUsuario = mysql_fetch_assoc($rsUsuario);
$totalRows_rsUsuario = mysql_num_rows($rsUsuario);

$colname_rsSolicitudes = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsSolicitudes = $_SESSION['MM_Username'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsSolicitudes = sprintf("SELECT sol.*, sta.status, usr.nombre_completo FROM sop_solicitudes sol  INNER JOIN sop_sol_status sta ON sol.id_status = sta.id_status INNER JOIN usuarios usr ON sol.res_usuario = usr.username WHERE usuario = %s AND id_tipo_sol = 1", GetSQLValueString($colname_rsSolicitudes, "text"));
$rsSolicitudes = mysql_query($query_rsSolicitudes, $conP12) or die(mysql_error());
$row_rsSolicitudes = mysql_fetch_assoc($rsSolicitudes);
$totalRows_rsSolicitudes = mysql_num_rows($rsSolicitudes);
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
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Mi perfil &gt; Solicitar cambio de contraseña</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Solicitar cambio de contraseña</h2>
  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <table align="center">
      <tr>
        <td align="right"><label for="fUsername">Usuario</label></td>
        <td><input name="fUsername" type="text" id="fUsername" value="<?php echo $row_rsUsuario['username']; ?>" size="20" maxlength="20" readonly />
        <input name="fIdTipoSol" type="hidden" id="fIdTipoSol" value="1" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNombreCompleto">Nombre</label></td>
        <td><input name="fNombreCompleto" type="text" id="fNombreCompleto" value="<?php echo $row_rsUsuario['nombre_completo']; ?>" size="50" maxlength="100" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fEmail">E-mail </label></td>
        <td><input name="fEmail" type="text" id="fEmail" value="<?php echo $row_rsUsuario['email']; ?>" size="50" maxlength="100" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNip">Contraseña</label></td>
        <td><input name="fNip" type="text" id="fNip" size="50" maxlength="100" placeholder="Escriba la nueva contraseña"/></td>
      </tr>
      <tr>
        <td align="right"><label for="fComentario">Comentario</label></td>
        <td><textarea name="fComentario" cols="44" rows="2" id="fComentario" placeholder="Opcionalmente puede escribir un comentario"></textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="bSolicitar" id="bSolicitar" value="Solicitar cambio" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
  </form>
  <br />
  <?php if ($totalRows_rsSolicitudes > 0) { // Show if recordset not empty ?>
  <table width="100%" class="TablaListaInventario">
    <tr>
      <th scope="col">ID SOLICITUD</th>
      <th scope="col">FECHA SOLICITUD</th>
      <th scope="col">STATUS</th>
      <th scope="col">NUEVA CONTRASEÑA</th>
      <th scope="col">USUARIO ASIGNADO</th>
      <th scope="col">FECHA RESOLUCION</th>
      <th scope="col">ACCION</th>
    </tr>
    <?php do { ?>
      <tr align="center">
        <td><?php echo $row_rsSolicitudes['id_solicitud']; ?></td>
        <td><?php echo $row_rsSolicitudes['fecha_sol']; ?></td>
        <td><?php echo $row_rsSolicitudes['status']; ?></td>
        <td><?php echo $row_rsSolicitudes['sol_info_1']; ?></td>
        <td><?php echo $row_rsSolicitudes['res_usuario']; ?>&nbsp;<a href="#" class="NtooltipSmall"><img src="../imagenes/ver.gif" alt="Ver nombre" width="12" height="12" class="NtooltipSmall VAlignMiddle" /><span><?php echo $row_rsSolicitudes['nombre_completo']; ?></span></a></td>
        <td><?php echo $row_rsSolicitudes['res_fecha']; ?></td>
        <td>Ver | Cancelar</td>
      </tr>
      <?php } while ($row_rsSolicitudes = mysql_fetch_assoc($rsSolicitudes)); ?>
  </table><br />
  <?php } // Show if recordset not empty ?>
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
mysql_free_result($rsUsuario);

mysql_free_result($rsSolicitudes);
?>
