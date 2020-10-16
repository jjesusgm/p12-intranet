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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO usuarios (username, nivel_de_acceso, ciclo_de_ingreso, email, nombre_completo, ape_pat, ape_mat, nombres, genero, nip, nip_tipo, campus, carrera, comentario1, comentario2, comentario3, comentario4, comentario5) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fUserName'], "text"),
                       GetSQLValueString($_POST['fNivelDeAcceso'], "text"),
                       GetSQLValueString($_POST['fCicloDeIngreso'], "text"),
                       GetSQLValueString($_POST['fEmail'], "text"),
                       GetSQLValueString($_POST['fNombreCompleto'], "text"),
                       GetSQLValueString($_POST['fApePat'], "text"),
                       GetSQLValueString($_POST['fApeMat'], "text"),
                       GetSQLValueString($_POST['fNombres'], "text"),
                       GetSQLValueString($_POST['fGenero'], "text"),
                       GetSQLValueString($_POST['fNip'], "text"),
                       GetSQLValueString($_POST['fNipTipo'], "text"),
                       GetSQLValueString($_POST['fCampus'], "text"),
                       GetSQLValueString($_POST['fCarrera'], "text"),
                       GetSQLValueString($_POST['fComentario1'], "text"),
                       GetSQLValueString($_POST['fComentario2'], "text"),
                       GetSQLValueString($_POST['fComentario3'], "text"),
                       GetSQLValueString($_POST['fComentario4'], "text"),
                       GetSQLValueString($_POST['fComentario5'], "text"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($insertSQL, $conP12) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
$query_rsNivelesDeAcceso = "SELECT * FROM usr_niveles_acceso ORDER BY nivel_de_acceso ASC";
$rsNivelesDeAcceso = mysql_query($query_rsNivelesDeAcceso, $conP12) or die(mysql_error());
$row_rsNivelesDeAcceso = mysql_fetch_assoc($rsNivelesDeAcceso);
$totalRows_rsNivelesDeAcceso = mysql_num_rows($rsNivelesDeAcceso);

mysql_select_db($database_conP12, $conP12);
$query_rsCiclos = "SELECT * FROM p12_ciclos ORDER BY ciclo DESC";
$rsCiclos = mysql_query($query_rsCiclos, $conP12) or die(mysql_error());
$row_rsCiclos = mysql_fetch_assoc($rsCiclos);
$totalRows_rsCiclos = mysql_num_rows($rsCiclos);

mysql_select_db($database_conP12, $conP12);
$query_rsCampus = "SELECT * FROM p12_campus ORDER BY campus ASC";
$rsCampus = mysql_query($query_rsCampus, $conP12) or die(mysql_error());
$row_rsCampus = mysql_fetch_assoc($rsCampus);
$totalRows_rsCampus = mysql_num_rows($rsCampus);

mysql_select_db($database_conP12, $conP12);
$query_rsCarreras = "SELECT * FROM p12_carreras ORDER BY carrera ASC";
$rsCarreras = mysql_query($query_rsCarreras, $conP12) or die(mysql_error());
$row_rsCarreras = mysql_fetch_assoc($rsCarreras);
$totalRows_rsCarreras = mysql_num_rows($rsCarreras);
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
function nombreCompleto(){
	var nc = document.getElementById("fNombreCompleto");
	var ns = document.getElementById("fNombres");
	var ap = document.getElementById("fApePat");
	var am = document.getElementById("fApeMat");
	
	nc.value = "";
	if(ns.value.length>0){
		nc.value = ns.value;
	}
	if(ap.value.length>0){
		if(nc.value.length>0){
			nc.value = nc.value+" "+ap.value;
		}else{
			nc.value = ap.value;
		}
	}
	if(am.value.length>0){
		if(nc.value.length>0){
			nc.value = nc.value+" "+am.value;
		}else{
			nc.value = am.value;
		}
	}
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
<script language="javascript" type="text/javascript">muestraMenuMain("<?php if(isset($_SESSION['MM_UserGroup'])){echo $_SESSION['MM_UserGroup'];}else {echo "";} ?>", "../", "");</script>
</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Path" -->
<div id="div_hdr_path">&nbsp;Inicio &gt; Usuarios &gt; Agregar un usuario</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Agregando usuario</h2>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center" class="tabla_usuario">
      <tr>
        <th colspan="2" align="left" scope="col">Registro del usuario a agregar</th>
      </tr>
      <tr>
        <td align="right"><label for="fUserName">Usuario*</label></td>
        <td><input name="fUserName" type="text" id="fUserName" size="20" maxlength="20" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNivelDeAcceso">Nivel de acceso*</label></td>
        <td><select name="fNivelDeAcceso" id="fNivelDeAcceso">
          <?php
do {  
?>
          <option value="<?php echo $row_rsNivelesDeAcceso['nivel_de_acceso']?>"><?php echo $row_rsNivelesDeAcceso['nivel_de_acceso']?></option>
          <?php
} while ($row_rsNivelesDeAcceso = mysql_fetch_assoc($rsNivelesDeAcceso));
  $rows = mysql_num_rows($rsNivelesDeAcceso);
  if($rows > 0) {
      mysql_data_seek($rsNivelesDeAcceso, 0);
	  $row_rsNivelesDeAcceso = mysql_fetch_assoc($rsNivelesDeAcceso);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fCicloDeIngreso">Ciclo de ingreso</label></td>
        <td><select name="fCicloDeIngreso" id="fCicloDeIngreso">
          <?php
do {  
?>
          <option value="<?php echo $row_rsCiclos['ciclo']?>"><?php echo $row_rsCiclos['ciclo']?></option>
          <?php
} while ($row_rsCiclos = mysql_fetch_assoc($rsCiclos));
  $rows = mysql_num_rows($rsCiclos);
  if($rows > 0) {
      mysql_data_seek($rsCiclos, 0);
	  $row_rsCiclos = mysql_fetch_assoc($rsCiclos);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fEmail">Correo electrónico</label></td>
        <td><input name="fEmail" type="text" id="fEmail" size="50" maxlength="100" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNombreCompleto">Nombre completo*</label></td>
        <td><input name="fNombreCompleto" type="text" id="fNombreCompleto" size="50" maxlength="100" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fApePat">Apellido paterno*</label></td>
        <td><input name="fApePat" type="text" id="fApePat" size="30" maxlength="30" onchange="nombreCompleto()" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fApeMat">Apellido materno</label></td>
        <td><input name="fApeMat" type="text" id="fApeMat" size="30" maxlength="30" onchange="nombreCompleto()" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNombres">Nombre(s)*</label></td>
        <td><input name="fNombres" type="text" id="fNombres" size="30" maxlength="30" onchange="nombreCompleto()" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fGenero">Género</label></td>
        <td><select name="fGenero" id="fGenero">
<option value="No aplica">No aplica</option>
<option value="Femenino">Femenino</option>
<option value="Masculino">Masculino</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fNip">Contraseña*</label></td>
        <td><input name="fNip" type="password" id="fNip" size="30" maxlength="100" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNipTipo">Tipo de NIP*</label></td>
        <td><select name="fNipTipo" id="fNipTipo">
          <option value="PREPA12">PREPA 12</option>
          <option value="SIIAU">SIIAU</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fCampus">Campus*</label></td>
        <td><select name="fCampus" id="fCampus">
          <?php
do {  
?>
          <option value="<?php echo $row_rsCampus['id_campus']?>"><?php echo $row_rsCampus['campus']?></option>
          <?php
} while ($row_rsCampus = mysql_fetch_assoc($rsCampus));
  $rows = mysql_num_rows($rsCampus);
  if($rows > 0) {
      mysql_data_seek($rsCampus, 0);
	  $row_rsCampus = mysql_fetch_assoc($rsCampus);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fCarrera">Carrera</label></td>
        <td><select name="fCarrera" id="fCarrera">
          <?php
do {  
?>
          <option value="<?php echo $row_rsCarreras['id_carrera']?>"<?php if (!(strcmp($row_rsCarreras['id_carrera'], "No aplica"))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsCarreras['carrera']?></option>
          <?php
} while ($row_rsCarreras = mysql_fetch_assoc($rsCarreras));
  $rows = mysql_num_rows($rsCarreras);
  if($rows > 0) {
      mysql_data_seek($rsCarreras, 0);
	  $row_rsCarreras = mysql_fetch_assoc($rsCarreras);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fComentario1">Comentario 1</label></td>
        <td><input name="fComentario1" type="text" id="fComentario1" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fComentario2">Comentario 2</label></td>
        <td><input name="fComentario2" type="text" id="fComentario2" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fComentario3">Comentario 3</label></td>
        <td><input name="fComentario3" type="text" id="fComentario3" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fComentario4">Comentario 4</label></td>
        <td><input name="fComentario4" type="text" id="fComentario4" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fComentario5">Comentario 5</label></td>
        <td><input name="fComentario5" type="text" id="fComentario5" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center">
        <input type="submit" name="bAgregar" id="bAgregar" value="Agregar" />
        <input type="reset" name="bRestablecer" id="bRestablecer" value="Restablecer" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
  </form>
  <p>&nbsp;</p>
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
mysql_free_result($rsNivelesDeAcceso);

mysql_free_result($rsCiclos);

mysql_free_result($rsCampus);

mysql_free_result($rsCarreras);
?>
