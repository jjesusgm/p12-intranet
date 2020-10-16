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
  $insertSQL = sprintf("INSERT INTO inv_computo (id_resguardante, id_tipo_dispositivo, id_marca, modelo, numero_serie, numero_inv_udg, nombre_dispositivo, ip_address, mac_address_eth, mac_address_wifi, estado, unidad_optica, uso, proc_marca, proc_modelo, proc_velocidad, memoria, so, so_version, id_tipo_monitor, disco_duro, ano_adquisicion, tipo_adquisicion, lan, wan, id_ubicacion, comentario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fResguardante'], "text"),
                       GetSQLValueString($_POST['fTipoDeDispositivo'], "int"),
                       GetSQLValueString($_POST['fMarca'], "text"),
                       GetSQLValueString($_POST['fModelo'], "text"),
                       GetSQLValueString($_POST['fNumeroDeSerie'], "text"),
                       GetSQLValueString($_POST['fNumeroDeInventario'], "text"),
                       GetSQLValueString($_POST['fNombreDelDispositivo'], "text"),
                       GetSQLValueString($_POST['fDireccionIP'], "text"),
                       GetSQLValueString($_POST['fMacAddressEth'], "text"),
                       GetSQLValueString($_POST['fMacAddressWiFi'], "text"),
                       GetSQLValueString($_POST['fEstado'], "text"),
                       GetSQLValueString($_POST['fUnidadOptica'], "text"),
                       GetSQLValueString($_POST['fUso'], "text"),
                       GetSQLValueString($_POST['fProcMarca'], "text"),
                       GetSQLValueString($_POST['fProcModelo'], "text"),
                       GetSQLValueString($_POST['fProcVelocidad'], "text"),
                       GetSQLValueString($_POST['fMemoria'], "text"),
                       GetSQLValueString($_POST['fSO'], "text"),
                       GetSQLValueString($_POST['fSOVersion'], "text"),
                       GetSQLValueString($_POST['fTipoDeMonitor'], "text"),
                       GetSQLValueString($_POST['fDiscoDuro'], "text"),
                       GetSQLValueString($_POST['fAnoDeAdquisicion'], "int"),
                       GetSQLValueString($_POST['fTipoDeAdquisicion'], "text"),
                       GetSQLValueString($_POST['fLAN'], "text"),
                       GetSQLValueString($_POST['fWAN'], "text"),
                       GetSQLValueString($_POST['fUbicacion'], "text"),
                       GetSQLValueString($_POST['fComentario'], "text"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($insertSQL, $conP12) or die(mysql_error());

  $insertGoTo = "inv_computo.php";
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

mysql_select_db($database_conP12, $conP12);
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
$query_rsTiposDeMonitor = "SELECT * FROM inv_computo_tipos_monitor ORDER BY tm_nombre ASC";
$rsTiposDeMonitor = mysql_query($query_rsTiposDeMonitor, $conP12) or die(mysql_error());
$row_rsTiposDeMonitor = mysql_fetch_assoc($rsTiposDeMonitor);
$totalRows_rsTiposDeMonitor = mysql_num_rows($rsTiposDeMonitor);

mysql_select_db($database_conP12, $conP12);
$query_rsUbicaciones = "SELECT * FROM p12_ubicaciones ORDER BY ub_nombre ASC";
$rsUbicaciones = mysql_query($query_rsUbicaciones, $conP12) or die(mysql_error());
$row_rsUbicaciones = mysql_fetch_assoc($rsUbicaciones);
$totalRows_rsUbicaciones = mysql_num_rows($rsUbicaciones);
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
	if(!f.fNumeroDeSerie.value){
		f.fNumeroDeSerie.value = "SN";
	}
	if(!f.fNumeroDeInventario.value){
		f.fNumeroDeInventario.value = "SN";
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Inventario &gt; Computo &gt; Agregar</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <table width="90%" align="center">
    <tr>
      <td><h1 class="H_Estilo1">Agregar un dispositivo de cómputo</h1></td>
    </tr>
    <tr>
      <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return valida(this)">
        <table align="center" class="tabla_usuario">
          <tr>
            <th colspan="2" align="left" scope="col">Registro del dispositivo de cómputo</th>
          </tr>
          <tr>
            <td align="right"><label for="fResguardante">Resguardante</label></td>
            <td><select name="fResguardante" id="fResguardante">
              <option value="Sin asignar">Sin asignar</option>
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
            <td align="right"><label for="fTipoDeDispositivo">Tipo de dispositivo</label></td>
            <td><select name="fTipoDeDispositivo" id="fTipoDeDispositivo">
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
              <option value="Sin marca">Sin marca</option>
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
            <td align="right"><label for="fModelo">Modelo</label></td>
            <td><input name="fModelo" type="text" id="fModelo" size="20" maxlength="20" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fNumeroDeSerie">Número de serie</label></td>
            <td><input name="fNumeroDeSerie" type="text" id="fNumeroDeSerie" size="50" maxlength="50" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fNumeroDeInventario">Número de inventario (U de G)</label></td>
            <td><input name="fNumeroDeInventario" type="text" id="fNumeroDeInventario" size="50" maxlength="50" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fNombreDelDispositivo">Nombre del dispositivo</label></td>
            <td><input name="fNombreDelDispositivo" type="text" id="fNombreDelDispositivo" size="50" maxlength="50" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fDireccionIP">Direccion IP</label></td>
            <td><input name="fDireccionIP" type="text" id="fDireccionIP" size="20" maxlength="15" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fMacAddressEth">MAC Address Ethernet</label></td>
            <td><input name="fMacAddressEth" type="text" id="fMacAddressEth" size="20" maxlength="17" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fMacAddressWiFi">MAC Address WiFi</label></td>
            <td><input name="fMacAddressWiFi" type="text" id="fMacAddressWiFi" size="20" maxlength="17" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fEstado">Estado</label></td>
            <td><select name="fEstado" id="fEstado">
              <option value="En operacion">En operacion</option>
              <option value="En reparacion">En reparacion</option>
              <option value="Guardada o en reserva">Guardada o en reserva</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fUnidadOptica">Unidad optica</label></td>
            <td><select name="fUnidadOptica" id="fUnidadOptica">
              <option value="No aplica">No aplica</option>
              <option value="No">No</option>
              <option value="Si">Si</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fUso">Uso</label></td>
            <td><select name="fUso" id="fUso">
              <option value="Administrativo">Administrativo</option>
              <option value="Docente">Docente</option>
              <option value="Educativo">Educativo</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fProcMarca">Marca del procesador</label></td>
            <td><select name="fProcMarca" id="fProcMarca">
              <option value="No aplica">No aplica</option>
              <option value="AMD">AMD</option>
              <option value="Intel">Intel</option>
              <option value="PowerPC">Power PC</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fProcModelo">Modelo del procesador</label></td>
            <td><input name="fProcModelo" type="text" id="fProcModelo" size="35" maxlength="30" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fProcVelocidad">Velocidad del procesador</label></td>
            <td><input name="fProcVelocidad" type="text" id="fProcVelocidad" size="10" maxlength="10" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fMemoria">Memoria</label></td>
            <td><select name="fMemoria" id="fMemoria">
              <option>No aplica</option>
              <option value="1 GB o menos">1 GB o menos</option>
              <option value="2 o 3 GB">2 o 3 GB</option>
              <option value="4 GB o mas">4 GB o mas</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fSO">Sistema operativo</label></td>
            <td><select name="fSO" id="fSO">
              <option value="No aplica">No aplica</option>
              <option value="Linux">Linux</option>
              <option value="Mac OS">Mac OS</option>
              <option value="Solaris">Solaris</option>
              <option value="Windows">Windows</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fSOVersion">Version del sistema operativo</label></td>
            <td><input name="fSOVersion" type="text" id="fSOVersion" size="35" maxlength="30" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fTipoDeMonitor">Tipo de monitor</label></td>
            <td><select name="fTipoDeMonitor" id="fTipoDeMonitor">
              <?php
do {  
?>
              <option value="<?php echo $row_rsTiposDeMonitor['siglas']?>"><?php echo $row_rsTiposDeMonitor['tm_nombre']?></option>
              <?php
} while ($row_rsTiposDeMonitor = mysql_fetch_assoc($rsTiposDeMonitor));
  $rows = mysql_num_rows($rsTiposDeMonitor);
  if($rows > 0) {
      mysql_data_seek($rsTiposDeMonitor, 0);
	  $row_rsTiposDeMonitor = mysql_fetch_assoc($rsTiposDeMonitor);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fDiscoDuro">Disco duro</label></td>
            <td><select name="fDiscoDuro" id="fDiscoDuro">
              <option value="No aplica">No aplica</option>
              <option value="30 GB o menos">30 GB o menos</option>
              <option value="31 a 200 GB">31 a 200 GB</option>
              <option value="201 GB o mas">201 GB o mas</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fAnoDeAdquisicion">Año de adquisición</label></td>
            <td><input name="fAnoDeAdquisicion" type="text" id="fAnoDeAdquisicion" size="4" maxlength="4" /></td>
          </tr>
          <tr>
            <td align="right"><label for="fTipoDeAdquisicion">Tipo de adquisición</label></td>
            <td><select name="fTipoDeAdquisicion" id="fTipoDeAdquisicion">
              <option value="Comprada">Comprada</option>
              <option value="Dependencia administrativa">Dependencia administrativa</option>
              <option value="Donada">Donada</option>
              <option value="Rentada">Rentada</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fLAN">LAN</label></td>
            <td><select name="fLAN" id="fLAN">
              <option value="No aplica">No aplica</option>
              <option value="No">No</option>
              <option value="Si">Si</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fWAN">WAN</label></td>
            <td><select name="fWAN" id="fWAN">
              <option value="No aplica">No aplica</option>
              <option value="No">No</option>
              <option value="Si">Si</option>
            </select></td>
          </tr>
          <tr>
            <td align="right"><label for="fUbicacion">Ubicación</label></td>
            <td><select name="fUbicacion" id="fUbicacion">
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
            <td align="right"><label for="fComentario">Comentario</label></td>
            <td><input name="fComentario" type="text" id="fComentario" size="100" maxlength="100" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center"><input type="submit" name="bAgregar" id="bAgregar" value="Agregar" />
              <input type="reset" name="bRestablecer" id="bRestablecer" value="Restablecer" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
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
mysql_free_result($rsResguardantes);

mysql_free_result($rsTiposDeDispositivo);

mysql_free_result($rsMarcas);

mysql_free_result($rsTiposDeMonitor);

mysql_free_result($rsUbicaciones);
?>
