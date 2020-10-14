<?php require_once('../Connections/conBecas.php'); ?>
<?php mysql_set_charset('utf8'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

if(isset($_POST['bFiltro'])){
	if($_POST['bFiltro'] == "Eliminar filtro"){
		$_SESSION['FILTRO_Codigo'] = "%";
		$_SESSION['FILTRO_NomBec'] = "%";
		$_SESSION['FILTRO_Ap1'] = "%";
		$_SESSION['FILTRO_Ap2'] = "%";
		header("Location: ". $_SERVER['PHP_SELF']);
	}else if($_POST['bFiltro'] == "Agregar filtro"){
		if (isset($_POST['fCodigo']) || isset($_POST['fNomBec']) || isset($_POST['fAp1']) || isset($_POST['fAp2'])){
			// Aplicar un filtro basado en el formulario
			$_SESSION['FILTRO_Codigo'] = $_POST['fCodigo'];
			$_SESSION['FILTRO_NomBec'] = $_POST['fNomBec'];
			$_SESSION['FILTRO_Ap1'] = $_POST['fAp1'];
			$_SESSION['FILTRO_Ap2'] = $_POST['fAp2'];
		//}else if(isset($_SESSION['FILTRO_Codigo']) || isset($_SESSION['FILTRO_NomBec']) || isset($_SESSION['FILTRO_Ap1']) || isset($_SESSION['FILTRO_Ap2'])){
			// No hacer nada, ya hay un filtro
			//header("Location: /intranet_p12/becas/lista_padron.php"); //. $_SERVER['PHP_SELF']);
			//exit;
		}else{
			// No existe ningun filtro ni se esta aplicando uno, inicializar las variables de sesion
			$_SESSION['FILTRO_Codigo'] = "%";
			$_SESSION['FILTRO_NomBec'] = "%";
			$_SESSION['FILTRO_Ap1'] = "%";
			$_SESSION['FILTRO_Ap2'] = "%";
			//header("Location: /intranet_p12/becas/lista_padron.php");//. $_SERVER['PHP_SELF']);
			//exit;
		}
	}
	// Revisa si se desea mostrar o no la sentencia SQL
	if (isset($_POST['fShowSQL']))
        $_SESSION['FILTRO_ShowSQL'] = "checked";
    else
        $_SESSION['FILTRO_ShowSQL'] = "";;
}
?>
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
$maxRows_rsPadron = 50;
$pageNum_rsPadron = 0;
if (isset($_GET['pageNum_rsPadron'])) {
  $pageNum_rsPadron = $_GET['pageNum_rsPadron'];
}
$startRow_rsPadron = $pageNum_rsPadron * $maxRows_rsPadron;

$varCodigo_rsPadron = "%";
if (isset($_SESSION['FILTRO_Codigo'])) {
  $varCodigo_rsPadron = $_SESSION['FILTRO_Codigo'];
}
$varNomBec_rsPadron = "%";
if (isset($_SESSION['FILTRO_NomBec'])) {
  $varNomBec_rsPadron = $_SESSION['FILTRO_NomBec'];
}
$varAp1_rsPadron = "%";
if (isset($_SESSION['FILTRO_Ap1'])) {
  $varAp1_rsPadron = $_SESSION['FILTRO_Ap1'];
}
$varAp2_rsPadron = "%";
if (isset($_SESSION['FILTRO_Ap2'])) {
  $varAp2_rsPadron = $_SESSION['FILTRO_Ap2'];
}
mysql_select_db($database_conBecas, $conBecas);
$query_rsPadron = sprintf("SELECT * FROM padron_bbbj WHERE codigo LIKE %s AND nom_bec LIKE %s AND ap1 LIKE %s AND ap2 LIKE %s ORDER BY ap1, ap2, nom_bec ASC", GetSQLValueString($varCodigo_rsPadron, "text"),GetSQLValueString($varNomBec_rsPadron, "text"),GetSQLValueString($varAp1_rsPadron, "text"),GetSQLValueString($varAp2_rsPadron, "text"));
$query_limit_rsPadron = sprintf("%s LIMIT %d, %d", $query_rsPadron, $startRow_rsPadron, $maxRows_rsPadron);
$rsPadron = mysql_query($query_limit_rsPadron, $conBecas) or die(mysql_error());
$row_rsPadron = mysql_fetch_assoc($rsPadron);

if (isset($_GET['totalRows_rsPadron'])) {
  $totalRows_rsPadron = $_GET['totalRows_rsPadron'];
} else {
  $all_rsPadron = mysql_query($query_rsPadron);
  $totalRows_rsPadron = mysql_num_rows($all_rsPadron);
}
$totalPages_rsPadron = ceil($totalRows_rsPadron/$maxRows_rsPadron)-1;

$queryString_rsPadron = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsPadron") == false && 
        stristr($param, "totalRows_rsPadron") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsPadron = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsPadron = sprintf("&totalRows_rsPadron=%d%s", $totalRows_rsPadron, $queryString_rsPadron);
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
	//Valida el campo fCodigo
	if(!f.fCodigo.value || f.fCodigo.value == null || f.fCodigo.value.length == 0){
		f.fCodigo.value = "%";
	}
	
	//Valida el campo fNomBec
	if(!f.fNomBec.value || f.fNomBec.value == null || f.fNomBec.value.length == 0){
		f.fNomBec.value = "%";
	}else{
		f.fNomBec.value = "%"+f.fNomBec.value+"%";
	}
	
	//Valida el campo fAp1
	if(!f.fAp1.value || f.fAp1.value == null || f.fAp1.value.length == 0){
		f.fAp1.value = "%";
	}else{
		f.fAp1.value = "%"+f.fAp1.value+"%";
	}
	
	//Valida el campo fAp2
	if(!f.fAp2.value || f.fAp2.value == null || f.fAp2.value.length == 0){
		f.fAp2.value = "%";
	}else{
		f.fAp2.value = "%"+f.fAp2.value+"%";
	}
	
	//Regresa el valor VERDADERO
	return true;
}

function setFocusTo(frmElement){
    document.getElementById(frmElement).focus();
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
<div id="div_hdr_path">&nbsp;Inicio &gt; Becas &gt; Administracion del padron de becas</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <table width="90%" align="center">
    <tr>
      <td><h1 class="H_Estilo1">Administración del padrón de becas</h1></td>
    </tr>
    <tr>
      <td align="center"><h3 class="margin_none">Mostrando alumnos <?php echo ($startRow_rsPadron + 1) ?> al <?php echo min($startRow_rsPadron + $maxRows_rsPadron, $totalRows_rsPadron) ?> de <?php echo $totalRows_rsPadron ?> </h3></td>
    </tr>
    <tr>
      <td align="center"><table align="center">
        <tr>
          <td><form action="" method="post" name="form1" id="form1" onsubmit="return valida(this)">
            <fieldset>
              <legend>Filtrar resultados</legend>
              <table align="center">
                <tr>
                  <td align="right"><label for="fCodigo">Código:</label></td>
                  <td colspan="3"><input name="fCodigo" type="text" id="fCodigo" placeholder="Código" pattern="[0-9]{9,}" value="<?php echo str_replace('%','',$_SESSION['FILTRO_Codigo']); ?>" size="15" maxlength="9" /></td>
                </tr>
                <tr>
                  <td align="right"><label for="fNomBec">Nombre:</label></td>
                  <td><input name="fNomBec" type="text" id="fNomBec" size="15" maxlength="100" placeholder="Nombre" value="<?php echo str_replace('%','',$_SESSION['FILTRO_NomBec']); ?>" /></td>
                  <td><input name="fAp1" type="text" id="fAp1" size="15" placeholder="1er apellido" value="<?php echo str_replace('%','',$_SESSION['FILTRO_Ap1']); ?>"></td>
                  <td><input name="fAp2" type="text" id="fAp2" size="15" placeholder="2do apellido" value="<?php echo str_replace('%','',$_SESSION['FILTRO_Ap2']); ?>"></td>
                </tr>
                <tr>
                  <td align="right"><input name="fShowSQL" type="checkbox" id="fShowSQL" <?php echo $_SESSION['FILTRO_ShowSQL'] ?>></td>
                  <td colspan="3">Mostrar sentencia SQL</td>
                  </tr>
                <tr>
                  <td colspan="4" align="center"><input type="submit" name="bFiltro" id="bFiltro" value="Agregar filtro" />
                    <input type="submit" name="bFiltro" id="bFiltro" value="Eliminar filtro"></td>
                </tr>
              </table>
            </fieldset>
          </form></td>
        </tr>
      </table>
<?php
	if(isset($_SESSION['FILTRO_ShowSQL']) && $_SESSION['FILTRO_ShowSQL'] == 'checked'){
		echo "<p style='font-size:15px;color:darkred'><strong>Sentencia SQL:</strong> ";
		echo $query_rsPadron; 
		echo "</p>";
	}
?>
     </td>
    </tr>
    <tr>
      <td align="center"><h3 class="margin_top">Agregar nuevo alumno</h3></td>
    </tr>
    <tr>
      <td align="center"><table>
        <tr>
          <th>Navegación:</th>
          <td><a href="<?php printf("%s?pageNum_rsPadron=%d%s", $currentPage, 0, $queryString_rsPadron); ?>" class="estilo3">Primera</a></td>
          <td>|</td>
          <td><a href="<?php printf("%s?pageNum_rsPadron=%d%s", $currentPage, max(0, $pageNum_rsPadron - 1), $queryString_rsPadron); ?>" class="estilo3">Anterior</a></td>
          <td>|</td>
          <td><a href="<?php printf("%s?pageNum_rsPadron=%d%s", $currentPage, min($totalPages_rsPadron, $pageNum_rsPadron + 1), $queryString_rsPadron); ?>" class="estilo3">Siguiente</a></td>
          <td>|</td>
          <td><a href="<?php printf("%s?pageNum_rsPadron=%d%s", $currentPage, $totalPages_rsPadron, $queryString_rsPadron); ?>" class="estilo3">Ultima</a></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td align="center"><table width="100%" class="TablaListaInventario">
        <tr>
          <th scope="col">CODIGO</th>
          <th scope="col">ACTUALIZACION</th>
          <th scope="col">NOMBRE</th>
          <th scope="col">CURP</th>
          <th scope="col">CORREO-E</th>
          <th scope="col">TELEFONO</th>
          <th scope="col">ACCION</th>
        </tr>
        <?php do { ?>
          <tr>
            <td align="center"><?php echo $row_rsPadron['codigo']; ?></td>
            <td align="center"><?php echo $row_rsPadron['ult_act']; ?></td>
            <td align="center"><?php echo $row_rsPadron['nom_bec'] . " " . $row_rsPadron['ap1'] . " " . $row_rsPadron['ap2']; ?></td>
            <td align="center"><?php echo $row_rsPadron['curp']; ?></td>
            <td align="center"><?php echo $row_rsPadron['correo']; ?></td>
            <td align="center"><?php echo $row_rsPadron['tel_con']; ?></td>
            <td align="center"><a href="becario_editar.php">Editar</a> | <a href="becario_borrar.php">Borrar</a></td>
          </tr>
          <?php } while ($row_rsPadron = mysql_fetch_assoc($rsPadron)); ?>
      </table></td>
    </tr>
    <tr>
      <td align="center"><table>
        <tr>
          <th>Navegación:</th>
          <td>Primera</td>
          <td>|</td>
          <td>Anterior</td>
          <td>|</td>
          <td>Siguiente</td>
          <td>|</td>
          <td>Ultima</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td align="center"><h3 class="margin_none">Agregar nuevo alumno</h3></td>
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
mysql_free_result($rsPadron);
?>
