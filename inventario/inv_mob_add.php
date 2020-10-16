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
  $insertSQL = sprintf("INSERT INTO inv_mobiliario (id_marca, id_resguardante, id_tipo, id_material, id_color, numero_serie, numero_inv_udg, descripcion, id_ubicacion, comentario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fIdMarca'], "int"),
                       GetSQLValueString($_POST['fIdResguardante'], "text"),
                       GetSQLValueString($_POST['fIdTipo'], "int"),
                       GetSQLValueString($_POST['fIdMaterial'], "int"),
                       GetSQLValueString($_POST['fIdColor'], "int"),
                       GetSQLValueString($_POST['fNumeroSerie'], "text"),
                       GetSQLValueString($_POST['fNumeroInvUdg'], "text"),
                       GetSQLValueString($_POST['fDescripcion'], "text"),
                       GetSQLValueString($_POST['fIdUbicacion'], "text"),
                       GetSQLValueString($_POST['fComentario'], "text"));

  mysql_select_db($database_conP12, $conP12);
  $Result1 = mysql_query($insertSQL, $conP12) or die(mysql_error());

  $insertGoTo = "inv_mobiliario.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conP12, $conP12);
$query_rsMarcas = "SELECT * FROM inv_mobiliario_marcas ORDER BY marca ASC";
$rsMarcas = mysql_query($query_rsMarcas, $conP12) or die(mysql_error());
$row_rsMarcas = mysql_fetch_assoc($rsMarcas);
$totalRows_rsMarcas = mysql_num_rows($rsMarcas);

mysql_select_db($database_conP12, $conP12);
$query_rsResguardantes = "SELECT * FROM usuarios WHERE nivel_de_acceso <> 'Alumno' ORDER BY nombre_completo ASC";
$rsResguardantes = mysql_query($query_rsResguardantes, $conP12) or die(mysql_error());
$row_rsResguardantes = mysql_fetch_assoc($rsResguardantes);
$totalRows_rsResguardantes = mysql_num_rows($rsResguardantes);

mysql_select_db($database_conP12, $conP12);
$query_rsTipos = "SELECT * FROM inv_mobiliario_tipos ORDER BY tipo_mobiliario ASC";
$rsTipos = mysql_query($query_rsTipos, $conP12) or die(mysql_error());
$row_rsTipos = mysql_fetch_assoc($rsTipos);
$totalRows_rsTipos = mysql_num_rows($rsTipos);

mysql_select_db($database_conP12, $conP12);
$query_rsMateriales = "SELECT * FROM inv_mobiliario_material ORDER BY material ASC";
$rsMateriales = mysql_query($query_rsMateriales, $conP12) or die(mysql_error());
$row_rsMateriales = mysql_fetch_assoc($rsMateriales);
$totalRows_rsMateriales = mysql_num_rows($rsMateriales);

mysql_select_db($database_conP12, $conP12);
$query_rsColores = "SELECT * FROM inv_mobiliario_colores ORDER BY color ASC";
$rsColores = mysql_query($query_rsColores, $conP12) or die(mysql_error());
$row_rsColores = mysql_fetch_assoc($rsColores);
$totalRows_rsColores = mysql_num_rows($rsColores);

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
<div id="div_hdr_path">&nbsp;Inicio &gt; Inventario &gt; Mobiliario &gt; Agregar</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <h2 class="center">Agregando Mobiliario</h2>
  <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
    <table width="600" align="center" class="tabla_usuario">
      <tr>
        <th colspan="2" align="left" scope="col">Registro del moniliario a agregar</th>
      </tr>
      <tr>
        <td align="right"><label for="fIdMarca">Marca</label></td>
        <td><select name="fIdMarca" id="fIdMarca">
          <?php
do {  
?>
          <option value="<?php echo $row_rsMarcas['id_marca']?>"><?php echo $row_rsMarcas['marca']?></option>
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
        <td align="right"><label for="fIdResguardante">Resguardante</label></td>
        <td><select name="fIdResguardante" id="fIdResguardante">
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
        <td align="right"><label for="fIdTipo">Tipo</label></td>
        <td><select name="fIdTipo" id="fIdTipo">
          <?php
do {  
?>
          <option value="<?php echo $row_rsTipos['id_tipo_mobiliario']?>"><?php echo $row_rsTipos['tipo_mobiliario']?></option>
          <?php
} while ($row_rsTipos = mysql_fetch_assoc($rsTipos));
  $rows = mysql_num_rows($rsTipos);
  if($rows > 0) {
      mysql_data_seek($rsTipos, 0);
	  $row_rsTipos = mysql_fetch_assoc($rsTipos);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fIdMaterial">Material</label></td>
        <td><select name="fIdMaterial" id="fIdMaterial">
          <?php
do {  
?>
          <option value="<?php echo $row_rsMateriales['id_material']?>"><?php echo $row_rsMateriales['material']?></option>
          <?php
} while ($row_rsMateriales = mysql_fetch_assoc($rsMateriales));
  $rows = mysql_num_rows($rsMateriales);
  if($rows > 0) {
      mysql_data_seek($rsMateriales, 0);
	  $row_rsMateriales = mysql_fetch_assoc($rsMateriales);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fIdColor">Color</label></td>
        <td><select name="fIdColor" id="fIdColor">
          <?php
do {  
?>
          <option value="<?php echo $row_rsColores['id_color']?>"><?php echo $row_rsColores['color']?></option>
          <?php
} while ($row_rsColores = mysql_fetch_assoc($rsColores));
  $rows = mysql_num_rows($rsColores);
  if($rows > 0) {
      mysql_data_seek($rsColores, 0);
	  $row_rsColores = mysql_fetch_assoc($rsColores);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td align="right"><label for="fNumeroSerie">No. de serie</label></td>
        <td><input name="fNumeroSerie" type="text" id="fNumeroSerie" size="25" maxlength="20" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fNumeroInvUdg">No. inventario UdeG</label></td>
        <td><input name="fNumeroInvUdg" type="text" id="fNumeroInvUdg" size="25" maxlength="20" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fDescripcion">Descripción</label></td>
        <td><input name="fDescripcion" type="text" id="fDescripcion" size="55" maxlength="100" /></td>
      </tr>
      <tr>
        <td align="right"><label for="fIdUbicacion">Ubicación</label></td>
        <td><select name="fIdUbicacion" id="fIdUbicacion">
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
        <td><input name="fComentario" type="text" id="fComentario" size="55" maxlength="100" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="bAgregar" id="bAgregar" value="Agregar" />
        <a href="inv_mobiliario.php" class="button-link">Cancelar</a></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1" />
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
mysql_free_result($rsMarcas);

mysql_free_result($rsResguardantes);

mysql_free_result($rsTipos);

mysql_free_result($rsMateriales);

mysql_free_result($rsColores);

mysql_free_result($rsUbicaciones);
?>
