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

$colname_rsDispositivo = "1";
if (isset($_GET['id_dispositivo'])) {
  $colname_rsDispositivo = $_GET['id_dispositivo'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsDispositivo = sprintf("SELECT iec.*, u.nombre_completo, itd.tipo_dispositivo, mrk.nombre_marca, itm.tm_nombre, ubi.ub_nombre FROM inv_equipo_computo iec INNER JOIN usuarios u ON iec.id_resguardante = u.username INNER JOIN inv_tipos_dispositivo itd ON iec.id_tipo_dispositivo = itd.id INNER JOIN inv_marcas mrk ON iec.marca = mrk.siglas INNER JOIN inv_tipos_monitor itm ON iec.tipo_monitor = itm.siglas INNER JOIN p12_ubicaciones ubi ON iec.ubicacion = ubi.siglas WHERE id_dispositivo = %s", GetSQLValueString($colname_rsDispositivo, "int"));
$rsDispositivo = mysql_query($query_rsDispositivo, $conP12) or die(mysql_error());
$row_rsDispositivo = mysql_fetch_assoc($rsDispositivo);
$totalRows_rsDispositivo = mysql_num_rows($rsDispositivo);

mysql_select_db($database_conP12, $conP12);
$query_rsResguardantes = "SELECT username, nivel_de_acceso, nombre_completo FROM usuarios WHERE nivel_de_acceso IN('Administrador','Administrativo','Profesor','Soporte') ORDER BY nombre_completo ASC";
$rsResguardantes = mysql_query($query_rsResguardantes, $conP12) or die(mysql_error());
$row_rsResguardantes = mysql_fetch_assoc($rsResguardantes);
$totalRows_rsResguardantes = mysql_num_rows($rsResguardantes);

mysql_select_db($database_conP12, $conP12);
$query_rsTiposDeDispositivo = "SELECT * FROM inv_tipos_dispositivo ORDER BY tipo_dispositivo ASC";
$rsTiposDeDispositivo = mysql_query($query_rsTiposDeDispositivo, $conP12) or die(mysql_error());
$row_rsTiposDeDispositivo = mysql_fetch_assoc($rsTiposDeDispositivo);
$totalRows_rsTiposDeDispositivo = mysql_num_rows($rsTiposDeDispositivo);

mysql_select_db($database_conP12, $conP12);
$query_rsMarcas = "SELECT * FROM inv_marcas ORDER BY nombre_marca ASC";
$rsMarcas = mysql_query($query_rsMarcas, $conP12) or die(mysql_error());
$row_rsMarcas = mysql_fetch_assoc($rsMarcas);
$totalRows_rsMarcas = mysql_num_rows($rsMarcas);

mysql_select_db($database_conP12, $conP12);
$query_rsTiposDeMonitor = "SELECT * FROM inv_tipos_monitor ORDER BY tm_nombre ASC";
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Inventario &gt; Cómputo &gt; Eliminar</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Eliminando registro de dispositivo de cómputo
  </h2>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center" class="tabla_usuario">
      <tr>
        <th colspan="2" align="left" scope="col">Registro del dispositivo de cómputo a eliminar</th>
      </tr>
      <tr>
        <td align="right"><label for="fIdDispositivo">ID del dispositivo</label></td>
        <td><input name="fIdDispositivo" type="text" id="fIdDispositivo" value="<?php echo $row_rsDispositivo['id_dispositivo']; ?>" size="10" maxlength="10" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fResguardante">Resguardante</label></td>
        <td><input name="fResguardante" type="text" id="fResguardante" value="<?php echo $row_rsDispositivo['id_resguardante']; ?>" size="10" maxlength="20" readonly />
        <?php echo $row_rsDispositivo['nombre_completo']; ?></td>
      </tr>
      <tr>
        <td align="right"><label for="fTipoDeDispositivo">Tipo de dispositivo</label></td>
        <td><input name="fTipoDeDispositivo" type="text" id="fTipoDeDispositivo" value="<?php echo $row_rsDispositivo['id_tipo_dispositivo']; ?>" size="3" maxlength="3" readonly />
        <?php echo $row_rsDispositivo['tipo_dispositivo']; ?></td>
      </tr>
      <tr>
        <td align="right"><label for="fMarca">Marca</label></td>
        <td><input name="fMarca" type="text" id="fMarca" value="<?php echo $row_rsDispositivo['marca']; ?>" size="10" maxlength="20" readonly />
        <?php echo $row_rsDispositivo['nombre_marca']; ?></td>
      </tr>
      <tr>
        <td align="right"><label for="fModelo">Modelo</label></td>
        <td><input name="fModelo" type="text" id="fModelo" value="<?php echo $row_rsDispositivo['modelo']; ?>" size="20" maxlength="20" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNumeroDeSerie">Número de serie</label></td>
        <td><input name="fNumeroDeSerie" type="text" id="fNumeroDeSerie" value="<?php echo $row_rsDispositivo['numero_serie']; ?>" size="50" maxlength="50" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNumeroDeInventario">Número de inventario (U de G)</label></td>
        <td><input name="fNumeroDeInventario" type="text" id="fNumeroDeInventario" value="<?php echo $row_rsDispositivo['numero_inv_udg']; ?>" size="50" maxlength="50" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNombreDelDispositivo">Nombre del dispositivo</label></td>
        <td><input name="fNombreDelDispositivo" type="text" id="fNombreDelDispositivo" value="<?php echo $row_rsDispositivo['nombre_dispositivo']; ?>" size="50" maxlength="50" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fDireccionIP">Direccion IP</label></td>
        <td><input name="fDireccionIP" type="text" id="fDireccionIP" value="<?php echo $row_rsDispositivo['ip_address']; ?>" size="15" maxlength="15" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fMacAddressEth">MAC Address Ethernet</label></td>
        <td><input name="fMacAddressEth" type="text" id="fMacAddressEth" value="<?php echo $row_rsDispositivo['mac_address_eth']; ?>" size="17" maxlength="17" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fMacAddressWiFi">MAC Address WiFi</label></td>
        <td><input name="fMacAddressWiFi" type="text" id="fMacAddressWiFi" value="<?php echo $row_rsDispositivo['mac_address_wifi']; ?>" size="17" maxlength="17" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fEstado">Estado</label></td>
        <td><input name="fEstado" type="text" id="fEstado" value="<?php echo $row_rsDispositivo['estado']; ?>" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fUnidadOptica">Unidad optica</label></td>
        <td><input name="fUnidadOptica" type="text" id="fUnidadOptica" value="<?php echo $row_rsDispositivo['unidad_optica']; ?>" size="2" maxlength="2" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fUso">Uso</label></td>
        <td><input name="fUso" type="text" id="fUso" value="<?php echo $row_rsDispositivo['uso']; ?>" size="15" maxlength="15" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fProcMarca">Marca del procesador</label></td>
        <td><input name="fProcMarca" type="text" id="fProcMarca" value="<?php echo $row_rsDispositivo['proc_marca']; ?>" size="10" maxlength="10" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fProcModelo">Modelo del procesador</label></td>
        <td><input name="fProcModelo" type="text" id="fProcModelo" value="<?php echo $row_rsDispositivo['proc_modelo']; ?>" size="20" maxlength="20" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fProcVelocidad">Velocidad del procesador</label></td>
        <td><input name="fProcVelocidad" type="text" id="fProcVelocidad" value="<?php echo $row_rsDispositivo['proc_velocidad']; ?>" size="10" maxlength="10" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fMemoria">Memoria</label></td>
        <td><input name="fMemoria" type="text" id="fMemoria" value="<?php echo $row_rsDispositivo['memoria']; ?>" size="15" maxlength="15" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fSO">Sistema operativo</label></td>
        <td><input name="fSO" type="text" id="fSO" value="<?php echo $row_rsDispositivo['so']; ?>" size="15" maxlength="15" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fSOVersion">Version del sistema operativo</label></td>
        <td><input name="fSOVersion" type="text" id="fSOVersion" value="<?php echo $row_rsDispositivo['so_version']; ?>" size="20" maxlength="20" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fTipoDeMonitor">Tipo de monitor</label></td>
        <td><input name="fTipoDeMonitor" type="text" id="fTipoDeMonitor" value="<?php echo $row_rsDispositivo['tipo_monitor']; ?>" size="10" maxlength="10" readonly />
        <?php echo $row_rsDispositivo['tm_nombre']; ?></td>
      </tr>
      <tr>
        <td align="right"><label for="fDiscoDuro">Disco duro</label></td>
        <td><input name="fDiscoDuro" type="text" id="fDiscoDuro" value="<?php echo $row_rsDispositivo['disco_duro']; ?>" size="15" maxlength="15" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fAnoDeAdquisicion">Año de adquisición</label></td>
        <td><input name="fAnoDeAdquisicion" type="text" id="fAnoDeAdquisicion" value="<?php echo $row_rsDispositivo['ano_adquisicion']; ?>" size="4" maxlength="4" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fTipoDeAdquisicion">Tipo de adquisición</label></td>
        <td><input name="fTipoDeAdquisicion" type="text" id="fTipoDeAdquisicion" value="<?php echo $row_rsDispositivo['tipo_adquisicion']; ?>" size="25" maxlength="25" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fLAN">LAN</label></td>
        <td><input name="fLAN" type="text" id="fLAN" value="<?php echo $row_rsDispositivo['lan']; ?>" size="2" maxlength="2" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fWAN">WAN</label></td>
        <td><input name="fWAN" type="text" id="fWAN" value="<?php echo $row_rsDispositivo['wan']; ?>" size="2" maxlength="2" readonly /></td>
      </tr>
      <tr>
        <td align="right"><label for="fUbicacion">Ubicación</label></td>
        <td><input name="fUbicacion" type="text" id="fUbicacion" value="<?php echo $row_rsDispositivo['ubicacion']; ?>" size="10" maxlength="10" readonly />
        <?php echo $row_rsDispositivo['ub_nombre']; ?></td>
      </tr>
      <tr>
        <td align="right"><label for="fComentario">Comentario</label></td>
        <td><input name="fComentario" type="text" id="fComentario" value="<?php echo $row_rsDispositivo['comentario']; ?>" size="100" maxlength="100" readonly /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="bEliminar" id="bEliminar" value="Eliminar" />
        <input type="reset" name="bRestablecer" id="bRestablecer" value="Restablecer" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
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
mysql_free_result($rsDispositivo);

mysql_free_result($rsResguardantes);

mysql_free_result($rsTiposDeDispositivo);

mysql_free_result($rsMarcas);

mysql_free_result($rsTiposDeMonitor);

mysql_free_result($rsUbicaciones);
?>
