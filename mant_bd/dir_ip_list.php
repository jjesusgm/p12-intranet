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

$currentPage = $_SERVER["PHP_SELF"];

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

$maxRows_rsDirIp = 253;
$pageNum_rsDirIp = 0;
if (isset($_GET['pageNum_rsDirIp'])) {
  $pageNum_rsDirIp = $_GET['pageNum_rsDirIp'];
}
$startRow_rsDirIp = $pageNum_rsDirIp * $maxRows_rsDirIp;

$varSubRed_rsDirIp = "%";
if (isset($_POST['fSubRed'])) {
  $varSubRed_rsDirIp = $_POST['fSubRed'];
}
$varDirIp_rsDirIp = "%";
if (isset($_POST['fDirIp'])) {
  $varDirIp_rsDirIp = $_POST['fDirIp'];
}
$varIdUsuario_rsDirIp = "%";
if (isset($_POST['fIdUsuario'])) {
  $varIdUsuario_rsDirIp = $_POST['fIdUsuario'];
}
$varIdUbicacion_rsDirIp = "%";
if (isset($_POST['fIdUbicacion'])) {
  $varIdUbicacion_rsDirIp = $_POST['fIdUbicacion'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsDirIp = sprintf("SELECT * FROM p12_dir_ip WHERE sub_red LIKE %s AND dir_ip LIKE %s AND id_usuario LIKE %s AND id_ubicacion LIKE %s ORDER BY dir_ip ASC", GetSQLValueString($varSubRed_rsDirIp, "text"),GetSQLValueString($varDirIp_rsDirIp, "text"),GetSQLValueString($varIdUsuario_rsDirIp, "text"),GetSQLValueString($varIdUbicacion_rsDirIp, "text"));
$query_limit_rsDirIp = sprintf("%s LIMIT %d, %d", $query_rsDirIp, $startRow_rsDirIp, $maxRows_rsDirIp);
$rsDirIp = mysql_query($query_limit_rsDirIp, $conP12) or die(mysql_error());
$row_rsDirIp = mysql_fetch_assoc($rsDirIp);

if (isset($_GET['totalRows_rsDirIp'])) {
  $totalRows_rsDirIp = $_GET['totalRows_rsDirIp'];
} else {
  $all_rsDirIp = mysql_query($query_rsDirIp);
  $totalRows_rsDirIp = mysql_num_rows($all_rsDirIp);
}
$totalPages_rsDirIp = ceil($totalRows_rsDirIp/$maxRows_rsDirIp)-1;

$queryString_rsDirIp = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsDirIp") == false && 
        stristr($param, "totalRows_rsDirIp") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsDirIp = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsDirIp = sprintf("&totalRows_rsDirIp=%d%s", $totalRows_rsDirIp, $queryString_rsDirIp);
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
	if(!f.fDirIp.value){
		f.fDirIp.value = "%";
	}else{
		f.fDirIp.value = "%"+f.fDirIp.value+"%";
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Mantenimiento a BD &gt; Direcciones IP</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <?php if ($totalRows_rsDirIp > 0) { // Show if recordset not empty ?>
    <h2 class="center">Mostrando
      registros <?php echo ($startRow_rsDirIp + 1) ?> al <?php echo min($startRow_rsDirIp + $maxRows_rsDirIp, $totalRows_rsDirIp) ?> de <?php echo $totalRows_rsDirIp ?></h2>
    <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsDirIp == 0) { // Show if recordset empty ?>
  <h2 class="center">Nada que mostrar con este criterio de búsqueda</h2>
  <?php } // Show if recordset empty ?>
<div class="div_filtro" id="filtro">
    <form id="form1" name="form1" method="post" action="" onsubmit="return valida(this)" >
      <fieldset>
        <legend>Filtrar resultados</legend>
        <table width="100%">
          <tr>
            <td align="right"><label for="fSubRed">Subred</label></td>
            <td><select name="fSubRed" id="fSubRed">
              <option value="%">Ambas</option>
              <option value="47">47</option>
              <option value="76">76</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fDirIp">Dirección IP</label></td>
            <td><input name="fDirIp" type="text" id="fDirIp" size="20" maxlength="15" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fIdUsuario">Usuario</label></td>
            <td><select name="fIdUsuario" id="fIdUsuario">
              <option value="%">Todos</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rsUsuarios['username']?>"><?php echo $row_rsUsuarios['nombre_completo']?></option>
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
            <td colspan="2" align="center"><input type="submit" name="bAgregarFiltro" id="bAgregarFiltro" value="Agregar filtro" /></td>
          </tr>
        </table>
      </fieldset>
    </form>
  </div>
  <h3 class="center"><a href="dir_ip_add.php" class="estilo3">Agregar nueva dirección IP</a></h3>
  <?php if ($totalRows_rsDirIp > 0) { // Show if recordset not empty ?>
  <table align="center" class="TablaNavegacion">
    <tr>
      <td>Navegación:</td>
      <td><a href="<?php printf("%s?pageNum_rsDirIp=%d%s", $currentPage, 0, $queryString_rsDirIp); ?>">Primera</a> | <a href="<?php printf("%s?pageNum_rsDirIp=%d%s", $currentPage, max(0, $pageNum_rsDirIp - 1), $queryString_rsDirIp); ?>">Anterior</a> | <a href="<?php printf("%s?pageNum_rsDirIp=%d%s", $currentPage, min($totalPages_rsDirIp, $pageNum_rsDirIp + 1), $queryString_rsDirIp); ?>">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsDirIp=%d%s", $currentPage, $totalPages_rsDirIp, $queryString_rsDirIp); ?>">Ultima</a></td>
      </tr>
  </table>
    <table width="100%" class="TablaListaInventario">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">SUBRED</th>
        <th scope="col">IP</th>
        <th scope="col">MAC-ETH</th>
        <th scope="col">MAC-WIFI</th>
        <th scope="col">ASIGNADA</th>
        <th scope="col">HOST</th>
        <th scope="col">USUARIO</th>
        <th scope="col">UBICACION</th>
        <th scope="col">ACCION</th>
      </tr>
      <?php do { ?>
        <tr align="center">
          <td><?php echo $row_rsDirIp['id_dir_ip']; ?></td>
          <td><?php echo $row_rsDirIp['sub_red']; ?></td>
          <td><?php echo $row_rsDirIp['dir_ip']; ?></td>
          <td><?php echo $row_rsDirIp['mac_eth']; ?></td>
          <td><?php echo $row_rsDirIp['mac_wifi']; ?></td>
          <td><?php echo $row_rsDirIp['asignada']; ?></td>
          <td><?php echo $row_rsDirIp['nombre_host']; ?></td>
          <td><?php echo $row_rsDirIp['id_usuario']; ?></td>
          <td><?php echo $row_rsDirIp['id_ubicacion']; ?></td>
          <td><a href="dir_ip_edit.php?id_dir_ip=<?php echo $row_rsDirIp['id_dir_ip']; ?>">Editar</a> | <a href="dir_ip_delete.php?id_dir_ip=<?php echo $row_rsDirIp['id_dir_ip']; ?>">Eliminar</a></td>
        </tr>
        <?php } while ($row_rsDirIp = mysql_fetch_assoc($rsDirIp)); ?>
    </table>
    <table align="center" class="TablaNavegacion">
      <tr>
        <td>Navegación:</td>
        <td><a href="<?php printf("%s?pageNum_rsDirIp=%d%s", $currentPage, 0, $queryString_rsDirIp); ?>">Primera</a> | <a href="<?php printf("%s?pageNum_rsDirIp=%d%s", $currentPage, max(0, $pageNum_rsDirIp - 1), $queryString_rsDirIp); ?>">Anterior</a> | <a href="<?php printf("%s?pageNum_rsDirIp=%d%s", $currentPage, min($totalPages_rsDirIp, $pageNum_rsDirIp + 1), $queryString_rsDirIp); ?>">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsDirIp=%d%s", $currentPage, $totalPages_rsDirIp, $queryString_rsDirIp); ?>">Ultima</a></td>
      </tr>
    </table>
    <h3 class="center"><a href="dir_ip_add.php" class="estilo3">Agregar nueva dirección IP</a> </h3>
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
mysql_free_result($rsUsuarios);

mysql_free_result($rsUbicaciones);

mysql_free_result($rsDirIp);
?>
