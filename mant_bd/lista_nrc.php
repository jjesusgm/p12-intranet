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

$maxRows_rsNRCs = 20;
$pageNum_rsNRCs = 0;
if (isset($_GET['pageNum_rsNRCs'])) {
  $pageNum_rsNRCs = $_GET['pageNum_rsNRCs'];
}
$startRow_rsNRCs = $pageNum_rsNRCs * $maxRows_rsNRCs;

$varIdNrc_rsNRCs = "%";
if (isset($_POST['fIdNrc'])) {
  $varIdNrc_rsNRCs = $_POST['fIdNrc'];
}
$varIdProfesor_rsNRCs = "%";
if (isset($_POST['fIdProfesor'])) {
  $varIdProfesor_rsNRCs = $_POST['fIdProfesor'];
}
$varUA_rsNRCs = "%";
if (isset($_POST['fUA'])) {
  $varUA_rsNRCs = $_POST['fUA'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsNRCs = sprintf("SELECT * FROM nrc_list WHERE id_nrc LIKE %s AND ua LIKE %s AND id_profesor LIKE %s ORDER BY id_nrc ASC", GetSQLValueString($varIdNrc_rsNRCs, "text"),GetSQLValueString($varUA_rsNRCs, "text"),GetSQLValueString($varIdProfesor_rsNRCs, "text"));
$query_limit_rsNRCs = sprintf("%s LIMIT %d, %d", $query_rsNRCs, $startRow_rsNRCs, $maxRows_rsNRCs);
$rsNRCs = mysql_query($query_limit_rsNRCs, $conP12) or die(mysql_error());
$row_rsNRCs = mysql_fetch_assoc($rsNRCs);

if (isset($_GET['totalRows_rsNRCs'])) {
  $totalRows_rsNRCs = $_GET['totalRows_rsNRCs'];
} else {
  $all_rsNRCs = mysql_query($query_rsNRCs);
  $totalRows_rsNRCs = mysql_num_rows($all_rsNRCs);
}
$totalPages_rsNRCs = ceil($totalRows_rsNRCs/$maxRows_rsNRCs)-1;

mysql_select_db($database_conP12, $conP12);
$query_rsProfesores = "SELECT * FROM usuarios WHERE nivel_de_acceso = 'Profesor' ORDER BY nombre_completo ASC";
$rsProfesores = mysql_query($query_rsProfesores, $conP12) or die(mysql_error());
$row_rsProfesores = mysql_fetch_assoc($rsProfesores);
$totalRows_rsProfesores = mysql_num_rows($rsProfesores);

$queryString_rsNRCs = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsNRCs") == false && 
        stristr($param, "totalRows_rsNRCs") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsNRCs = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsNRCs = sprintf("&totalRows_rsNRCs=%d%s", $totalRows_rsNRCs, $queryString_rsNRCs);
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
	if(!f.fIdNrc.value){
		f.fIdNrc.value = "%";
	}
	if(!f.fUA.value){
		f.fUA.value = "%";
	}else{
		f.fUA.value = "%"+f.fUA.value+"%";
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Mantenimiento a BD &gt; Lista de NRCs</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Mostrando registros <?php echo ($startRow_rsNRCs + 1) ?> al <?php echo min($startRow_rsNRCs + $maxRows_rsNRCs, $totalRows_rsNRCs) ?> de <?php echo $totalRows_rsNRCs ?></h2>
  <div class="div_filtro">
    <form id="form1" name="form1" method="post" action="" onsubmit="return valida(this)">
      <fieldset>
        <legend>Filtrar resultados</legend>
        <table width="100%">
          <tr>
            <td align="right"><label for="fIdNrc">NRC</label></td>
            <td><input name="fIdNrc" type="text" id="fIdNrc" size="10" maxlength="8" pattern="\d{5}" title="ID del NRC (Formato: Vacío ó 99999" /><span class="validity"></span></td>
          </tr>
          <tr>
            <td align="right"><label for="fUA">Unidad de Aprendizaje</label></td>
            <td><input name="fUA" type="text" id="fUA" size="50" maxlength="50" pattern="[a-zA-Z][a-zA-Z0-9\s]*" title="Unidad de Aprendizaje (Formato: Vacío ó nombre parcial de la UA" /><span class="validity"></span></td>
          </tr>
          <tr>
            <td align="right"><label for="fIdProfesor">Profesor</label></td>
            <td align="left"><select name="fIdProfesor" id="fIdProfesor">
              <option value="%">Todos</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rsProfesores['username']?>"><?php echo $row_rsProfesores['nombre_completo']?></option>
              <?php
} while ($row_rsProfesores = mysql_fetch_assoc($rsProfesores));
  $rows = mysql_num_rows($rsProfesores);
  if($rows > 0) {
      mysql_data_seek($rsProfesores, 0);
	  $row_rsProfesores = mysql_fetch_assoc($rsProfesores);
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
  </div>
  <h3 class="center"><a href="nrc_add.php" class="estilo3">Agregar nuevo NRC</a></h3>
  <table align="center">
    <tr>
      <td>Navegación:</td>
      <td><a href="<?php printf("%s?pageNum_rsNRCs=%d%s", $currentPage, 0, $queryString_rsNRCs); ?>" class="estilo3">Primera</a> | <a href="<?php printf("%s?pageNum_rsNRCs=%d%s", $currentPage, max(0, $pageNum_rsNRCs - 1), $queryString_rsNRCs); ?>" class="estilo3">Anterior</a> | <a href="<?php printf("%s?pageNum_rsNRCs=%d%s", $currentPage, min($totalPages_rsNRCs, $pageNum_rsNRCs + 1), $queryString_rsNRCs); ?>" class="estilo3">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsNRCs=%d%s", $currentPage, $totalPages_rsNRCs, $queryString_rsNRCs); ?>" class="estilo3">Ultima</a></td>
    </tr>
  </table>
  <table width="700" align="center" class="tabla_contactos">
    <tr>
      <th scope="col">NRC</th>
      <th scope="col">UNIDAD DE APRENDIZAJE</th>
      <th scope="col">PROFESOR</th>
      <th scope="col">ACCION</th>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_rsNRCs['id_nrc']; ?></td>
        <td align="left"><?php echo $row_rsNRCs['ua']; ?></td>
        <td align="center"><?php echo $row_rsNRCs['id_profesor']; ?></td>
        <td align="center"><a href="nrc_edit.php" class="estilo3">Editar</a> | <a href="nrc_delete.php" class="estilo3">Eliminar</a></td>
      </tr>
      <?php } while ($row_rsNRCs = mysql_fetch_assoc($rsNRCs)); ?>
  </table>
  <table align="center">
    <tr>
      <td>Navegación:</td>
      <td><a href="<?php printf("%s?pageNum_rsNRCs=%d%s", $currentPage, 0, $queryString_rsNRCs); ?>" class="estilo3">Primera</a> | <a href="<?php printf("%s?pageNum_rsNRCs=%d%s", $currentPage, max(0, $pageNum_rsNRCs - 1), $queryString_rsNRCs); ?>" class="estilo3">Anterior</a> | <a href="<?php printf("%s?pageNum_rsNRCs=%d%s", $currentPage, min($totalPages_rsNRCs, $pageNum_rsNRCs + 1), $queryString_rsNRCs); ?>" class="estilo3">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsNRCs=%d%s", $currentPage, $totalPages_rsNRCs, $queryString_rsNRCs); ?>" class="estilo3">Ultima</a></td>
    </tr>
  </table>
  <h3 class="center"><a href="nrc_add.php" class="estilo3">Agregar nuevo NRC</a></h3>
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
mysql_free_result($rsNRCs);

mysql_free_result($rsProfesores);
?>
