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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE usuarios SET email=%s, nombre_completo=%s, ape_pat=%s, ape_mat=%s, nombres=%s, genero=%s, campus=%s WHERE username=%s",
                       GetSQLValueString($_POST['fEmail'], "text"),
                       GetSQLValueString($_POST['fNombreCompleto'], "text"),
                       GetSQLValueString($_POST['fApePat'], "text"),
                       GetSQLValueString($_POST['fApeMat'], "text"),
                       GetSQLValueString($_POST['fNombres'], "text"),
                       GetSQLValueString($_POST['fGenero'], "text"),
                       GetSQLValueString($_POST['fCampus'], "text"),
                       GetSQLValueString($_POST['fUsername'], "text"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($updateSQL, $conP12) or die(mysql_error());

  $updateGoTo = "perfil_view.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsProfesor = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsProfesor = $_SESSION['MM_Username'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsProfesor = sprintf("SELECT * FROM usuarios WHERE username = %s", GetSQLValueString($colname_rsProfesor, "text"));
$rsProfesor = mysql_query($query_rsProfesor, $conP12) or die(mysql_error());
$row_rsProfesor = mysql_fetch_assoc($rsProfesor);
$totalRows_rsProfesor = mysql_num_rows($rsProfesor);

mysql_select_db($database_conP12, $conP12);
$query_rsCampus = "SELECT * FROM p12_campus ORDER BY campus ASC";
$rsCampus = mysql_query($query_rsCampus, $conP12) or die(mysql_error());
$row_rsCampus = mysql_fetch_assoc($rsCampus);
$totalRows_rsCampus = mysql_num_rows($rsCampus);
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
<script language="javascript" type="text/javascript">muestraMenuMain("<?php if(isset($_SESSION['MM_UserGroup'])){echo $_SESSION['MM_UserGroup'];}else {echo '';} ?>", "../", "");</script>
</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Path" -->
<div id="div_hdr_path">&nbsp;Inicio &gt; Mi perfil &gt; Editar información</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido"><br />
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td><div class="div_fldr_tab"><a href="perfil_view.php">Perfil</a></div></td>
      <td><div class="div_fldr_tab">Editar información</div></td>
      <td><div class="div_fldr_tab">Mensajes</div></td>
    </tr>
  </table>
  <div class="div_fldr">
    <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%" align="center" class="TablaViewProfile">
        <tr>
          <td width="200" rowspan="8" align="center">
		  	<?php if(file_exists("../imagenes/perfiles/".$row_rsProfesor['username'].".jpg")){
				echo "<img src='../imagenes/perfiles/".$row_rsProfesor['username'].".jpg' alt='Foto del usuario' name='foto_usuario' width='200' height='250' id='foto_usuario' />";
			} else {
      			echo "<img src='../imagenes/perfiles/sin_foto.png' alt='Foto del usuario' name='foto_usuario' width='200' height='250' id='foto_usuario' />";
      		} ?>
          </td>
          <th align="right"><label for="fUsername">Usuario</label></th>
          <td><input name="fUsername" type="text" id="fUsername" value="<?php echo $row_rsProfesor['username']; ?>" readonly /></td>
        </tr>
        <tr>
          <th align="right"><label for="fNombres">Nombre(s)</label></th>
          <td><input name="fNombres" type="text" id="fNombres" value="<?php echo $row_rsProfesor['nombres']; ?>" size="30" maxlength="30" required pattern=".{1,}" title="Escribe tu nombre" onchange="nombreCompleto()" /><span class="validity"></span></td>
        </tr>
        <tr>
          <th align="right"><label for="fApePat">Apellido paterno</label></th>
          <td><input name="fApePat" type="text" id="fApePat" value="<?php echo $row_rsProfesor['ape_pat']; ?>" size="30" maxlength="30" required pattern=".{1,}" title="Escribe tu apellido paterno" onchange="nombreCompleto()" /><span class="validity"></span></td>
        </tr>
        <tr>
          <th align="right"><label for="fApeMat">Apellido materno</label></th>
          <td><input name="fApeMat" type="text" id="fApeMat" value="<?php echo $row_rsProfesor['ape_mat']; ?>" size="30" maxlength="30" onchange="nombreCompleto()" /></td>
        </tr>
        <tr>
          <th align="right"><label for="fNombreCompleto">Nombre completo</label></th>
          <td><input name="fNombreCompleto" type="text" id="fNombreCompleto" value="<?php echo $row_rsProfesor['nombre_completo']; ?>" size="50" maxlength="100" readonly /></td>
        </tr>
        <tr>
          <th align="right"><label for="fEmail">E-mail</label></th>
          <td><input name="fEmail" type="text" id="fEmail" value="<?php echo $row_rsProfesor['email']; ?>" size="50" maxlength="100" /></td>
        </tr>
        <tr>
          <th align="right"><label for="fGenero">Género</label></th>
          <td><select name="fGenero" id="fGenero">
            <option value="Femenino" <?php if (!(strcmp("Femenino", $row_rsProfesor['genero']))) {echo "selected=\"selected\"";} ?>>Femenino</option>
            <option value="Masculino" <?php if (!(strcmp("Masculino", $row_rsProfesor['genero']))) {echo "selected=\"selected\"";} ?>>Masculino</option>
            <option value="No aplica" <?php if (!(strcmp("No aplica", $row_rsProfesor['genero']))) {echo "selected=\"selected\"";} ?>>No aplica</option>
          </select></td>
        </tr>
        <tr>
          <th align="right"><label for="fCampus">Campus</label></th>
          <td><select name="fCampus" id="fCampus">
            <?php
do {  
?>
            <option value="<?php echo $row_rsCampus['id_campus']?>"<?php if (!(strcmp($row_rsCampus['id_campus'], $row_rsProfesor['campus']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsCampus['campus']?></option>
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
          <td align="center"><a href="perfil_upload_picture.php" class="button-link">Subir foto</a></td>
          <th colspan="2" align="center"><input type="submit" name="bActualizar" id="bActualizar" value="Actualizar información personal" /></th>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1" />
    </form>
  </div><p class="center"><a href="perfil_view.php" class="button-link">Regresar</a></p>
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
mysql_free_result($rsProfesor);

mysql_free_result($rsCampus);
?>
