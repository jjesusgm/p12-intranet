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

mysql_select_db($database_conP12, $conP12);
$query_rsResguardantes = "SELECT username, nivel_de_acceso, nombre_completo FROM usuarios WHERE nivel_de_acceso IN('Administrador','Administrativo','Profesor','Soporte')";
$rsResguardantes = mysql_query($query_rsResguardantes, $conP12) or die(mysql_error());
$row_rsResguardantes = mysql_fetch_assoc($rsResguardantes);
$totalRows_rsResguardantes = mysql_num_rows($rsResguardantes);
$query_rsResguardantes = "SELECT username, nivel_de_acceso, nombre_completo FROM usuarios WHERE nivel_de_acceso IN('Administrador','Administrativo','Profesor','Soporte') ORDER BY nombre_completo ASC";
$rsResguardantes = mysql_query($query_rsResguardantes, $conP12) or die(mysql_error());
$row_rsResguardantes = mysql_fetch_assoc($rsResguardantes);
$totalRows_rsResguardantes = mysql_num_rows($rsResguardantes);

mysql_select_db($database_conP12, $conP12);
$query_rsTiposDeDispositivo = "SELECT * FROM inv_computo_tipos ORDER BY tipo_dispositivo ASC";
$rsTiposDeDispositivo = mysql_query($query_rsTiposDeDispositivo, $conP12) or die(mysql_error());
$row_rsTiposDeDispositivo = mysql_fetch_assoc($rsTiposDeDispositivo);
$totalRows_rsTiposDeDispositivo = mysql_num_rows($rsTiposDeDispositivo);

mysql_select_db($database_conP12, $conP12);
$query_rsMarcas = "SELECT * FROM inv_computo_marcas ORDER BY nombre_marca ASC";
$rsMarcas = mysql_query($query_rsMarcas, $conP12) or die(mysql_error());
$row_rsMarcas = mysql_fetch_assoc($rsMarcas);
$totalRows_rsMarcas = mysql_num_rows($rsMarcas);

mysql_select_db($database_conP12, $conP12);
$query_rsUbicaciones = "SELECT * FROM p12_ubicaciones ORDER BY ub_nombre ASC";
$rsUbicaciones = mysql_query($query_rsUbicaciones, $conP12) or die(mysql_error());
$row_rsUbicaciones = mysql_fetch_assoc($rsUbicaciones);
$totalRows_rsUbicaciones = mysql_num_rows($rsUbicaciones);

$maxRows_rsInvComputo = 100;
$pageNum_rsInvComputo = 0;
if (isset($_GET['pageNum_rsInvComputo'])) {
  $pageNum_rsInvComputo = $_GET['pageNum_rsInvComputo'];
}
$startRow_rsInvComputo = $pageNum_rsInvComputo * $maxRows_rsInvComputo;

$varResguardante_rsInvComputo = "%";
if (isset($_POST['fResguardante'])) {
  $varResguardante_rsInvComputo = $_POST['fResguardante'];
}
$varDireccionIP_rsInvComputo = "%";
if (isset($_POST['fDireccionIP'])) {
  $varDireccionIP_rsInvComputo = $_POST['fDireccionIP'];
}
$varUbicacion_rsInvComputo = "%";
if (isset($_POST['fUbicacion'])) {
  $varUbicacion_rsInvComputo = $_POST['fUbicacion'];
}
$varNumeroInventario_rsInvComputo = "%";
if (isset($_POST['fNumeroInventario'])) {
  $varNumeroInventario_rsInvComputo = $_POST['fNumeroInventario'];
}
$varEstadoDispositivo_rsInvComputo = "%";
if (isset($_POST['fEstadoDispositivo'])) {
  $varEstadoDispositivo_rsInvComputo = $_POST['fEstadoDispositivo'];
}
$varNumeroSerie_rsInvComputo = "%";
if (isset($_POST['fNumeroSerie'])) {
  $varNumeroSerie_rsInvComputo = $_POST['fNumeroSerie'];
}
$varMarca_rsInvComputo = "%";
if (isset($_POST['fMarca'])) {
  $varMarca_rsInvComputo = $_POST['fMarca'];
}
$varTipoDispositivo_rsInvComputo = "%";
if (isset($_POST['fTipoDispositivo'])) {
  $varTipoDispositivo_rsInvComputo = $_POST['fTipoDispositivo'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsInvComputo = sprintf("SELECT ic.*, usr.nombre_completo, ict.tipo_dispositivo FROM inv_computo ic  INNER JOIN usuarios usr ON ic.id_resguardante = usr.username INNER JOIN inv_computo_tipos ict ON ic.id_tipo_dispositivo = ict.id_tipo_dispositivo WHERE ic.id_resguardante LIKE %s AND ic.id_tipo_dispositivo LIKE %s AND ic.id_marca LIKE %s AND ic.numero_serie LIKE %s AND ic.numero_inv_udg LIKE %s AND ic.ip_address LIKE %s AND ic.estado LIKE %s AND ic.id_ubicacion LIKE %s ORDER BY ic.id_dispositivo ASC", GetSQLValueString($varResguardante_rsInvComputo, "text"),GetSQLValueString($varTipoDispositivo_rsInvComputo, "text"),GetSQLValueString($varMarca_rsInvComputo, "text"),GetSQLValueString($varNumeroSerie_rsInvComputo, "text"),GetSQLValueString($varNumeroInventario_rsInvComputo, "text"),GetSQLValueString($varDireccionIP_rsInvComputo, "text"),GetSQLValueString($varEstadoDispositivo_rsInvComputo, "text"),GetSQLValueString($varUbicacion_rsInvComputo, "text"));
$query_limit_rsInvComputo = sprintf("%s LIMIT %d, %d", $query_rsInvComputo, $startRow_rsInvComputo, $maxRows_rsInvComputo);
$rsInvComputo = mysql_query($query_limit_rsInvComputo, $conP12) or die(mysql_error());
$row_rsInvComputo = mysql_fetch_assoc($rsInvComputo);

if (isset($_GET['totalRows_rsInvComputo'])) {
  $totalRows_rsInvComputo = $_GET['totalRows_rsInvComputo'];
} else {
  $all_rsInvComputo = mysql_query($query_rsInvComputo);
  $totalRows_rsInvComputo = mysql_num_rows($all_rsInvComputo);
}
$totalPages_rsInvComputo = ceil($totalRows_rsInvComputo/$maxRows_rsInvComputo)-1;

$queryString_rsInvComputo = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsInvComputo") == false && 
        stristr($param, "totalRows_rsInvComputo") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsInvComputo = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsInvComputo = sprintf("&totalRows_rsInvComputo=%d%s", $totalRows_rsInvComputo, $queryString_rsInvComputo);
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
	if(!f.fResguardante.value){
		f.fResguardante.value = "%";
	}
	if(!f.fTipoDispositivo.value){
		f.fTipoDispositivo.value = "%";
	}
	if(!f.fMarca.value){
		f.fMarca.value = "%";
	}
	if(!f.fNumeroSerie.value){
		f.fNumeroSerie.value = "%";
	}else{
		f.fNumeroSerie.value = "%"+f.fNumeroSerie.value+"%";
	}
	if(!f.fNumeroInventario.value){
		f.fNumeroInventario.value = "%";
	}else{
		f.fNumeroInventario.value = "%"+f.fNumeroInventario.value+"%";
	}
	if(!f.fDireccionIP.value){
		f.fDireccionIP.value = "%";
	}else{
		f.fDireccionIP.value = "%"+f.fDireccionIP.value+"%";
	}
	if(!f.fEstadoDispositivo.value){
		f.fEstadoDispositivo.value = "%";
	}
	if(!f.fUbicacion.value){
		f.fUbicacion.value = "%";
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Inventario &gt; Cómputo</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
<h2 class="center">Mostrando Dispositivos <?php echo ($startRow_rsInvComputo + 1) ?> al <?php echo min($startRow_rsInvComputo + $maxRows_rsInvComputo, $totalRows_rsInvComputo) ?> de <?php echo $totalRows_rsInvComputo ?></h2>
<div class="div_filtro">
  <form action="" method="post" name="form1" id="form1" onsubmit="return valida(this)">
  <fieldset><legend>Filtrar resultados</legend>
    <table width="100%">
      <tr>
        <td align="right"><label for="fResguardante">Resguardante</label></td>
        <td><select name="fResguardante" id="fResguardante">
          <option value="%">Todos</option>
          <option value="%Sin asignar%">Sin asignar</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsResguardantes['username']?>"><?php echo $row_rsResguardantes['nombre_completo']?></option>
          <?php
} while ($row_rsResguardantes = mysql_fetch_assoc($rsResguardantes));
  $rows = mysql_num_rows($rsResguardantes);
  if($rows > 0) {
      mysql_data_seek($rsResguardantes, 0);
	  $row_rsResguardantes = mysql_fetch_assoc($rsResguardantes);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fTipoDispositivo">Tipo de dispositivo</label></td>
        <td><select name="fTipoDispositivo" id="fTipoDispositivo">
          <option value="%">Todos</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsTiposDeDispositivo['id_tipo_dispositivo']?>"><?php echo $row_rsTiposDeDispositivo['tipo_dispositivo']?></option>
          <?php
} while ($row_rsTiposDeDispositivo = mysql_fetch_assoc($rsTiposDeDispositivo));
  $rows = mysql_num_rows($rsTiposDeDispositivo);
  if($rows > 0) {
      mysql_data_seek($rsTiposDeDispositivo, 0);
	  $row_rsTiposDeDispositivo = mysql_fetch_assoc($rsTiposDeDispositivo);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fMarca">Marca</label></td>
        <td><select name="fMarca" id="fMarca">
          <option value="%">Todas</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsMarcas['siglas']?>"><?php echo $row_rsMarcas['nombre_marca']?></option>
          <?php
} while ($row_rsMarcas = mysql_fetch_assoc($rsMarcas));
  $rows = mysql_num_rows($rsMarcas);
  if($rows > 0) {
      mysql_data_seek($rsMarcas, 0);
	  $row_rsMarcas = mysql_fetch_assoc($rsMarcas);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fNumeroSerie">Numero de serie</label></td>
        <td><input name="fNumeroSerie" type="text" id="fNumeroSerie" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNumeroInventario">Numero de inventario</label></td>
        <td><input name="fNumeroInventario" type="text" id="fNumeroInventario" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fDireccionIP">Direccion IP</label></td>
        <td><input name="fDireccionIP" type="text" id="fDireccionIP" size="20" maxlength="15" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fEstadoDispositivo">Estado del dispositivo</label></td>
        <td><select name="fEstadoDispositivo" id="fEstadoDispositivo">
          <option value="%">Todos</option>
          <option value="En operacion">En operacion</option>
          <option value="En reparacion">En reparacion</option>
          <option value="Guardada o en reserva">Guardada o en reserva</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fUbicacion">Ubicación</label></td>
        <td><select name="fUbicacion" id="fUbicacion">
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
        <td colspan="2" align="center"><input type="submit" name="fAgregarFiltro" id="fAgregarFiltro" value="Agregar filtro" /></td>
        </tr>
    </table>
      </fieldset>
  </form>
</div>
<h3 class="center"><a href="inv_comp_add.php" class="estilo3">Agregar nuevo dispositivo</a></h3>
<table align="center" class="TablaNavegacion">
  <tr>
    <td>Navegacion:</td>
    <td><a href="<?php printf("%s?pageNum_rsInvComputo=%d%s", $currentPage, 0, $queryString_rsInvComputo); ?>">Primera</a> | <a href="<?php printf("%s?pageNum_rsInvComputo=%d%s", $currentPage, max(0, $pageNum_rsInvComputo - 1), $queryString_rsInvComputo); ?>">Anterior</a> | <a href="<?php printf("%s?pageNum_rsInvComputo=%d%s", $currentPage, min($totalPages_rsInvComputo, $pageNum_rsInvComputo + 1), $queryString_rsInvComputo); ?>">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsInvComputo=%d%s", $currentPage, $totalPages_rsInvComputo, $queryString_rsInvComputo); ?>">Ultima</a></td>
    </tr>
</table>
<table width="100%" class="TablaListaInventario">
  <tr>
    <th scope="col">ID</th>
    <th scope="col">RESGUARDANTE</th>
    <th scope="col">TIPO</th>
    <th scope="col">MARCA</th>
    <th scope="col">No. SERIE</th>
    <th scope="col">No. INV</th>
    <th scope="col">NOMBRE</th>
    <th scope="col">DIR. IP</th>
    <th scope="col">ESTADO</th>
    <th scope="col">UBICACION</th>
    <th scope="col">ACCION</th>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_rsInvComputo['id_dispositivo']; ?></td>
      <td align="center"><?php echo $row_rsInvComputo['id_resguardante']; ?>&nbsp;<a href="#" class="NtooltipSmall"><img src="../imagenes/ver.gif" alt="Ver nombre" width="12" height="12" class="NtooltipSmall VAlignMiddle" /><span><?php echo $row_rsInvComputo['nombre_completo']; ?></span></a></td>
      <td align="center"><?php echo $row_rsInvComputo['tipo_dispositivo']; ?></td>
      <td align="center"><?php echo $row_rsInvComputo['id_marca']; ?></td>
      <td align="center"><?php echo $row_rsInvComputo['numero_serie']; ?></td>
      <td align="center"><?php echo $row_rsInvComputo['numero_inv_udg']; ?></td>
      <td align="center"><?php echo $row_rsInvComputo['nombre_dispositivo']; ?></td>
      <td align="center"><?php echo $row_rsInvComputo['ip_address']; ?></td>
      <td align="center"><?php echo $row_rsInvComputo['estado']; ?></td>
      <td align="center"><?php echo $row_rsInvComputo['id_ubicacion']; ?></td>
      <td align="center"><a href="inv_comp_edit.php?id_dispositivo=<?php echo $row_rsInvComputo['id_dispositivo']; ?>">Editar</a> | <a href="inv_comp_delete.php?id_dispositivo=<?php echo $row_rsInvComputo['id_dispositivo']; ?>">Eliminar</a></td>
    </tr>
    <?php } while ($row_rsInvComputo = mysql_fetch_assoc($rsInvComputo)); ?>
</table>
<table align="center" class="TablaNavegacion">
  <tr>
    <td>Navegacion:</td>
    <td><a href="<?php printf("%s?pageNum_rsInvComputo=%d%s", $currentPage, 0, $queryString_rsInvComputo); ?>">Primera</a> | <a href="<?php printf("%s?pageNum_rsInvComputo=%d%s", $currentPage, max(0, $pageNum_rsInvComputo - 1), $queryString_rsInvComputo); ?>">Anterior</a> | <a href="<?php printf("%s?pageNum_rsInvComputo=%d%s", $currentPage, min($totalPages_rsInvComputo, $pageNum_rsInvComputo + 1), $queryString_rsInvComputo); ?>">Siguiente</a> | <a href="<?php printf("%s?pageNum_rsInvComputo=%d%s", $currentPage, $totalPages_rsInvComputo, $queryString_rsInvComputo); ?>">Ultima</a></td>
    </tr>
</table>
<h3 class="center"><a href="inv_comp_add.php" class="estilo3">Agregar nuevo dispositivo</a></h3>
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
mysql_free_result($rsResguardantes);

mysql_free_result($rsTiposDeDispositivo);

mysql_free_result($rsMarcas);

mysql_free_result($rsUbicaciones);

mysql_free_result($rsInvComputo);
?>
