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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE inv_computo SET id_resguardante=%s, id_tipo_dispositivo=%s, id_marca=%s, modelo=%s, numero_serie=%s, numero_inv_udg=%s, nombre_dispositivo=%s, ip_address=%s, mac_address_eth=%s, mac_address_wifi=%s, estado=%s, unidad_optica=%s, uso=%s, proc_marca=%s, proc_modelo=%s, proc_velocidad=%s, memoria=%s, so=%s, so_version=%s, id_tipo_monitor=%s, disco_duro=%s, ano_adquisicion=%s, tipo_adquisicion=%s, lan=%s, wan=%s, id_ubicacion=%s, comentario=%s WHERE id_dispositivo=%s",
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
                       GetSQLValueString($_POST['fComentario'], "text"),
                       GetSQLValueString($_POST['fIdDispositivo'], "int"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($updateSQL, $conP12) or die(mysql_error());

  $updateGoTo = "inv_computo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_conP12, $conP12);
$query_rsResguardantes = "SELECT username, nivel_de_acceso, nombre_completo FROM usuarios WHERE nivel_de_acceso IN('Administrador','Administrativo','Profesor','Soporte') ORDER BY nombre_completo ASC";
$rsResguardantes = mysql_query($query_rsResguardantes, $conP12) or die(mysql_error());
$row_rsResguardantes = mysql_fetch_assoc($rsResguardantes);
$totalRows_rsResguardantes = mysql_num_rows($rsResguardantes);

$colname_rsDispositivo = "-1";
if (isset($_GET['id_dispositivo'])) {
  $colname_rsDispositivo = $_GET['id_dispositivo'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsDispositivo = sprintf("SELECT * FROM inv_computo WHERE id_dispositivo = %s", GetSQLValueString($colname_rsDispositivo, "int"));
$rsDispositivo = mysql_query($query_rsDispositivo, $conP12) or die(mysql_error());
$row_rsDispositivo = mysql_fetch_assoc($rsDispositivo);
$totalRows_rsDispositivo = mysql_num_rows($rsDispositivo);

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
<div id="div_hdr_path">&nbsp;Inicio &gt; Inventario &gt; Cómputo &gt; Modificar</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Modificando dispositivo de cómputo
  </h2>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center" class="tabla_usuario">
      <tr>
        <th colspan="2" align="left" scope="col">Registro del dispositivo de cómputo a modificar</th>
      </tr>
      <tr>
        <td align="right"><label for="fIdDispositivo">ID del dispositivo</label></td>
        <td><input name="fIdDispositivo" type="text" id="fIdDispositivo" value="<?php echo $row_rsDispositivo['id_dispositivo']; ?>" size="12" maxlength="10" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fResguardante">Resguardante</label></td>
        <td><select name="fResguardante" id="fResguardante">
          <option value="Sin asignar" <?php if (!(strcmp("Sin asignar", $row_rsDispositivo['id_resguardante']))) {echo "selected=\"selected\"";} ?>>Sin asignar</option>
          <?php
do {  
?>
<option value="<?php echo $row_rsResguardantes['username']?>"<?php if (!(strcmp($row_rsResguardantes['username'], $row_rsDispositivo['id_resguardante']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsResguardantes['nombre_completo']?></option>
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
          <option value="<?php echo $row_rsTiposDeDispositivo['id_tipo_dispositivo']?>"<?php if (!(strcmp($row_rsTiposDeDispositivo['id_tipo_dispositivo'], $row_rsDispositivo['id_tipo_dispositivo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTiposDeDispositivo['tipo_dispositivo']?></option>
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
          <?php
do {  
?>
          <option value="<?php echo $row_rsMarcas['siglas']?>"<?php if (!(strcmp($row_rsMarcas['siglas'], $row_rsDispositivo['id_marca']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsMarcas['nombre_marca']?></option>
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
        <td><input name="fModelo" type="text" id="fModelo" value="<?php echo $row_rsDispositivo['modelo']; ?>" size="22" maxlength="20" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNumeroDeSerie">Número de serie</label></td>
        <td><input name="fNumeroDeSerie" type="text" id="fNumeroDeSerie" value="<?php echo $row_rsDispositivo['numero_serie']; ?>" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNumeroDeInventario">Número de inventario (U de G)</label></td>
        <td><input name="fNumeroDeInventario" type="text" id="fNumeroDeInventario" value="<?php echo $row_rsDispositivo['numero_inv_udg']; ?>" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNombreDelDispositivo">Nombre del dispositivo</label></td>
        <td><input name="fNombreDelDispositivo" type="text" id="fNombreDelDispositivo" value="<?php echo $row_rsDispositivo['nombre_dispositivo']; ?>" size="50" maxlength="50" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fDireccionIP">Direccion IP</label></td>
        <td><input name="fDireccionIP" type="text" id="fDireccionIP" value="<?php echo $row_rsDispositivo['ip_address']; ?>" size="17" maxlength="15" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fMacAddressEth">MAC Address Ethernet</label></td>
        <td><input name="fMacAddressEth" type="text" id="fMacAddressEth" value="<?php echo $row_rsDispositivo['mac_address_eth']; ?>" size="20" maxlength="17" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fMacAddressWiFi">MAC Address WiFi</label></td>
        <td><input name="fMacAddressWiFi" type="text" id="fMacAddressWiFi" value="<?php echo $row_rsDispositivo['mac_address_wifi']; ?>" size="20" maxlength="17" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fEstado">Estado</label></td>
        <td><select name="fEstado" id="fEstado">
          <option value="En operacion" <?php if (!(strcmp("En operacion", $row_rsDispositivo['estado']))) {echo "selected=\"selected\"";} ?>>En operacion</option>
          <option value="En reparacion" <?php if (!(strcmp("En reparacion", $row_rsDispositivo['estado']))) {echo "selected=\"selected\"";} ?>>En reparacion</option>
          <option value="Guardada o en reserva" <?php if (!(strcmp("Guardada o en reserva", $row_rsDispositivo['estado']))) {echo "selected=\"selected\"";} ?>>Guardada o en reserva</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fUnidadOptica">Unidad optica</label></td>
        <td><select name="fUnidadOptica" id="fUnidadOptica">
          <option value="" <?php if (!(strcmp("", $row_rsDispositivo['unidad_optica']))) {echo "selected=\"selected\"";} ?>>No aplica</option>
          <option value="No" <?php if (!(strcmp("No", $row_rsDispositivo['unidad_optica']))) {echo "selected=\"selected\"";} ?>>No</option>
          <option value="Si" <?php if (!(strcmp("Si", $row_rsDispositivo['unidad_optica']))) {echo "selected=\"selected\"";} ?>>Si</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fUso">Uso</label></td>
        <td><select name="fUso" id="fUso">
          <option value="Administrativo" <?php if (!(strcmp("Administrativo", $row_rsDispositivo['uso']))) {echo "selected=\"selected\"";} ?>>Administrativo</option>
          <option value="Docente" <?php if (!(strcmp("Docente", $row_rsDispositivo['uso']))) {echo "selected=\"selected\"";} ?>>Docente</option>
          <option value="Educativo" <?php if (!(strcmp("Educativo", $row_rsDispositivo['uso']))) {echo "selected=\"selected\"";} ?>>Educativo</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fProcMarca">Marca del procesador</label></td>
        <td><select name="fProcMarca" id="fProcMarca">
          <option value="" <?php if (!(strcmp("", $row_rsDispositivo['proc_marca']))) {echo "selected=\"selected\"";} ?>>No aplica</option>
          <option value="AMD" <?php if (!(strcmp("AMD", $row_rsDispositivo['proc_marca']))) {echo "selected=\"selected\"";} ?>>AMD</option>
          <option value="Intel" <?php if (!(strcmp("Intel", $row_rsDispositivo['proc_marca']))) {echo "selected=\"selected\"";} ?>>Intel</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fProcModelo">Modelo del procesador</label></td>
        <td><input name="fProcModelo" type="text" id="fProcModelo" value="<?php echo $row_rsDispositivo['proc_modelo']; ?>" size="35" maxlength="30" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fProcVelocidad">Velocidad del procesador</label></td>
        <td><input name="fProcVelocidad" type="text" id="fProcVelocidad" value="<?php echo $row_rsDispositivo['proc_velocidad']; ?>" size="12" maxlength="10" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fMemoria">Memoria</label></td>
        <td><select name="fMemoria" id="fMemoria">
          <option value="" <?php if (!(strcmp("", $row_rsDispositivo['memoria']))) {echo "selected=\"selected\"";} ?>>No aplica</option>
          <option value="1 GB o menos" <?php if (!(strcmp("1 GB o menos", $row_rsDispositivo['memoria']))) {echo "selected=\"selected\"";} ?>>1 GB o menos</option>
          <option value="2 o 3 GB" <?php if (!(strcmp("2 o 3 GB", $row_rsDispositivo['memoria']))) {echo "selected=\"selected\"";} ?>>2 o 3 GB</option>
          <option value="4 GB o mas" <?php if (!(strcmp("4 GB o mas", $row_rsDispositivo['memoria']))) {echo "selected=\"selected\"";} ?>>4 GB o mas</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fSO">Sistema operativo</label></td>
        <td><select name="fSO" id="fSO">
          <option value="" <?php if (!(strcmp("", $row_rsDispositivo['so']))) {echo "selected=\"selected\"";} ?>>No aplica</option>
          <option value="Linux" <?php if (!(strcmp("Linux", $row_rsDispositivo['so']))) {echo "selected=\"selected\"";} ?>>Linux</option>
          <option value="Mac OS" <?php if (!(strcmp("Mac OS", $row_rsDispositivo['so']))) {echo "selected=\"selected\"";} ?>>Mac OS</option>
          <option value="Solaris" <?php if (!(strcmp("Solaris", $row_rsDispositivo['so']))) {echo "selected=\"selected\"";} ?>>Solaris</option>
          <option value="Windows" <?php if (!(strcmp("Windows", $row_rsDispositivo['so']))) {echo "selected=\"selected\"";} ?>>Windows</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fSOVersion">Version del sistema operativo</label></td>
        <td><input name="fSOVersion" type="text" id="fSOVersion" value="<?php echo $row_rsDispositivo['so_version']; ?>" size="35" maxlength="30" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fTipoDeMonitor">Tipo de monitor</label></td>
        <td><select name="fTipoDeMonitor" id="fTipoDeMonitor">
          <?php
do {  
?>
          <option value="<?php echo $row_rsTiposDeMonitor['siglas']?>"<?php if (!(strcmp($row_rsTiposDeMonitor['siglas'], $row_rsDispositivo['id_tipo_monitor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTiposDeMonitor['tm_nombre']?></option>
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
          <option value="" <?php if (!(strcmp("", $row_rsDispositivo['disco_duro']))) {echo "selected=\"selected\"";} ?>>No aplica</option>
          <option value="30 GB o menos" <?php if (!(strcmp("30 GB o menos", $row_rsDispositivo['disco_duro']))) {echo "selected=\"selected\"";} ?>>30 GB o menos</option>
          <option value="31 a 200 GB" <?php if (!(strcmp("31 a 200 GB", $row_rsDispositivo['disco_duro']))) {echo "selected=\"selected\"";} ?>>31 a 200 GB</option>
          <option value="201 GB o mas" <?php if (!(strcmp("201 GB o mas", $row_rsDispositivo['disco_duro']))) {echo "selected=\"selected\"";} ?>>201 GB o mas</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fAnoDeAdquisicion">Año de adquisición</label></td>
        <td><input name="fAnoDeAdquisicion" type="text" id="fAnoDeAdquisicion" value="<?php echo $row_rsDispositivo['ano_adquisicion']; ?>" size="6" maxlength="4" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fTipoDeAdquisicion">Tipo de adquisición</label></td>
        <td><select name="fTipoDeAdquisicion" id="fTipoDeAdquisicion">
          <option value="Comprada" <?php if (!(strcmp("Comprada", $row_rsDispositivo['tipo_adquisicion']))) {echo "selected=\"selected\"";} ?>>Comprada</option>
          <option value="Dependencia administrativa" <?php if (!(strcmp("Dependencia administrativa", $row_rsDispositivo['tipo_adquisicion']))) {echo "selected=\"selected\"";} ?>>Dependencia administrativa</option>
          <option value="Donada" <?php if (!(strcmp("Donada", $row_rsDispositivo['tipo_adquisicion']))) {echo "selected=\"selected\"";} ?>>Donada</option>
          <option value="Rentada" <?php if (!(strcmp("Rentada", $row_rsDispositivo['tipo_adquisicion']))) {echo "selected=\"selected\"";} ?>>Rentada</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fLAN">LAN</label></td>
        <td><select name="fLAN" id="fLAN">
          <option value="" <?php if (!(strcmp("", $row_rsDispositivo['lan']))) {echo "selected=\"selected\"";} ?>>No aplica</option>
          <option value="No" <?php if (!(strcmp("No", $row_rsDispositivo['lan']))) {echo "selected=\"selected\"";} ?>>No</option>
          <option value="Si" <?php if (!(strcmp("Si", $row_rsDispositivo['lan']))) {echo "selected=\"selected\"";} ?>>Si</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fWAN">WAN</label></td>
        <td><select name="fWAN" id="fWAN">
          <option value="" <?php if (!(strcmp("", $row_rsDispositivo['wan']))) {echo "selected=\"selected\"";} ?>>No aplica</option>
          <option value="No" <?php if (!(strcmp("No", $row_rsDispositivo['wan']))) {echo "selected=\"selected\"";} ?>>No</option>
          <option value="Si" <?php if (!(strcmp("Si", $row_rsDispositivo['wan']))) {echo "selected=\"selected\"";} ?>>Si</option>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fUbicacion">Ubicación</label></td>
        <td><select name="fUbicacion" id="fUbicacion">
          <?php
do {  
?>
          <option value="<?php echo $row_rsUbicaciones['id_ubicacion']?>"<?php if (!(strcmp($row_rsUbicaciones['id_ubicacion'], $row_rsDispositivo['id_ubicacion']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsUbicaciones['ub_nombre']?></option>
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
        <td><input name="fComentario" type="text" id="fComentario" value="<?php echo $row_rsDispositivo['comentario']; ?>" size="75" maxlength="100" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="bModificar" id="bModificar" value="Modificar" />
        <input type="reset" name="bRestablecer" id="bRestablecer" value="Restablecer" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
    <input type="hidden" name="MM_update" value="form1" />
  </form>
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

mysql_free_result($rsDispositivo);

mysql_free_result($rsTiposDeDispositivo);

mysql_free_result($rsMarcas);

mysql_free_result($rsTiposDeMonitor);

mysql_free_result($rsUbicaciones);
?>
