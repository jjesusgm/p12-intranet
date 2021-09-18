<?php require_once('../Connections/conBecas.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE padron_bbbj SET sede=%s, curp=%s, ap1=%s, ap2=%s, nombre=%s, fec_nac=%s, situacion=%s, causa=%s, semestre=%s, modalidad=%s, correo=%s, tel_cel=%s, cla_ent=%s, cla_mun=%s, cla_loc=%s, tipo_asent=%s, nom_asent=%s, cod_post=%s, tipo_vial=%s, nom_vial_carr_cam=%s, cod_carr=%s, adm_carr=%s, der_tran=%s, margen=%s, tramo=%s, kilometro=%s, num_ext=%s, num_int=%s, desc_ubic=%s, tipo_ref_1=%s, nom_ref_1=%s, tipo_ref_2=%s, nom_ref_2=%s, tipo_ref_3=%s, nom_ref_3=%s, genero=%s, grupo=%s, turno=%s, tel_eme=%s, msg_err_plat=%s, comentario=%s WHERE codigo=%s",
                       GetSQLValueString($_POST['fSede'], "text"),
                       GetSQLValueString($_POST['fCurp'], "text"),
                       GetSQLValueString($_POST['fAp1'], "text"),
                       GetSQLValueString($_POST['fAp2'], "text"),
                       GetSQLValueString($_POST['fNombre'], "text"),
                       GetSQLValueString($_POST['fFecNac'], "date"),
                       GetSQLValueString($_POST['fSituacion'], "text"),
                       GetSQLValueString($_POST['fCausa'], "text"),
                       GetSQLValueString($_POST['fSemestre'], "text"),
                       GetSQLValueString($_POST['fModalidad'], "text"),
                       GetSQLValueString($_POST['fCorreo'], "text"),
                       GetSQLValueString($_POST['fTelCel'], "text"),
                       GetSQLValueString($_POST['fClaEnt'], "text"),
                       GetSQLValueString($_POST['fClaMun'], "text"),
                       GetSQLValueString($_POST['fClaLoc'], "text"),
                       GetSQLValueString($_POST['fTipoAsent'], "int"),
                       GetSQLValueString($_POST['fNomAsent'], "text"),
                       GetSQLValueString($_POST['fCodPost'], "text"),
                       GetSQLValueString($_POST['fTipoVial'], "int"),
                       GetSQLValueString($_POST['fNomVialCarrCam'], "text"),
                       GetSQLValueString($_POST['fCodCarr'], "int"),
                       GetSQLValueString($_POST['fAdmCarr'], "int"),
                       GetSQLValueString($_POST['fDerTran'], "int"),
                       GetSQLValueString($_POST['fMargen'], "int"),
                       GetSQLValueString($_POST['fTramo'], "text"),
                       GetSQLValueString($_POST['fKilometro'], "int"),
                       GetSQLValueString($_POST['fNumExt'], "text"),
                       GetSQLValueString($_POST['fNumInt'], "text"),
                       GetSQLValueString($_POST['fDescUbic'], "text"),
                       GetSQLValueString($_POST['fTipoRef1'], "int"),
                       GetSQLValueString($_POST['fNomRef1'], "text"),
                       GetSQLValueString($_POST['fTipoRef2'], "int"),
                       GetSQLValueString($_POST['fNomRef2'], "text"),
                       GetSQLValueString($_POST['fTipoRef3'], "int"),
                       GetSQLValueString($_POST['fNomRef3'], "text"),
                       GetSQLValueString($_POST['fGenero'], "text"),
                       GetSQLValueString($_POST['fGrupo'], "text"),
                       GetSQLValueString($_POST['fTurno'], "text"),
                       GetSQLValueString($_POST['fTelEme'], "text"),
                       GetSQLValueString($_POST['fMsgErrPlat'], "text"),
                       GetSQLValueString($_POST['fComentario'], "text"),
                       GetSQLValueString($_POST['fCodigo'], "text"));

  mysql_select_db($database_conBecas, $conBecas);
  $Result1 = mysql_query($updateSQL, $conBecas) or die(mysql_error());

  $updateGoTo = "log_update.php";
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
mysql_select_db($database_conBecas, $conBecas);
$query_rsAlumno = sprintf("SELECT * FROM padron_bbbj WHERE codigo = %s", GetSQLValueString($colname_rsAlumno, "text"));
$rsAlumno = mysql_query($query_rsAlumno, $conBecas) or die(mysql_error());
$row_rsAlumno = mysql_fetch_assoc($rsAlumno);
$totalRows_rsAlumno = mysql_num_rows($rsAlumno);

mysql_select_db($database_conBecas, $conBecas);
$query_rsMsgErrPlat = "SELECT * FROM msg_err_plat ORDER BY `Error` ASC";
$rsMsgErrPlat = mysql_query($query_rsMsgErrPlat, $conBecas) or die(mysql_error());
$row_rsMsgErrPlat = mysql_fetch_assoc($rsMsgErrPlat);
$totalRows_rsMsgErrPlat = mysql_num_rows($rsMsgErrPlat);

mysql_select_db($database_conBecas, $conBecas);
$query_rsTipoAsentamiento = "SELECT * FROM tipo_asentam ORDER BY nom_asentam ASC";
$rsTipoAsentamiento = mysql_query($query_rsTipoAsentamiento, $conBecas) or die(mysql_error());
$row_rsTipoAsentamiento = mysql_fetch_assoc($rsTipoAsentamiento);
$totalRows_rsTipoAsentamiento = mysql_num_rows($rsTipoAsentamiento);

mysql_select_db($database_conBecas, $conBecas);
$query_rsTipoVialidad = "SELECT * FROM tipo_vialidad ORDER BY nom_vialidad ASC";
$rsTipoVialidad = mysql_query($query_rsTipoVialidad, $conBecas) or die(mysql_error());
$row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad);
$totalRows_rsTipoVialidad = mysql_num_rows($rsTipoVialidad);

mysql_select_db($database_conBecas, $conBecas);
$query_rsEstados = "SELECT * FROM estados WHERE id_estado = '14' ORDER BY nom_estado ASC";
$rsEstados = mysql_query($query_rsEstados, $conBecas) or die(mysql_error());
$row_rsEstados = mysql_fetch_assoc($rsEstados);
$totalRows_rsEstados = mysql_num_rows($rsEstados);

mysql_select_db($database_conBecas, $conBecas);
$query_rsMunicipios = "SELECT * FROM municipios WHERE id_estado = '14' ORDER BY nom_municipio ASC";
$rsMunicipios = mysql_query($query_rsMunicipios, $conBecas) or die(mysql_error());
$row_rsMunicipios = mysql_fetch_assoc($rsMunicipios);
$totalRows_rsMunicipios = mysql_num_rows($rsMunicipios);

mysql_select_db($database_conBecas, $conBecas);
$query_rsLocalidades = "SELECT * FROM localidades WHERE id_estado = '14' ORDER BY nom_localidad ASC";
$rsLocalidades = mysql_query($query_rsLocalidades, $conBecas) or die(mysql_error());
$row_rsLocalidades = mysql_fetch_assoc($rsLocalidades);
$totalRows_rsLocalidades = mysql_num_rows($rsLocalidades);
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

    <link href="../css/divs.css" rel="stylesheet" type="text/css" />
    <link href="../css/enlaces.css" rel="stylesheet" type="text/css" />
    <link href="../css/formularios.css" rel="stylesheet" type="text/css">
    <link href="../css/form_validation.css" rel="stylesheet" type="text/css" />
	<link href="../css/imagenes.css" rel="stylesheet" type="text/css" />
    <link href="../css/menu1.css" rel="stylesheet" type="text/css" />
    <link href="../css/tablas.css" rel="stylesheet" type="text/css" />
    <link href="../css/varios.css" rel="stylesheet" type="text/css" />

    <title>Soporte P12 | Actualizar datos de beca BBBJ</title>

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
    <script type="text/javascript" src="../js/menus.js"></script>
	<script language="javascript">
    function muestraLocalidades(strEstado,strMunicipio,strLocalidad) {
      if (strEstado == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET","includes/get_localidades.php?id_estado="+strEstado+"&id_municipio="+strMunicipio+"&id_localidad="+strLocalidad,true);
        xmlhttp.send();
      }
    }
    </script>

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
<div id="div_hdr_path">&nbsp;Inicio &gt; Becas &gt; Actualiza tus datos</div>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="Contenido" -->
<div id="div_contenido">
  <table width="90%" align="center">
    <tr>
      <td><h1 class="H_Estilo1">Actualiza tus datos</h1></td>
    </tr>
    <tr>
      <td align="center"><form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
       <div class="DivShadowMsg">
        <table width="800" class="tabla_info_msg">
          <tr>
            <th colspan="2" align="left" scope="col">Datos de tu registro</th>
            </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Código*:</td>
            <td><input name="fCodigo" type="text" id="fCodigo" value="<?php echo $row_rsAlumno['codigo']; ?>" readonly></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fSede">Sede*:</label></td>
            <td class="TablaEntregaNIP"><input name="fSede" type="text" id="fSede" value="<?php echo $row_rsAlumno['sede']; ?>" readonly></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">CURP*:</td>
            <td class="TablaEntregaNIP"><input name="fCurp" type="text" required id="fCurp" pattern=".{18,}" title="18 caracteres exactos (Obligatorio)" value="<?php echo $row_rsAlumno['curp']; ?>" maxlength="18" <?php if($row_rsAlumno['semestre'] != 1 && strlen($row_rsAlumno['curp']) == 18) echo "readonly"; ?>>
              18 caracteres exactos</td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Apellido 1*:</td>
            <td class="TablaEntregaNIP"><input name="fAp1" type="text" required id="fAp1" title="Primer apellido del estudiante, antes apellido paterno (Obligatorio)" value="<?php echo $row_rsAlumno['ap1']; ?>" maxlength="30" <?php if($row_rsAlumno['semestre'] != 1 && strlen($row_rsAlumno['ap1']) > 0) echo "readonly"; ?>></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Apellido 2:</td>
            <td class="TablaEntregaNIP"><input name="fAp2" type="text" id="fAp2" title="Segundo apellido del estudiante, antes apellido materno (Opcional)" value="<?php echo $row_rsAlumno['ap2']; ?>" maxlength="30" <?php if($row_rsAlumno['semestre'] != 1 && strlen($row_rsAlumno['ap2']) > 0) echo "readonly"; ?>></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Nombre*:</td>
            <td class="TablaEntregaNIP"><input name="fNombre" type="text" required id="fNombre" title="Nombre(s) del estudiante (Obligatorio)" value="<?php echo $row_rsAlumno['nombre']; ?>" maxlength="30" <?php if($row_rsAlumno['semestre'] != 1 && strlen($row_rsAlumno['nombre']) > 0) echo "readonly"; ?>></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Fecha nacimiento*:</td>
            <td class="TablaEntregaNIP"><input name="fFecNac" type="text" required id="fFecNac" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" title="Fecha de nacimiento del estudiante con el formato aaaa-mm-dd (Obligatorio)" value="<?php echo $row_rsAlumno['fec_nac']; ?>" maxlength="10" <?php if($row_rsAlumno['semestre'] != 1 && $row_rsAlumno['fec_nac'] != '0000-00-00') echo "readonly"; ?>>
              aaaa-mm-dd</td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fSituacion">Situación*:</label></td>
            <td class="TablaEntregaNIP"><input name="fSituacion" type="text" id="fSituacion" value="<?php echo $row_rsAlumno['situacion']; ?>" maxlength="15" title="Situación actual, por ejemplo REINSCRITO, CONCLUYE, etcétera" readonly></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fCausa">Causa:</label></td>
            <td class="TablaEntregaNIP"><input name="fCausa" type="text" id="fCausa" value="<?php echo $row_rsAlumno['causa']; ?>" readonly></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fSemestre">Semestre*:</label></td>
            <td class="TablaEntregaNIP"><input name="fSemestre" type="text" required id="fSemestre" pattern="[1-6]{1,}" title="Semestre al que asiste de forma regular el estudiante (Obligatorio)" value="<?php echo $row_rsAlumno['semestre']; ?>" maxlength="1"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fModalidad">Modalidad*:</label></td>
            <td class="TablaEntregaNIP"><input name="fModalidad" type="text" id="fModalidad" title="Modalidad de estudio (Obligatorio)" value="<?php echo $row_rsAlumno['modalidad']; ?>" readonly></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Correo*:</td>
            <td class="TablaEntregaNIP"><input name="fCorreo" type="email" id="fCorreo" value="<?php echo $row_rsAlumno['correo']; ?>" size="40" maxlength="100" required pattern=".{1,}" title="Correo electrónico (Obligatorio)" readonly></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Tel. celular*:</td>
            <td class="TablaEntregaNIP"><input name="fTelCel" type="text" required id="fTelCel" pattern="[0-9]{10,}" title="Telefono celular, 10 dígitos exactos (Obligatorio)" value="<?php echo $row_rsAlumno['tel_cel']; ?>" maxlength="10">
              10 dígitos exactos</td>
          </tr>
          <tr>
            <th colspan="2" align="center">DOMICILIO DEL ESTUDIANTE</th>
            </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fClaEnt">Entidad federativa*:</label></td>
            <td class="TablaEntregaNIP"><select name="fClaEnt" id="fClaEnt" title="Estado ó Entidad Federativa del domicilio del estudiante" read>
              <?php
do {  
?>
              <option value="<?php echo $row_rsEstados['id_estado']?>"<?php if (!(strcmp($row_rsEstados['id_estado'], $row_rsAlumno['cla_ent']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsEstados['nom_estado']?></option>
              <?php
} while ($row_rsEstados = mysql_fetch_assoc($rsEstados));
  $rows = mysql_num_rows($rsEstados);
  if($rows > 0) {
      mysql_data_seek($rsEstados, 0);
	  $row_rsEstados = mysql_fetch_assoc($rsEstados);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fClaMun">Municipio/Alcaldía*:</label></td>
            <td class="TablaEntregaNIP"><select name="fClaMun" id="fClaMun" title="Municipio ó Alcaldía del domicilio del estudiante" onchange="muestraLocalidades('14',this.value)">
              <?php
do {  
?>
              <option value="<?php echo $row_rsMunicipios['id_municipio']?>"<?php if (!(strcmp($row_rsMunicipios['id_municipio'], $row_rsAlumno['cla_mun']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsMunicipios['nom_municipio']?></option>
              <?php
} while ($row_rsMunicipios = mysql_fetch_assoc($rsMunicipios));
  $rows = mysql_num_rows($rsMunicipios);
  if($rows > 0) {
      mysql_data_seek($rsMunicipios, 0);
	  $row_rsMunicipios = mysql_fetch_assoc($rsMunicipios);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fClaLoc">Localidad*:</label></td>
            <td class="TablaEntregaNIP">
                <div id="txtHint" style="margin:0px;"><?php echo "<script language='javascript'>muestraLocalidades('14','".$row_rsAlumno['cla_mun']."','".$row_rsAlumno['cla_loc']."')</script>"; ?></div>
			</td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fTipoAsent">Tipo de asentamiento*:</label></td>
            <td class="TablaEntregaNIP"><select name="fTipoAsent" id="fTipoAsent" title="Tipo de asentamiento humano">
              <?php
do {  
?>
              <option value="<?php echo $row_rsTipoAsentamiento['id_asentam']?>"<?php if (!(strcmp($row_rsTipoAsentamiento['id_asentam'], $row_rsAlumno['tipo_asent']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTipoAsentamiento['nom_asentam']?></option>
              <?php
} while ($row_rsTipoAsentamiento = mysql_fetch_assoc($rsTipoAsentamiento));
  $rows = mysql_num_rows($rsTipoAsentamiento);
  if($rows > 0) {
      mysql_data_seek($rsTipoAsentamiento, 0);
	  $row_rsTipoAsentamiento = mysql_fetch_assoc($rsTipoAsentamiento);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fNomAsent">Nombre de asentamiento*:</label></td>
            <td class="TablaEntregaNIP"><input name="fNomAsent" type="text" required id="fNomAsent" value="<?php echo $row_rsAlumno['nom_asent']; ?>" maxlength="100" title="Nombre de asentamiento humano"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fCodPost">Código postal*:</label></td>
            <td class="TablaEntregaNIP"><input name="fCodPost" type="text" required id="fCodPost" pattern="[0-9]{5,}" value="<?php echo $row_rsAlumno['cod_post']; ?>" maxlength="5" title="Código Postal del domicilio del estudiante"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fTipoVial">Tipo de vialidad*:</label></td>
            <td class="TablaEntregaNIP"><select name="fTipoVial" id="fTipoVial" title="Tipo de vialidad del domicilio del estudiante">
              <?php
do {  
?>
              <option value="<?php echo $row_rsTipoVialidad['id_vialidad']?>"<?php if (!(strcmp($row_rsTipoVialidad['id_vialidad'], $row_rsAlumno['tipo_vial']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTipoVialidad['nom_vialidad']?></option>
              <?php
} while ($row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad));
  $rows = mysql_num_rows($rsTipoVialidad);
  if($rows > 0) {
      mysql_data_seek($rsTipoVialidad, 0);
	  $row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fNomVialCarrCam">Nombre de Vialidad*:</label></td>
            <td class="TablaEntregaNIP"><input name="fNomVialCarrCam" type="text" required id="fNomVialCarrCam" value="<?php echo $row_rsAlumno['nom_vial_carr_cam']; ?>" maxlength="100" title="Nombre de vialidad del domicilio del estudiante"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fTipoRef1">Tipo de referencia 1*:</label></td>
            <td class="TablaEntregaNIP"><select name="fTipoRef1" id="fTipoRef1" title="Tipo de referencia 1">
              <?php
do {  
?>
              <option value="<?php echo $row_rsTipoVialidad['id_vialidad']?>"<?php if (!(strcmp($row_rsTipoVialidad['id_vialidad'], $row_rsAlumno['tipo_ref_1']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTipoVialidad['nom_vialidad']?></option>
              <?php
} while ($row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad));
  $rows = mysql_num_rows($rsTipoVialidad);
  if($rows > 0) {
      mysql_data_seek($rsTipoVialidad, 0);
	  $row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad);
  }
?>
            </select> Viendo tu casa de frente,</td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fNomRef1">Nombre referencia 1*:</label></td>
            <td class="TablaEntregaNIP"><input name="fNomRef1" type="text" id="fNomRef1" value="<?php echo $row_rsAlumno['nom_ref_1']; ?>" maxlength="100" title="Viendo tu casa de frente, ¿cuál es el nombre de la vialidad que se ubica a tu derecha?" required> ¿cuál es el nombre de la vialidad que se ubica a tu derecha?</td>
          </tr>

          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fTipoRef2">Tipo de referencia 2*:</label></td>
            <td class="TablaEntregaNIP"><select name="fTipoRef2" id="fTipoRef2" title="Tipo de referencia 2">
              <?php
do {  
?>
              <option value="<?php echo $row_rsTipoVialidad['id_vialidad']?>"<?php if (!(strcmp($row_rsTipoVialidad['id_vialidad'], $row_rsAlumno['tipo_ref_2']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTipoVialidad['nom_vialidad']?></option>
              <?php
} while ($row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad));
  $rows = mysql_num_rows($rsTipoVialidad);
  if($rows > 0) {
      mysql_data_seek($rsTipoVialidad, 0);
	  $row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad);
  }
?>
            </select> Viendo tu casa de frente,</td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fNomRef2">Nombre referencia 2*:</label></td>
            <td class="TablaEntregaNIP"><input name="fNomRef2" type="text" id="fNomRef2" value="<?php echo $row_rsAlumno['nom_ref_2']; ?>" maxlength="100" title="Nombre de referencia 2" required> ¿cuál es el nombre de la vialidad que se ubica a tu izquierda?</td>
          </tr>

          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fTipoRef3">Tipo de referencia 3*:</label></td>
            <td class="TablaEntregaNIP"><select name="fTipoRef3" id="fTipoRef3" title="Tipo de referencia 3">
              <?php
do {  
?>
              <option value="<?php echo $row_rsTipoVialidad['id_vialidad']?>"<?php if (!(strcmp($row_rsTipoVialidad['id_vialidad'], $row_rsAlumno['tipo_ref_3']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTipoVialidad['nom_vialidad']?></option>
              <?php
} while ($row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad));
  $rows = mysql_num_rows($rsTipoVialidad);
  if($rows > 0) {
      mysql_data_seek($rsTipoVialidad, 0);
	  $row_rsTipoVialidad = mysql_fetch_assoc($rsTipoVialidad);
  }
?>
            </select> Viendo tu casa de frente,</td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fNomRef3">Nombre referencia 3*:</label></td>
            <td class="TablaEntregaNIP"><input name="fNomRef3" type="text" id="fNomRef3" value="<?php echo $row_rsAlumno['nom_ref_3']; ?>" maxlength="100" title="Nombre de referencia 3" required> ¿cuál es el nombre de la vialidad que se ubica atrás de tu casa?</td>
          </tr>

          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fCodCarr">Código carretera:</label></td>
            <td class="TablaEntregaNIP"><input name="fCodCarr" type="text" id="fCodCarr" pattern="[0-9]{1,5}" value="<?php echo $row_rsAlumno['cod_carr']; ?>" maxlength="5" title="Código de la carretera"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fAdmCarr">Administración carretera:</label></td>
            <td class="TablaEntregaNIP"><select name="fAdmCarr" id="fAdmCarr" title="Infraestructura de la vialidad">
              <option value="" <?php if (!(strcmp("", $row_rsAlumno['adm_carr']))) {echo "selected=\"selected\"";} ?>>Ninguna</option>
              <option value="2" <?php if (!(strcmp(2, $row_rsAlumno['adm_carr']))) {echo "selected=\"selected\"";} ?>>Estatal</option>
              <option value="1" <?php if (!(strcmp(1, $row_rsAlumno['adm_carr']))) {echo "selected=\"selected\"";} ?>>Federal</option>
              <option value="3" <?php if (!(strcmp(3, $row_rsAlumno['adm_carr']))) {echo "selected=\"selected\"";} ?>>Municipal</option>
              <option value="4" <?php if (!(strcmp(4, $row_rsAlumno['adm_carr']))) {echo "selected=\"selected\"";} ?>>Particular</option>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fDerTran">Derecho de tránsito:</label></td>
            <td class="TablaEntregaNIP"><select name="fDerTran" id="fDerTran" title="Tipo de paso para los vehículos">
              <option value="" <?php if (!(strcmp("", $row_rsAlumno['der_tran']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
              <option value="1" <?php if (!(strcmp(1, $row_rsAlumno['der_tran']))) {echo "selected=\"selected\"";} ?>>Cuota</option>
              <option value="2" <?php if (!(strcmp(2, $row_rsAlumno['der_tran']))) {echo "selected=\"selected\"";} ?>>Libre</option>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fMargen">Margen:</label></td>
            <td class="TablaEntregaNIP"><select name="fMargen" id="fMargen" title="Margen en que se encuentra el domicilio">
              <option value="" <?php if (!(strcmp("", $row_rsAlumno['margen']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
              <option value="2" <?php if (!(strcmp(2, $row_rsAlumno['margen']))) {echo "selected=\"selected\"";} ?>>Derecho</option>
              <option value="1" <?php if (!(strcmp(1, $row_rsAlumno['margen']))) {echo "selected=\"selected\"";} ?>>Izquierdo</option>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fTramo">Tramo:</label></td>
            <td class="TablaEntregaNIP"><input name="fTramo" type="text" id="fTramo" value="<?php echo $row_rsAlumno['tramo']; ?>" maxlength="100" title="Tramo carretero comprendido entre el origen y el destino"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fKilometro">Kilómetro:</label></td>
            <td class="TablaEntregaNIP"><input name="fKilometro" type="text" id="fKilometro" pattern="[0-9]{1,5}" value="<?php echo $row_rsAlumno['kilometro']; ?>" maxlength="5" title="Kilómetro dentro del tramo carretero"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fNumExt">Número exterior:</label></td>
            <td class="TablaEntregaNIP"><input name="fNumExt" type="text" id="fNumExt" value="<?php echo $row_rsAlumno['num_ext']; ?>" maxlength="10" title="Número exterior del domicilio del estudiante"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fNumInt">Número interior:</label></td>
            <td class="TablaEntregaNIP"><input name="fNumInt" type="text" id="fNumInt" value="<?php echo $row_rsAlumno['num_int']; ?>" maxlength="10" title="Número interior del domicilio del estudiante"></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fDescUbic">Descripción de ubicación:</label></td>
            <td class="TablaEntregaNIP"><input name="fDescUbic" type="text" id="fDescUbic" value="<?php echo $row_rsAlumno['desc_ubic']; ?>" maxlength="100" title="Descripción de la ubicación" style="width:98%;"></td>
          </tr>
          <tr>
            <th colspan="2" align="center">OTRA INFORMACION</th>
            </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Genero*:</td>
            <td class="TablaEntregaNIP"><select name="fGenero" id="fGenero" title="Género del estudiante">
              <option value="H" <?php if (!(strcmp("H", $row_rsAlumno['genero']))) {echo "selected=\"selected\"";} ?>>Hombre</option>
              <option value="M" <?php if (!(strcmp("M", $row_rsAlumno['genero']))) {echo "selected=\"selected\"";} ?>>Mujer</option>
              <option value="" <?php if (!(strcmp("", $row_rsAlumno['genero']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fGrupo">Grupo:</label></td>
            <td class="TablaEntregaNIP"><select name="fGrupo" id="fGrupo" title="Grupo en el que asiste el estudiante a la escuela">
              <option value="A" <?php if (!(strcmp("A", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>A</option>
              <option value="B" <?php if (!(strcmp("B", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>B</option>
              <option value="C" <?php if (!(strcmp("C", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>C</option>
              <option value="D" <?php if (!(strcmp("D", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>D</option>
              <option value="E" <?php if (!(strcmp("E", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>E</option>
              <option value="F" <?php if (!(strcmp("F", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>F</option>
              <option value="G" <?php if (!(strcmp("G", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>G</option>
              <option value="H" <?php if (!(strcmp("H", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>H</option>
              <option value="I" <?php if (!(strcmp("I", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>I</option>
              <option value="J" <?php if (!(strcmp("J", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>J</option>
              <option value="K" <?php if (!(strcmp("K", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>K</option>
              <option value="L" <?php if (!(strcmp("L", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>L</option>
              <option value="M" <?php if (!(strcmp("M", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>M</option>
              <option value="N" <?php if (!(strcmp("N", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>N</option>
              <option value="O" <?php if (!(strcmp("O", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>O</option>
              <option value="P" <?php if (!(strcmp("P", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>P</option>
              <option value="Q" <?php if (!(strcmp("Q", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>Q</option>
              <option value="R" <?php if (!(strcmp("R", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>R</option>
              <option value="S" <?php if (!(strcmp("S", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>S</option>
              <option value="T" <?php if (!(strcmp("T", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>T</option>
              <option value="U" <?php if (!(strcmp("U", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>U</option>
              <option value="V" <?php if (!(strcmp("V", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>V</option>
              <option value="W" <?php if (!(strcmp("W", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>W</option>
              <option value="X" <?php if (!(strcmp("X", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>X</option>
              <option value="Y" <?php if (!(strcmp("Y", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>Y</option>
              <option value="Z" <?php if (!(strcmp("Z", $row_rsAlumno['grupo']))) {echo "selected=\"selected\"";} ?>>Z</option>
              </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP"><label for="fTurno3">Turno:</label></td>
            <td class="TablaEntregaNIP"><select name="fTurno" id="fTurno2" title="Turno en el que asiste el estudiante a la escuela">
              <option value="Matutino" <?php if (!(strcmp("Matutino", $row_rsAlumno['turno']))) {echo "selected=\"selected\"";} ?>>Matutino</option>
              <option value="Vespertino" <?php if (!(strcmp("Vespertino", $row_rsAlumno['turno']))) {echo "selected=\"selected\"";} ?>>Vespertino</option>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Tel. emergencia:</td>
            <td class="TablaEntregaNIP"><input name="fTelEme" type="text" id="fTelEme" pattern="[0-9]{10,}" title="Telefono al cual llamar en caso de emergencia, 10 dígitos (Opcional)" value="<?php echo $row_rsAlumno['tel_eme']; ?>" maxlength="10">
              Vacio ó 10 dígitos exactos</td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Error/Mensaje en plataforma:</td>
            <td class="TablaEntregaNIP"><select name="fMsgErrPlat" id="fMsgErrPlat" title="Selecciona si se presento algun mensaje/error en plataforma. Si no aparece en el listado entonces selecciona 'Otro' y escríbelo a continuación como 'Comentario'">
              <option value="" <?php if (!(strcmp("", $row_rsAlumno['msg_err_plat']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rsMsgErrPlat['id_err']?>"<?php if (!(strcmp($row_rsMsgErrPlat['id_err'], $row_rsAlumno['msg_err_plat']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsMsgErrPlat['Error']?></option>
              <?php
} while ($row_rsMsgErrPlat = mysql_fetch_assoc($rsMsgErrPlat));
  $rows = mysql_num_rows($rsMsgErrPlat);
  if($rows > 0) {
      mysql_data_seek($rsMsgErrPlat, 0);
	  $row_rsMsgErrPlat = mysql_fetch_assoc($rsMsgErrPlat);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td align="right" class="TablaEntregaNIP">Comentario:</td>
            <td class="TablaEntregaNIP"><input name="fComentario" type="text" id="fComentario" value="<?php echo $row_rsAlumno['comentario']; ?>" size="70" maxlength="200" title="Comentario, 'Otro' error ó Mensaje (Opcional)" style="width:98%;"></td>
          </tr>
          <tr>
            <td height="30" colspan="2" align="center"><input type="submit" name="bEnviar" id="bEnviar" value="Enviar">
              <input type="reset" name="bRestablecer" id="bRestablecer" value="Restablecer"></td>
            </tr>
        </table>
       </div>
        <input type="hidden" name="MM_update" value="form1">
      </form></td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><a href="consulta_bbbj.php" class="estilo3">Regresar</a></td>
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

mysql_free_result($rsMsgErrPlat);

mysql_free_result($rsTipoAsentamiento);

mysql_free_result($rsTipoVialidad);

mysql_free_result($rsEstados);

mysql_free_result($rsMunicipios);

mysql_free_result($rsLocalidades);
?>
