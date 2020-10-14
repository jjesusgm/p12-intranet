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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_GET['UserName'])) && ($_GET['UserName'] != "")) {
  $deleteSQL = sprintf("DELETE FROM usuarios WHERE username=%s",
                       GetSQLValueString($_GET['UserName'], "text"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($deleteSQL, $conP12) or die(mysql_error());

  $deleteGoTo = "index.php";
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_rsUsuarios = 100;
$pageNum_rsUsuarios = 0;
if (isset($_GET['pageNum_rsUsuarios'])) {
  $pageNum_rsUsuarios = $_GET['pageNum_rsUsuarios'];
}
$startRow_rsUsuarios = $pageNum_rsUsuarios * $maxRows_rsUsuarios;

$varUserName_rsUsuarios = "%";
if (isset($_POST['fUserName'])) {
  $varUserName_rsUsuarios = $_POST['fUserName'];
}
$varCarrera_rsUsuarios = "%";
if (isset($_POST['fCarrera'])) {
  $varCarrera_rsUsuarios = $_POST['fCarrera'];
}
$varNivelDeAcceso_rsUsuarios = "%";
if (isset($_POST['fNivelDeAcceso'])) {
  $varNivelDeAcceso_rsUsuarios = $_POST['fNivelDeAcceso'];
}
$varCicloDeIngreso_rsUsuarios = "%";
if (isset($_POST['fCicloDeIngreso'])) {
  $varCicloDeIngreso_rsUsuarios = $_POST['fCicloDeIngreso'];
}
$varNombreCompleto_rsUsuarios = "%";
if (isset($_POST['fNombreCompleto'])) {
  $varNombreCompleto_rsUsuarios = $_POST['fNombreCompleto'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsUsuarios = sprintf("SELECT * FROM usuarios WHERE username LIKE %s AND nivel_de_acceso LIKE %s AND ciclo_de_ingreso LIKE %s AND nombre_completo LIKE %s AND carrera LIKE %s ORDER BY ape_pat,ape_mat,nombres ASC", GetSQLValueString($varUserName_rsUsuarios, "text"),GetSQLValueString($varNivelDeAcceso_rsUsuarios, "text"),GetSQLValueString($varCicloDeIngreso_rsUsuarios, "text"),GetSQLValueString($varNombreCompleto_rsUsuarios, "text"),GetSQLValueString($varCarrera_rsUsuarios, "text"));
$query_limit_rsUsuarios = sprintf("%s LIMIT %d, %d", $query_rsUsuarios, $startRow_rsUsuarios, $maxRows_rsUsuarios);
$rsUsuarios = mysql_query($query_limit_rsUsuarios, $conP12) or die(mysql_error());
$row_rsUsuarios = mysql_fetch_assoc($rsUsuarios);

if (isset($_GET['totalRows_rsUsuarios'])) {
  $totalRows_rsUsuarios = $_GET['totalRows_rsUsuarios'];
} else {
  $all_rsUsuarios = mysql_query($query_rsUsuarios);
  $totalRows_rsUsuarios = mysql_num_rows($all_rsUsuarios);
}
$totalPages_rsUsuarios = ceil($totalRows_rsUsuarios/$maxRows_rsUsuarios)-1;

mysql_select_db($database_conP12, $conP12);
$query_rsNivelesDeAcceso = "SELECT * FROM usr_niveles_acceso ORDER BY nivel_de_acceso ASC";
$rsNivelesDeAcceso = mysql_query($query_rsNivelesDeAcceso, $conP12) or die(mysql_error());
$row_rsNivelesDeAcceso = mysql_fetch_assoc($rsNivelesDeAcceso);
$totalRows_rsNivelesDeAcceso = mysql_num_rows($rsNivelesDeAcceso);

mysql_select_db($database_conP12, $conP12);
$query_rsCiclosDeIngreso = "SELECT * FROM p12_ciclos ORDER BY ciclo DESC";
$rsCiclosDeIngreso = mysql_query($query_rsCiclosDeIngreso, $conP12) or die(mysql_error());
$row_rsCiclosDeIngreso = mysql_fetch_assoc($rsCiclosDeIngreso);
$totalRows_rsCiclosDeIngreso = mysql_num_rows($rsCiclosDeIngreso);

mysql_select_db($database_conP12, $conP12);
$query_rsCarreras = "SELECT * FROM p12_carreras ORDER BY carrera ASC";
$rsCarreras = mysql_query($query_rsCarreras, $conP12) or die(mysql_error());
$row_rsCarreras = mysql_fetch_assoc($rsCarreras);
$totalRows_rsCarreras = mysql_num_rows($rsCarreras);

$queryString_rsUsuarios = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsUsuarios") == false && 
        stristr($param, "totalRows_rsUsuarios") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsUsuarios = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsUsuarios = sprintf("&totalRows_rsUsuarios=%d%s", $totalRows_rsUsuarios, $queryString_rsUsuarios);
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
	if(!f.fUserName.value){
		f.fUserName.value = "%";
	}
	if(!f.fNombreCompleto.value){
		f.fNombreCompleto.value = "%";
	}else{
		f.fNombreCompleto.value = "%"+f.fNombreCompleto.value+"%";
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Usuarios</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center"> Mostrando Usuarios <?php echo ($startRow_rsUsuarios + 1) ?> al <?php echo min($startRow_rsUsuarios + $maxRows_rsUsuarios, $totalRows_rsUsuarios) ?> de <?php echo $totalRows_rsUsuarios ?></h2>
  <table align="center"><tr>
    <td>
  <form action="" method="post" name="form1" id="form1" onsubmit="return valida(this)">
  	<fieldset><legend>Filtrar resultados</legend>
    <table align="center">
      <tr>
        <td align="right"><label for="fUserName">Usuario</label></td>
        <td><input name="fUserName" type="text" id="fUserName" size="20" maxlength="20" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNivelDeAcceso">Nivel de acceso</label></td>
        <td><select name="fNivelDeAcceso" id="fNivelDeAcceso">
          <option value="%">Todos</option>
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
          <option value="%">Todos</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsCiclosDeIngreso['ciclo']?>"><?php echo $row_rsCiclosDeIngreso['ciclo']?></option>
          <?php
} while ($row_rsCiclosDeIngreso = mysql_fetch_assoc($rsCiclosDeIngreso));
  $rows = mysql_num_rows($rsCiclosDeIngreso);
  if($rows > 0) {
      mysql_data_seek($rsCiclosDeIngreso, 0);
	  $row_rsCiclosDeIngreso = mysql_fetch_assoc($rsCiclosDeIngreso);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fNombreCompleto">Nombre</label></td>
        <td><input name="fNombreCompleto" type="text" id="fNombreCompleto" size="60" maxlength="100" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fCarrera">Carrera</label></td>
        <td><select name="fCarrera" id="fCarrera">
          <option value="%">Todas</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsCarreras['id_carrera']?>"><?php echo $row_rsCarreras['carrera']?></option>
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
        <td colspan="2" align="center"><input type="submit" name="bAgregarFiltro" id="bAgregarFiltro" value="Agregar filtro" /></td>
      </tr>
    </table>
    </fieldset>
  </form>
  </td></tr></table>
  <h3 class="center"><a href="usr_add.php" class="estilo3">Agregar nuevo usuario</a></h3>
  <table align="center" class="TablaNavegacion">
  <tr align="center">
    <td scope="col"><a href="<?php printf("%s?pageNum_rsUsuarios=%d%s", $currentPage, 0, $queryString_rsUsuarios); ?>">Primera</a> | <a href="<?php printf("%s?pageNum_rsUsuarios=%d%s", $currentPage, max(0, $pageNum_rsUsuarios - 1), $queryString_rsUsuarios); ?>">Anterior</a> | <a href="<?php printf("%s?pageNum_rsUsuarios=%d%s", $currentPage, min($totalPages_rsUsuarios, $pageNum_rsUsuarios + 1), $queryString_rsUsuarios); ?>">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsUsuarios=%d%s", $currentPage, $totalPages_rsUsuarios, $queryString_rsUsuarios); ?>">Ultima</a></td>
    </tr>
</table>

  <table width="100%" class="TablaListaInventario">
    <tr>
      <th align="center" scope="col">Usuario</th>
      <th align="center" scope="col">NIP</th>
      <th align="center" scope="col">Tipo NIP</th>
      <th align="center" scope="col">Nivel acceso</th>
      <th align="center" scope="col">Cic. Ingreso</th>
      <th align="center" scope="col">Ultimo acceso</th>
      <th align="center" scope="col">Nombre completo</th>
      <th align="center" scope="col">Carrera</th>
      <th align="center" scope="col">ACCION</th>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center" scope="col"><?php echo $row_rsUsuarios['username']; ?></td>
        <td align="center" valign="middle" scope="col"><a href="#" class="Ntooltip"><img src="../imagenes/lupa.png" width="22" height="22" alt="Mostrar NIP" /><span><?php echo $row_rsUsuarios['nip']; ?></span></a></td>
        <td align="center" valign="middle" scope="col"><?php echo $row_rsUsuarios['nip_tipo']; ?></td>
        <td align="center" valign="middle" scope="col"><?php echo $row_rsUsuarios['nivel_de_acceso']; ?></td>
        <td align="center" valign="middle" scope="col"><?php echo $row_rsUsuarios['ciclo_de_ingreso']; ?></td>
        <td align="center" scope="col"><?php echo $row_rsUsuarios['ultimo_acceso']; ?></td>
        <td align="center" scope="col"><?php echo $row_rsUsuarios['nombre_completo']; ?></td>
        <td align="center" scope="col"><?php echo $row_rsUsuarios['carrera']; ?></td>
        <td align="center" scope="col"><a href="usr_edit.php?username=<?php echo $row_rsUsuarios['username']; ?>">Editar</a> | <a href="usr_delete.php?username=<?php echo $row_rsUsuarios['username']; ?>">Eliminar</a></td>
      </tr>
      <?php } while ($row_rsUsuarios = mysql_fetch_assoc($rsUsuarios)); ?>
  </table>
  <table align="center" class="TablaNavegacion">
  <tr>
    <td scope="col"><a href="<?php printf("%s?pageNum_rsUsuarios=%d%s", $currentPage, 0, $queryString_rsUsuarios); ?>">Primera</a> | <a href="<?php printf("%s?pageNum_rsUsuarios=%d%s", $currentPage, max(0, $pageNum_rsUsuarios - 1), $queryString_rsUsuarios); ?>">Anterior</a> | <a href="<?php printf("%s?pageNum_rsUsuarios=%d%s", $currentPage, min($totalPages_rsUsuarios, $pageNum_rsUsuarios + 1), $queryString_rsUsuarios); ?>">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsUsuarios=%d%s", $currentPage, $totalPages_rsUsuarios, $queryString_rsUsuarios); ?>">Ultima</a></td>
  </tr>
</table>
  <h3 class="center"><a href="usr_add.php" class="estilo3">Agregar nuevo usuario</a></h3>

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
mysql_free_result($rsUsuarios);

mysql_free_result($rsNivelesDeAcceso);

mysql_free_result($rsCiclosDeIngreso);

mysql_free_result($rsCarreras);
?>
