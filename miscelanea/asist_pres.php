<?php require_once('../Connections/conMiscelanea.php'); ?>
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

$colname_rsAlumno = "-1";
if (isset($_POST['fCodigo'])) {
  $colname_rsAlumno = $_POST['fCodigo'];
}
mysql_select_db($database_conMiscelanea, $conMiscelanea);
$query_rsAlumno = sprintf("SELECT * FROM clas_presencial WHERE codigo = %s", GetSQLValueString($colname_rsAlumno, "text"));
$rsAlumno = mysql_query($query_rsAlumno, $conMiscelanea) or die(mysql_error());
$row_rsAlumno = mysql_fetch_assoc($rsAlumno);
$totalRows_rsAlumno = mysql_num_rows($rsAlumno);
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
<link href="../css/form_validation.css" rel="stylesheet" type="text/css" />
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Miscelanea &gt; Clases presencial</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <table width="90%" align="center">
    <tr>
      <td><h1 class="H_Estilo1">Consulta aquí las fechas y demás detalles de tu asistencia PRESENCIAL</h1></td>
    </tr>
    <?php if ($totalRows_rsAlumno == 0) { // Show if recordset empty ?>
      <tr>
        <td align="center"><form name="form1" method="post" action="">
          <div class="DivShadowMsgLogin">
            <table width="350" class="tabla_info_msg">
              <tr>
                <th colspan="2" align="left" scope="col">Escribe tu código</th>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="100" align="right">Código*</td>
                <td><input name="fCodigo" type="text" id="fCodigo" size="15" maxlength="9" required pattern="[0-9]{9,}">
                  <input type="submit" name="bEnviar" id="bEnviar" value="Enviar"></td>
              </tr>
              <tr>
                <td align="right">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr bgcolor="#FFFF00">
                <td colspan="2" align="center">Escribe tu código de estudiante, recuerda que debe ser de 9 dígitos.</td>
              </tr>
            </table>
          </div>
        </form>
        <?php if (isset($_POST['fCodigo'])) { // Show if fCodigo is set ?>
          <br>¡DATOS NO ENCONTRADOS, INGRESA AL LINK Y COMPLETA EL FORMULARIO!<br>
          <?php } // Show if fCodigo is set ?>
        </td>
      </tr>
      <?php } // Show if recordset empty ?>
    <?php if ($totalRows_rsAlumno > 0) { // Show if recordset not empty ?>
  <tr>
    <td align="center">
    <div class="DivShadowMsg">
    <table width="800" class="TablaEntregaNIP">
      <tr>
        <th colspan="2" align="left" scope="col">Datos encontrados</th>
        </tr>
      <tr>
        <td width="12%" align="right">Código:</td>
        <td><?php echo $row_rsAlumno['codigo']; ?></td>
        </tr>
      <tr>
        <td align="right">Nombre:</td>
        <td><?php echo $row_rsAlumno['nombre']; ?></td>
      </tr>
      <tr>
        <td align="right">Plantel:</td>
        <td><?php echo $row_rsAlumno['plantel']; if($row_rsAlumno['plantel'] == "P12"){echo " - Prepa 12";}else{echo " - Módulo Tlaquepaque";} ?></td>
      </tr>
      <tr>
        <td align="right">Grupo:</td>
        <td><?php echo $row_rsAlumno['grupo']; ?></td>
      </tr>
      <tr>
        <td align="right">Sección:</td>
        <td><?php echo $row_rsAlumno['seccion']; ?></td>
      </tr>
      <tr>
        <td align="right">Aula:</td>
        <td><?php echo $row_rsAlumno['aula']; ?></td>
      </tr>
      <tr>
        <td align="right">Periodos para asistir de manera presencial a clase:</td>
        <td><?php echo nl2br($row_rsAlumno['detalles']); ?></td>
        </tr>
      <tr>
        <td colspan="2" align="left" bgcolor="#FFFF00">Nota: .</td>
      </tr>
    </table>
    </div>
    </td>
  </tr>
  <?php } // Show if recordset not empty ?>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><a href="index.php" class="estilo3">Regresar</a></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  </table>
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
mysql_free_result($rsAlumno);
?>
