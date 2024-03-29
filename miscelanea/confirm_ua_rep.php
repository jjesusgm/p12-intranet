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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE confirm_ua_rep SET cnfrm1=%s, cnfrm2=%s, cnfrm3=%s, cnfrm4=%s, cnfrm5=%s, cnfrm6=%s, cnfrm7=%s, cnfrm8=%s, cnfrm9=%s WHERE codigo=%s",
                       GetSQLValueString(isset($_POST['fCnfrm1']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fCnfrm2']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fCnfrm3']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fCnfrm4']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fCnfrm5']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fCnfrm6']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fCnfrm7']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fCnfrm8']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fCnfrm9']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['fCodigo'], "text"));

  mysql_select_db($database_conMiscelanea, $conMiscelanea);
  $Result1 = mysql_query($updateSQL, $conMiscelanea) or die(mysql_error());

  $updateGoTo = "mens_cnfrm_act.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsAlumno = "-1";
if (isset($_GET['codigo'])) {
  $colname_rsAlumno = $_GET['codigo'];
}
mysql_select_db($database_conMiscelanea, $conMiscelanea);
$query_rsAlumno = sprintf("SELECT * FROM confirm_ua_rep WHERE codigo = %s", GetSQLValueString($colname_rsAlumno, "text"));
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Miscelanea &gt; UA por repetir &gt; Confirmar</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <table width="90%" align="center">
    <tr>
      <td><h1 class="H_Estilo1">Confirma las Unidades de Aprendizaje que vas a repetir</h1></td>
    </tr>
    <tr>
      <td align="center"><form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="500" class="TablaEntregaNIP">
          <tr>
            <td align="right" scope="col"><input name="fCodigo" type="hidden" id="fCodigo" value="<?php echo $row_rsAlumno['codigo']; ?>">
              Código:</td>
            <td scope="col"><?php echo $row_rsAlumno['codigo']; ?></td>
          </tr>
          <tr>
            <td align="right">Nombre:</td>
            <td><?php echo $row_rsAlumno['nombre']; ?></td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%">
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm1'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm1" id="fCnfrm1"></td>
                <td><?php echo $row_rsAlumno['crn1']; ?> - <?php echo $row_rsAlumno['ua1']; ?></td>
              </tr>
<?php if (strlen($row_rsAlumno['crn2']) > 0) { ?>
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm2'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm2" id="fCnfrm2"></td>
                <td><?php echo $row_rsAlumno['crn2']; ?> - <?php echo $row_rsAlumno['ua2']; ?></td>
              </tr>
<?php } if (strlen($row_rsAlumno['crn3']) > 0) { ?>
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm3'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm3" id="fCnfrm3"></td>
                <td><?php echo $row_rsAlumno['crn3']; ?> - <?php echo $row_rsAlumno['ua3']; ?></td>
              </tr>
<?php } if (strlen($row_rsAlumno['crn4']) > 0) { ?>
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm4'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm4" id="fCnfrm4"></td>
                <td><?php echo $row_rsAlumno['crn4']; ?> - <?php echo $row_rsAlumno['ua4']; ?></td>
              </tr>
<?php } if (strlen($row_rsAlumno['crn5']) > 0) { ?>
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm5'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm5" id="fCnfrm5"></td>
                <td><?php echo $row_rsAlumno['crn5']; ?> - <?php echo $row_rsAlumno['ua5']; ?></td>
              </tr>
<?php } if (strlen($row_rsAlumno['crn6']) > 0) { ?>
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm6'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm6" id="fCnfrm6"></td>
                <td><?php echo $row_rsAlumno['crn6']; ?> - <?php echo $row_rsAlumno['ua6']; ?></td>
              </tr>
<?php } if (strlen($row_rsAlumno['crn7']) > 0) { ?>
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm7'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm7" id="fCnfrm7"></td>
                <td><?php echo $row_rsAlumno['crn7']; ?> - <?php echo $row_rsAlumno['ua7']; ?></td>
              </tr>
<?php } if (strlen($row_rsAlumno['crn8']) > 0) { ?>
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm8'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm8" id="fCnfrm8"></td>
                <td><?php echo $row_rsAlumno['crn8']; ?> - <?php echo $row_rsAlumno['ua8']; ?></td>
              </tr>
<?php } if (strlen($row_rsAlumno['crn9']) > 0) { ?>
              <tr>
                <td align="center"><input <?php if (!(strcmp($row_rsAlumno['cnfrm9'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fCnfrm9" id="fCnfrm9"></td>
                <td><?php echo $row_rsAlumno['crn9']; ?> - <?php echo $row_rsAlumno['ua9']; ?></td>
              </tr>
<?php }?>
            </table></td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input type="submit" name="fActualizar" id="fActualizar" value="Actualizar"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
      </form></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
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
