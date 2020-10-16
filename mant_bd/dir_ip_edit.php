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
  $updateSQL = sprintf("UPDATE p12_dir_ip SET sub_red=%s, dir_ip=%s, mac_eth=%s, mac_wifi=%s, asignada=%s, nombre_host=%s, id_usuario=%s, id_ubicacion=%s WHERE id_dir_ip=%s",
                       GetSQLValueString($_POST['fSubRed'], "int"),
                       GetSQLValueString($_POST['fDirIp'], "text"),
                       GetSQLValueString($_POST['fMacEth'], "text"),
                       GetSQLValueString($_POST['fMacWiFi'], "text"),
                       GetSQLValueString($_POST['fAsignada'], "text"),
                       GetSQLValueString($_POST['fNombreHost'], "text"),
                       GetSQLValueString($_POST['fIdUsuario'], "text"),
                       GetSQLValueString($_POST['fIdUbicacion'], "text"),
                       GetSQLValueString($_POST['fIdDirIp'], "int"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($updateSQL, $conP12) or die(mysql_error());

  $updateGoTo = "dir_ip_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsDirIp = "-1";
if (isset($_GET['id_dir_ip'])) {
  $colname_rsDirIp = $_GET['id_dir_ip'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsDirIp = sprintf("SELECT * FROM p12_dir_ip WHERE id_dir_ip = %s", GetSQLValueString($colname_rsDirIp, "int"));
$rsDirIp = mysql_query($query_rsDirIp, $conP12) or die(mysql_error());
$row_rsDirIp = mysql_fetch_assoc($rsDirIp);
$totalRows_rsDirIp = mysql_num_rows($rsDirIp);

mysql_select_db($database_conP12, $conP12);
$query_rsUsuarios = "SELECT * FROM usuarios WHERE nivel_de_acceso <> 'Alumno' ORDER BY nombre_completo ASC";
$rsUsuarios = mysql_query($query_rsUsuarios, $conP12) or die(mysql_error());
$row_rsUsuarios = mysql_fetch_assoc($rsUsuarios);
$totalRows_rsUsuarios = mysql_num_rows($rsUsuarios);

mysql_select_db($database_conP12, $conP12);
$query_rsUbicaciones = "SELECT * FROM p12_ubicaciones ORDER BY ub_nombre ASC";
$rsUbicaciones = mysql_query($query_rsUbicaciones, $conP12) or die(mysql_error());
$row_rsUbicaciones = mysql_fetch_assoc($rsUbicaciones);
$totalRows_rsUbicaciones = mysql_num_rows($rsUbicaciones);
?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/t_intranet_p12.dwt" codeOutsideHTMLIsLocked="false" -->
<!-- InstanceParam name="body" type="text" value="" -->
<!-- InstanceParam name="focusFormElement" type="text" value="focusIpField()" -->
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
<link href="../css/imagenes.css" rel="stylesheet" type="text/css" />
<link href="../css/menu1.css" rel="stylesheet" type="text/css" />
<link href="../css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../css/varios.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/menus.js"></script>
<script type="text/javascript" src="../js/set_caret_pos.js"></script>
<script language="javascript">
function focusIpField() {
	var ip = document.getElementById("fDirIp");
	setCaretPosition(ip, ip.value.length);
}
function updateIpField() {
    var sr = document.getElementById("fSubRed").value;
	var ip = document.getElementById("fDirIp");
	if(sr == "47"){
		var old_ip = ip.value;
		var new_ip = old_ip.replace(".76.", ".47.");
		ip.value = new_ip;
	}else if(sr=="76"){
		var old_ip = ip.value;
		var new_ip = old_ip.replace(".47.", ".76.");
		ip.value = new_ip;
		//ip.value = "148.202."+sr+".";
	}
	setCaretPosition(ip, ip.value.length);
}
</script>
</head>
<!-- InstanceEndEditable -->
<body onload="focusIpField()">
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
<div id="div_hdr_path">&nbsp;Inicio</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Modificando una dirección IP</h2>
  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="500" align="center" class="tabla_usuario">
      <tr>
        <th colspan="2" align="left" scope="col">Registro de la dirección IP</th>
      </tr>
      <tr>
        <td align="right"><label for="fIdDirIp">ID</label></td>
        <td><input name="fIdDirIp" type="text" id="fIdDirIp" value="<?php echo $row_rsDirIp['id_dir_ip']; ?>" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fSubRed">Subred*</label></td>
        <td><select name="fSubRed" id="fSubRed" onchange="updateIpField()">
          <option value="47" <?php if (!(strcmp(47, $row_rsDirIp['sub_red']))) {echo "selected=\"selected\"";} ?>>47</option>
          <option value="76" <?php if (!(strcmp(76, $row_rsDirIp['sub_red']))) {echo "selected=\"selected\"";} ?>>76</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fDirIp">Dirección IP*</label></td>
        <td><input name="fDirIp" type="text" id="fDirIp" value="<?php echo $row_rsDirIp['dir_ip']; ?>" size="20" maxlength="15" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fMacEth">MAC Ethernet</label></td>
        <td><input name="fMacEth" type="text" id="fMacEth" value="<?php echo $row_rsDirIp['mac_eth']; ?>" size="20" maxlength="17" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fMacWiFi">MAC WiFi</label></td>
        <td><input name="fMacWiFi" type="text" id="fMacWiFi" value="<?php echo $row_rsDirIp['mac_wifi']; ?>" size="20" maxlength="17" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fAsignada">Asignada*</label></td>
        <td><select name="fAsignada" id="fAsignada">
          <option value="No" <?php if (!(strcmp("No", $row_rsDirIp['asignada']))) {echo "selected=\"selected\"";} ?>>No</option>
          <option value="Si" <?php if (!(strcmp("Si", $row_rsDirIp['asignada']))) {echo "selected=\"selected\"";} ?>>Si</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fNombreHost">Host</label></td>
        <td><input name="fNombreHost" type="text" id="fNombreHost" value="<?php echo $row_rsDirIp['nombre_host']; ?>" size="25" maxlength="20" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fIdUsuario">Usuario*</label></td>
        <td><select name="fIdUsuario" id="fIdUsuario">
          <?php
do {  
?>
          <option value="<?php echo $row_rsUsuarios['username']?>"<?php if (!(strcmp($row_rsUsuarios['username'], $row_rsDirIp['id_usuario']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsUsuarios['nombre_completo']?></option>
          <?php
} while ($row_rsUsuarios = mysql_fetch_assoc($rsUsuarios));
  $rows = mysql_num_rows($rsUsuarios);
  if($rows > 0) {
      mysql_data_seek($rsUsuarios, 0);
	  $row_rsUsuarios = mysql_fetch_assoc($rsUsuarios);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fIdUbicacion">Ubicación*</label></td>
        <td><select name="fIdUbicacion" id="fIdUbicacion">
          <?php
do {  
?>
          <option value="<?php echo $row_rsUbicaciones['id_ubicacion']?>"<?php if (!(strcmp($row_rsUbicaciones['id_ubicacion'], $row_rsDirIp['id_ubicacion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsUbicaciones['ub_nombre']?></option>
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
        <td colspan="2" align="center"><input type="submit" name="bModificar" id="bModificar" value="Modificar" />
          <a href="dir_ip_list.php" class="button-link">Cancelar</a></td>
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
mysql_free_result($rsDirIp);

mysql_free_result($rsUsuarios);

mysql_free_result($rsUbicaciones);
?>
