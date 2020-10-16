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

$varNombreMarca_rsInvCompMarcas = "%";
if (isset($_POST['fNombreMarca'])) {
  $varNombreMarca_rsInvCompMarcas = $_POST['fNombreMarca'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsInvCompMarcas = sprintf("SELECT * FROM inv_computo_marcas WHERE nombre_marca LIKE %s ORDER BY nombre_marca ASC", GetSQLValueString($varNombreMarca_rsInvCompMarcas, "text"));
$rsInvCompMarcas = mysql_query($query_rsInvCompMarcas, $conP12) or die(mysql_error());
$row_rsInvCompMarcas = mysql_fetch_assoc($rsInvCompMarcas);
$totalRows_rsInvCompMarcas = mysql_num_rows($rsInvCompMarcas);$varNombreMarca_rsInvCompMarcas = "%";
if (isset($_POST['fNombreMarca'])) {
  $varNombreMarca_rsInvCompMarcas = $_POST['fNombreMarca'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsInvCompMarcas = sprintf("SELECT * FROM inv_computo_marcas WHERE nombre_marca LIKE %s ORDER BY nombre_marca ASC", GetSQLValueString($varNombreMarca_rsInvCompMarcas, "text"));
$rsInvCompMarcas = mysql_query($query_rsInvCompMarcas, $conP12) or die(mysql_error());
$row_rsInvCompMarcas = mysql_fetch_assoc($rsInvCompMarcas);
$totalRows_rsInvCompMarcas = mysql_num_rows($rsInvCompMarcas);
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
	if(!f.fNombreMarca.value){
		f.fNombreMarca.value = "%";
	}else{
		f.fNombreMarca.value = "%"+f.fNombreMarca.value+"%";
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Mantenimiento a BD &gt; Inventario &gt; Cómputo &gt; Marcas</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <?php if ($totalRows_rsInvCompMarcas > 0) { // Show if recordset not empty ?>
    <h2 class="center"><?php echo $totalRows_rsInvCompMarcas ?> marcas  coinciden con el criterio de búsqueda</h2>
    <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsInvCompMarcas == 0) { // Show if recordset empty ?>
    <h2 class="center">Nada que mostrar con este criterio de búsqueda</h2>
    <?php } // Show if recordset empty ?>
<div class="div_filtro">
    <form id="form1" name="form1" method="post" action="" onsubmit="return valida(this)">
      <fieldset>
        <legend>Filtrar resultados</legend>
      <table align="center">
        <tr>
          <td align="right"><label for="fNombreMarca">Marca</label></td>
          <td><input name="fNombreMarca" type="text" id="fNombreMarca" size="45" maxlength="40" /></td>
        </tr>
        <tr>
          <td colspan="2" align="center"><input type="submit" name="bAgregarFiltro" id="bAgregarFiltro" value="Agregar filtro" /></td>
        </tr>
      </table>
      </fieldset>
    </form>
  </div><h3 class="center"><a href="inv_comp_marcas_add.php" class="estilo3">Agregar nueva marca</a></h3>
  <?php if ($totalRows_rsInvCompMarcas > 0) { // Show if recordset not empty ?>
    <table width="600" align="center" class="tabla_contactos">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">MARCA</th>
        <th scope="col">ACCION</th>
      </tr>
      <?php do { ?>
        <tr>
          <td align="center"><?php echo $row_rsInvCompMarcas['siglas']; ?></td>
          <td><?php echo $row_rsInvCompMarcas['nombre_marca']; ?></td>
          <td align="center"><a href="inv_comp_marcas_edit.php?siglas=<?php echo $row_rsInvCompMarcas['siglas']; ?>">Editar</a> | <a href="inv_comp_marcas_delete.php?siglas=<?php echo $row_rsInvCompMarcas['siglas']; ?>">Eliminar</a></td>
        </tr>
        <?php } while ($row_rsInvCompMarcas = mysql_fetch_assoc($rsInvCompMarcas)); ?>
    </table>
    <h3 class="center"><a href="inv_comp_marcas_add.php" class="estilo3">Agregar nueva marca</a></h3>
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
mysql_free_result($rsInvCompMarcas);
?>
