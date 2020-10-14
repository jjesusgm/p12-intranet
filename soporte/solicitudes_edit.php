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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE sop_solicitudes SET id_tipo_sol=%s, solicitante=%s, usuario=%s, fecha_sol=%s, id_status=%s, sol_info_1=%s, comentario=%s, res_usuario=%s, res_fecha=%s, res_comentario=%s WHERE id_solicitud=%s",
                       GetSQLValueString($_POST['fIdTipoSol'], "int"),
                       GetSQLValueString($_POST['fSolicitante'], "text"),
                       GetSQLValueString($_POST['fUsuario'], "text"),
                       GetSQLValueString($_POST['fFechaSol'], "date"),
                       GetSQLValueString($_POST['fIdStatus'], "int"),
                       GetSQLValueString($_POST['fSolInfo1'], "text"),
                       GetSQLValueString($_POST['fComentario'], "text"),
                       GetSQLValueString($_POST['fResUsuario'], "text"),
                       GetSQLValueString($_POST['fResFecha'], "date"),
                       GetSQLValueString($_POST['fResComentario'], "text"),
                       GetSQLValueString($_POST['fIdSolicitud'], "int"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($updateSQL, $conP12) or die(mysql_error());

  $updateGoTo = "solicitudes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsSolicitud = "-1";
if (isset($_GET['id_solicitud'])) {
  $colname_rsSolicitud = $_GET['id_solicitud'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsSolicitud = sprintf("SELECT * FROM sop_solicitudes WHERE id_solicitud = %s", GetSQLValueString($colname_rsSolicitud, "int"));
$rsSolicitud = mysql_query($query_rsSolicitud, $conP12) or die(mysql_error());
$row_rsSolicitud = mysql_fetch_assoc($rsSolicitud);
$totalRows_rsSolicitud = mysql_num_rows($rsSolicitud);

mysql_select_db($database_conP12, $conP12);
$query_rsSolStatus = "SELECT * FROM sop_sol_status ORDER BY status ASC";
$rsSolStatus = mysql_query($query_rsSolStatus, $conP12) or die(mysql_error());
$row_rsSolStatus = mysql_fetch_assoc($rsSolStatus);
$totalRows_rsSolStatus = mysql_num_rows($rsSolStatus);

mysql_select_db($database_conP12, $conP12);
$query_rsResUsuarios = "SELECT * FROM usuarios WHERE nivel_de_acceso <> 'Alumno' ORDER BY nombre_completo ASC";
$rsResUsuarios = mysql_query($query_rsResUsuarios, $conP12) or die(mysql_error());
$row_rsResUsuarios = mysql_fetch_assoc($rsResUsuarios);
$totalRows_rsResUsuarios = mysql_num_rows($rsResUsuarios);
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
<script type="text/javascript" src="../js/fechas.js"></script>
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Soporte &gt; Modificar solicitud</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Modificando solicitud de soporte</h2>
  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="700" align="center" class="tabla_usuario">
      <tr>
        <th colspan="2" align="left" scope="col">Registro que se va a modificar</th>
      </tr>
      <tr>
        <td align="right"><label for="fIdSolicitud">ID</label></td>
        <td><input name="fIdSolicitud" type="text" id="fIdSolicitud" value="<?php echo $row_rsSolicitud['id_solicitud']; ?>" size="10" maxlength="10" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fIdTipoSol">Tipo</label></td>
        <td><input name="fIdTipoSol" type="text" id="fIdTipoSol" value="<?php echo $row_rsSolicitud['id_tipo_sol']; ?>" size="3" maxlength="3" readonly />
        <input name="fTipoSol" type="text" id="fTipoSol" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fSolicitante">Solicitante</label></td>
        <td><input name="fSolicitante" type="text" id="fSolicitante" value="<?php echo $row_rsSolicitud['solicitante']; ?>" size="9" maxlength="20" readonly />
        <input name="fSolicitanteNC" type="text" id="fSolicitanteNC" size="60" maxlength="100" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fUsuario">Usuario</label></td>
        <td><input name="fUsuario" type="text" id="fUsuario" value="<?php echo $row_rsSolicitud['usuario']; ?>" size="9" maxlength="20" readonly />
        <input name="fUsuarioNC" type="text" id="fUsuarioNC" size="60" maxlength="100" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fFechaSol">Fecha solicitud</label></td>
        <td><input name="fFechaSol" type="text" id="fFechaSol" value="<?php echo $row_rsSolicitud['fecha_sol']; ?>" size="20" maxlength="20" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fIdStatus">Status</label></td>
        <td><label for="fIdStatus"></label>
          <select name="fIdStatus" id="fIdStatus">
            <?php
do {  
?>
            <option value="<?php echo $row_rsSolStatus['id_status']?>"<?php if (!(strcmp($row_rsSolStatus['id_status'], $row_rsSolicitud['id_status']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsSolStatus['status']?></option>
            <?php
} while ($row_rsSolStatus = mysql_fetch_assoc($rsSolStatus));
  $rows = mysql_num_rows($rsSolStatus);
  if($rows > 0) {
      mysql_data_seek($rsSolStatus, 0);
	  $row_rsSolStatus = mysql_fetch_assoc($rsSolStatus);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fSolInfo1">Información</label></td>
        <td><input name="fSolInfo1" type="text" id="fSolInfo1" value="<?php echo $row_rsSolicitud['sol_info_1']; ?>" size="50" maxlength="100" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fComentario">Comentario</label></td>
        <td><input name="fComentario" type="text" id="fComentario" placeholder="Comentario del solicitante" value="<?php echo $row_rsSolicitud['comentario']; ?>" size="75" maxlength="200" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fResUsuario">Asignado a</label></td>
        <td><select name="fResUsuario" id="fResUsuario">
          <?php
do {  
?>
          <option value="<?php echo $row_rsResUsuarios['username']?>"<?php if (!(strcmp($row_rsResUsuarios['username'], $row_rsSolicitud['res_usuario']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsResUsuarios['nombre_completo']?></option>
          <?php
} while ($row_rsResUsuarios = mysql_fetch_assoc($rsResUsuarios));
  $rows = mysql_num_rows($rsResUsuarios);
  if($rows > 0) {
      mysql_data_seek($rsResUsuarios, 0);
	  $row_rsResUsuarios = mysql_fetch_assoc($rsResUsuarios);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fResFecha">Fecha resuelto</label></td>
        <td><input name="fResFecha" type="text" id="fResFecha" value="<?php echo $row_rsSolicitud['res_fecha']; ?>" size="25" maxlength="20" onfocus="this.value=curDateToStringForDB()" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fResComentario">Comentario</label></td>
        <td><input name="fResComentario" type="text" id="fResComentario" placeholder="Comentario de la resolución" value="<?php echo $row_rsSolicitud['res_comentario']; ?>" size="75" maxlength="200" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="bModificar" id="bModificar" value="Modificar" />
          <a href="solicitudes.php" class="button-link">Cancelar</a></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
  </form><br />
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
mysql_free_result($rsSolicitud);

mysql_free_result($rsSolStatus);

mysql_free_result($rsResUsuarios);
?>
