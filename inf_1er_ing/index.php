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

$colname_rsAlumnos = "-1";
if (isset($_POST['fRegistro'])) {
  $colname_rsAlumnos = $_POST['fRegistro'];
}
mysql_select_db($database_conP12, $conP12);
$query_rsAlumnos = sprintf("SELECT * FROM inf_1er_ingreso WHERE registro = %s", GetSQLValueString($colname_rsAlumnos, "text"));
$rsAlumnos = mysql_query($query_rsAlumnos, $conP12) or die(mysql_error());
$row_rsAlumnos = mysql_fetch_assoc($rsAlumnos);
$totalRows_rsAlumnos = mysql_num_rows($rsAlumnos);
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
<div id="div_hdr_path">&nbsp;Inicio &gt; 1er ingreso &gt; Consulta tus datos</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <table width="90%" align="center">
    <tr>
      <td><h1 class="H_Estilo1">Consulta de datos de alumno de 1er ingreso</h1></td>
    </tr>
    <tr>
      <td align="center"><form name="form1" method="post" action="">
        <div class="DivShadowMsgLogin">
          <table width="100%" class="tabla_info_msg">
            <tr>
              <th colspan="2" align="left" scope="col">Busqueda...</th>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="100" align="right"><label for="fRegistro">Registro*</label></td>
              <td><input name="fRegistro" type="text" id="fRegistro" size="10" maxlength="7" title="Escribe tu número de registro" placeholder="7 dígitos" pattern="[0-9]{7,}" required>
                <input type="submit" name="bEnviar" id="bEnviar" value="Enviar"></td>
            </tr>
            <tr>
              <td align="right">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFF00">
              <td colspan="2" align="center">Escribe tu número de registro, recuerda que debe ser de 7 dígitos.</td>
            </tr>
          </table>
        </div>
      </form></td>
    </tr>
<?php if ($totalRows_rsAlumnos > 0) { // Show if recordset not empty ?>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
    <td align="center"><div class="DivShadowMsgValidar"><table width="100%" class="TablaListaInventario">
      <tr>
        <th colspan="2" align="left" scope="col">Detalles del alumno</th>
        </tr>
      <tr>
        <td align="right"><strong>REGISTRO:</strong></td>
        <td align="left"><?php echo $row_rsAlumnos['registro']; ?></td>
        </tr>
      <tr>
        <td align="right"><strong>CODIGO:</strong></td>
        <td align="left"><?php echo $row_rsAlumnos['codigo']; ?></td>
      </tr>
      <tr>
        <td align="right"><strong>NOMBRE:</strong></td>
        <td align="left"><?php echo $row_rsAlumnos['nom_comp']; ?></td>
      </tr>
      <tr>
        <td align="right"><strong>GRUPO:</strong></td>
        <td align="left"><?php echo $row_rsAlumnos['grado']; ?>°&nbsp;<?php echo $row_rsAlumnos['grupo']; ?>&nbsp;T/<?php echo $row_rsAlumnos['turno']; ?></td>
      </tr>
      <tr>
        <td align="right"><strong>SECCION:</strong></td>
        <td align="left"><?php echo $row_rsAlumnos['seccion']; ?></td>
      </tr>
      <tr>
        <td align="right"><strong>FECHA CURSO INDUCCION:</strong></td>
        <td align="left"><?php echo $row_rsAlumnos['fec_cur_ind']; ?></td>
      </tr>
      <tr>
        <td align="right"><strong>HORA CURSO INDUCCION:</strong></td>
        <td align="left"><?php echo $row_rsAlumnos['hor_cur_ind']; ?></td>
      </tr>
      <tr>
        <td align="right"><strong>AULA:</strong></td>
        <td align="left"><?php echo $row_rsAlumnos['aula']; ?></td>
      </tr>
        </table></div></td>
  </tr>
  <?php } // Show if recordset not empty ?>
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
mysql_free_result($rsAlumnos);
?>