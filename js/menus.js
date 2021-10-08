// JavaScript Document
function muestraMenuMain(menu_group, link_pre){
	//Menu para el grupo de Administradores
	var administrador_menu = 			["Inicio","Mi perfil","Inventario","Mantenimiento a BD","Soporte","Usuarios","Becas"];
	var administrador_menu_links =		["index.php","todos/perfil_view.php","inventario/index.php","mant_bd/index.php","soporte/index.php", "usuarios/index.php","becas/adm_becas.php"];
	var administrador_submenu = 		[[],[],["Equipo de cómputo","Mobiliario"],["Direcciones IP","General","Inventario","Lista de NRC","Niveles de acceso","Soporte"],["Solicitudes","Bitácoras cómputo"],[],["Actualizar padrón","Mensajes plataforma"]];
	var administrador_submenu_links =	[[],[],["inventario/inv_computo.php","inventario/inv_mobiliario.php"],["mant_bd/dir_ip_list.php","mant_bd/general.php","mant_bd/inventario.php","mant_bd/lista_nrc.php","mant_bd/niv_acceso.php","mant_bd/soporte.php"],["soporte/solicitudes.php","soporte/bitacora_computo_list.php"],[],["becas/lista_padron.php","becas/lista_mensajes.php"]];
	//Menu para el grupo de Administrativos
	var administrativo_menu = 			["Inicio","Mi perfil"];
	var administrativo_menu_links =		["index.php","todos/perfil_view.php"];
	var administrativo_submenu = 		[[],[]];
	var administrativo_submenu_links =	[[],[]];
	//Menu para el grupo de Alumnos
	var alumno_menu = 					["Inicio","Mi perfil","Alumnos","Clases presenciales"];
	var alumno_menu_links =				["index.php","todos/perfil_view.php","alumnos/index.php","asist_pres/index.php"];
	var alumno_submenu = 				[[],[],[],[]];
	var alumno_submenu_links =			[[],[],[],[]];
	//Menu para el grupo de Profesores
	var profesor_menu = 				["Inicio","Mi perfil","NIPs Alumnos"];
	var profesor_menu_links =			["index.php","todos/perfil_view.php","usuarios/nips_alumnos.php"];
	var profesor_submenu = 				[[],[],[]];
	var profesor_submenu_links =		[[],[],[]];
	//Menu para el grupo de Servicio Social
	var ssocial_menu = 					["Inicio","Mi perfil","Alumnos","NIPs Alumnos"];
	var ssocial_menu_links =			["index.php","todos/perfil_view.php","alumnos/index.php","usuarios/nips_alumnos.php"];
	var ssocial_submenu = 				[[],[],[],[]];
	var ssocial_submenu_links =			[[],[],[],[]];
	//Menu para el grupo de Soporte
	var soporte_menu = 					["Inicio","Mi perfil","Soporte","Usuarios","NIPs Alumnos"];
	var soporte_menu_links =			["index.php","todos/perfil_view.php","soporte/index.php","usuarios/index.php","usuarios/nips_alumnos.php"];
	var soporte_submenu = 				[[],[],["Solicitudes","Bitácoras cómputo"],[],[]];
	var soporte_submenu_links =			[[],[],["soporte/solicitudes.php","soporte/bitacora_computo_list.php"],[],[]];
	//Menu por default
	var default_menu = 					["Inicio","1er ingreso","Semestre Base","Becas","Contacto","Miscelanea","Ayuda"];
	var default_menu_links =			["index.php","inf_1er_ing/index.php","sem_base/index.php","becas/index.php","contacto/index.php","miscelanea/index.php","ayuda/index.php"];
	var default_submenu = 				[[],[],[],["Benito Juarez"],[],["Clases presenciales","Repetidores"],[]];
	var default_submenu_links =			[[],[],[],["becas/consulta_bbbj.php"],[],["asist_pres/index.php","miscelanea/ua_repetir.php"],[]];
	//Variable para armar el string del menu
	var menu_string = "";
	
	menu_string = menu_string + "<ul>";
	switch(menu_group){
		case "Administrador":
			for (var i = 0; i < administrador_menu.length; i++) {
				if(administrador_submenu[i].length > 0){
					menu_string = menu_string + "<li class='dropdown'>";
					menu_string = menu_string + "<a href='" + link_pre + administrador_menu_links[i] + "' class='dropbtn'>" + administrador_menu[i] + "</a>"
					menu_string = menu_string + "<div class='dropdown-content'>"
					for(var j = 0; j < administrador_submenu[i].length; j++){
						menu_string = menu_string + "<a href='" + link_pre + administrador_submenu_links[i][j] + "'>" + administrador_submenu[i][j] + "</a>"
					}
					menu_string = menu_string + "</div>"
					menu_string = menu_string + "</li>";
				}else{
					menu_string = menu_string + "<li><a href='" + link_pre + administrador_menu_links[i] + "'>" + administrador_menu[i] + "</a></li>";
				}
			}
			break;
		case "Administrativo":
			for (var i = 0; i < administrativo_menu.length; i++) {
				if(administrativo_submenu[i].length > 0){
					menu_string = menu_string + "<li class='dropdown'>";
					menu_string = menu_string + "<a href='" + link_pre + administrativo_menu_links[i] + "' class='dropbtn'>" + administrativo_menu[i] + "</a>"
					menu_string = menu_string + "<div class='dropdown-content'>"
					for(var j = 0; j < administrativo_submenu[i].length; j++){
						menu_string = menu_string + "<a href='" + link_pre + administrativo_submenu_links[i][j] + "'>" + administrativo_submenu[i][j] + "</a>"
					}
					menu_string = menu_string + "</div>"
					menu_string = menu_string + "</li>";
				}else{
					menu_string = menu_string + "<li><a href='" + link_pre + administrativo_menu_links[i] + "'>" + administrativo_menu[i] + "</a></li>";
				}
			}
			break;
		case "Alumno":
			for (var i = 0; i < alumno_menu.length; i++) {
				if(alumno_submenu[i].length > 0){
					menu_string = menu_string + "<li class='dropdown'>";
					menu_string = menu_string + "<a href='" + link_pre + alumno_menu_links[i] + "' class='dropbtn'>" + alumno_menu[i] + "</a>"
					menu_string = menu_string + "<div class='dropdown-content'>"
					for(var j = 0; j < alumno_submenu[i].length; j++){
						menu_string = menu_string + "<a href='" + link_pre + alumno_submenu_links[i][j] + "'>" + alumno_submenu[i][j] + "</a>"
					}
					menu_string = menu_string + "</div>"
					menu_string = menu_string + "</li>";
				}else{
					menu_string = menu_string + "<li><a href='" + link_pre + alumno_menu_links[i] + "'>" + alumno_menu[i] + "</a></li>";
				}
			}
			break;
		case "Profesor":
			for (var i = 0; i < profesor_menu.length; i++) {
				if(profesor_submenu[i].length > 0){
					menu_string = menu_string + "<li class='dropdown'>";
					menu_string = menu_string + "<a href='" + link_pre + profesor_menu_links[i] + "' class='dropbtn'>" + profesor_menu[i] + "</a>"
					menu_string = menu_string + "<div class='dropdown-content'>"
					for(var j = 0; j < profesor_submenu[i].length; j++){
						menu_string = menu_string + "<a href='" + link_pre + profesor_submenu_links[i][j] + "'>" + profesor_submenu[i][j] + "</a>"
					}
					menu_string = menu_string + "</div>"
					menu_string = menu_string + "</li>";
				}else{
					menu_string = menu_string + "<li><a href='" + link_pre + profesor_menu_links[i] + "'>" + profesor_menu[i] + "</a></li>";
				}
			}
			break;
		case "Servicio social":
			for (var i = 0; i < ssocial_menu.length; i++) {
				if(ssocial_submenu[i].length > 0){
					menu_string = menu_string + "<li class='dropdown'>";
					menu_string = menu_string + "<a href='" + link_pre + ssocial_menu_links[i] + "' class='dropbtn'>" + ssocial_menu[i] + "</a>"
					menu_string = menu_string + "<div class='dropdown-content'>"
					for(var j = 0; j < ssocial_submenu[i].length; j++){
						menu_string = menu_string + "<a href='" + link_pre + ssocial_submenu_links[i][j] + "'>" + ssocial_submenu[i][j] + "</a>"
					}
					menu_string = menu_string + "</div>"
					menu_string = menu_string + "</li>";
				}else{
					menu_string = menu_string + "<li><a href='" + link_pre + ssocial_menu_links[i] + "'>" + ssocial_menu[i] + "</a></li>";
				}
			}
			break;
		case "Soporte":
			for (var i = 0; i < soporte_menu.length; i++) {
				if(soporte_submenu[i].length > 0){
					menu_string = menu_string + "<li class='dropdown'>";
					menu_string = menu_string + "<a href='" + link_pre + soporte_menu_links[i] + "' class='dropbtn'>" + soporte_menu[i] + "</a>"
					menu_string = menu_string + "<div class='dropdown-content'>"
					for(var j = 0; j < soporte_submenu[i].length; j++){
						menu_string = menu_string + "<a href='" + link_pre + soporte_submenu_links[i][j] + "'>" + soporte_submenu[i][j] + "</a>"
					}
					menu_string = menu_string + "</div>"
					menu_string = menu_string + "</li>";
				}else{
					menu_string = menu_string + "<li><a href='" + link_pre + soporte_menu_links[i] + "'>" + soporte_menu[i] + "</a></li>";
				}
			}
			break;
		default:
			for (var i = 0; i < default_menu.length; i++) {
				if(default_submenu[i].length > 0){
					menu_string = menu_string + "<li class='dropdown'>";
					menu_string = menu_string + "<a href='" + link_pre + default_menu_links[i] + "' class='dropbtn'>" + default_menu[i] + "</a>"
					menu_string = menu_string + "<div class='dropdown-content'>"
					for(var j = 0; j < default_submenu[i].length; j++){
						menu_string = menu_string + "<a href='" + link_pre + default_submenu_links[i][j] + "'>" + default_submenu[i][j] + "</a>"
					}
					menu_string = menu_string + "</div>"
					menu_string = menu_string + "</li>";
				}else{
					menu_string = menu_string + "<li><a href='" + link_pre + default_menu_links[i] + "'>" + default_menu[i] + "</a></li>";
				}
			}
	}
	menu_string = menu_string + "</ul>";
	document.write(menu_string);
}

function muestraMenuFooter(menu_group, link_pre){
	//Menu para el grupo de Administradores
	var administrador_menu = 		["Inicio","Mi perfil","Inventario","Mant. a BD","Soporte","Usuarios","Becas"];
	var administrador_menu_links =	["index.php","todos/perfil_view.php","inventario/index.php","mant_bd/index.php","soporte/index.php","usuarios/index.php","becas/adm_becas.php"];
	//Menu para el grupo de Administrativos
	var administrativo_menu = 		["Inicio","Mi perfil"];
	var administrativo_menu_links =	["index.php","todos/perfil_view.php"];
	//Menu para el grupo de Alumnos
	var alumno_menu = 				["Inicio","Mi perfil","Alumnos","Clases presenciales"];
	var alumno_menu_links =			["index.php","todos/perfil_view.php","alumnos/index.php","asist_pres/index.php"];
	//Menu para el grupo de Profesores
	var profesor_menu = 			["Inicio","Mi perfil","NIPs Alumnos"];
	var profesor_menu_links =		["index.php","todos/perfil_view.php","usuarios/nips_alumnos.php"];
	//Menu para el grupo de Servicio Social
	var ssocial_menu = 				["Inicio","Mi perfil","Alumnos","NIPs Alumnos"];
	var ssocial_menu_links =		["index.php","todos/perfil_view.php","alumnos/index.php","usuarios/nips_alumnos.php"];
	//Menu para el grupo de Soporte
	var soporte_menu = 				["Inicio","Mi perfil","Soporte","Usuarios","NIPs Alumnos"];
	var soporte_menu_links =		["index.php","todos/perfil_view.php","soporte/index.php","usuarios/index.php","usuarios/nips_alumnos.php"];
	//Menu por default
	var default_menu = 				["Inicio","1er ingreso","Semestre Base","Becas","Contacto","Miscelanea","Ayuda"];
	var default_menu_links =		["index.php","inf_1er_ing/index.php","sem_base/index.php","becas/index.php","contacto/index.php","miscelanea/index.php","ayuda/index.php"];
	//Variable para armar el string del menu
	var menu_string = "";
	
	menu_string = menu_string + "<table width='100%' border='0' cellspacing='0' cellpadding='0'>"
    menu_string = menu_string + "<tr>"
    menu_string = menu_string + "<td colspan='2' align='left'><p class='Marron Grande'>Men&uacute;</p></td>"
    menu_string = menu_string + "</tr>"
	switch(menu_group){
		case "Administrador":
			for (var i = 0; i < administrador_menu.length; i++) {
				menu_string = menu_string + "<tr>";
				menu_string = menu_string + "<td width='25'><img src='" + link_pre + "imagenes/footer_bullet.png' alt='HBullet " + i + "' name='HBullet" + i + "' width='25' height='18' id='HBullet" + i + "'></td>";
				menu_string = menu_string + "<td><a href='" + link_pre + administrador_menu_links[i] + "'>" + administrador_menu[i] + "</a></td>";
				menu_string = menu_string + "</tr>";
			}
			break;
		case "Administrativo":
			for (var i = 0; i < administrativo_menu.length; i++) {
				menu_string = menu_string + "<tr>";
				menu_string = menu_string + "<td width='25'><img src='" + link_pre + "imagenes/footer_bullet.png' alt='HBullet " + i + "' name='HBullet" + i + "' width='25' height='18' id='HBullet" + i + "'></td>";
				menu_string = menu_string + "<td><a href='" + link_pre + administrativo_menu_links[i] + "'>" + administrativo_menu[i] + "</a></td>";
				menu_string = menu_string + "</tr>";
			}
			break;
		case "Alumno":
			for (var i = 0; i < alumno_menu.length; i++) {
				menu_string = menu_string + "<tr>";
				menu_string = menu_string + "<td width='25'><img src='" + link_pre + "imagenes/footer_bullet.png' alt='HBullet " + i + "' name='HBullet" + i + "' width='25' height='18' id='HBullet" + i + "'></td>";
				menu_string = menu_string + "<td><a href='" + link_pre + alumno_menu_links[i] + "'>" + alumno_menu[i] + "</a></td>";
				menu_string = menu_string + "</tr>";
			}
			break;
		case "Profesor":
			for (var i = 0; i < profesor_menu.length; i++) {
				menu_string = menu_string + "<tr>";
				menu_string = menu_string + "<td width='25'><img src='" + link_pre + "imagenes/footer_bullet.png' alt='HBullet " + i + "' name='HBullet" + i + "' width='25' height='18' id='HBullet" + i + "'></td>";
				menu_string = menu_string + "<td><a href='" + link_pre + profesor_menu_links[i] + "'>" + profesor_menu[i] + "</a></td>";
				menu_string = menu_string + "</tr>";
			}
			break;
		case "Servicio social":
			for (var i = 0; i < ssocial_menu.length; i++) {
				menu_string = menu_string + "<tr>";
				menu_string = menu_string + "<td width='25'><img src='" + link_pre + "imagenes/footer_bullet.png' alt='HBullet " + i + "' name='HBullet" + i + "' width='25' height='18' id='HBullet" + i + "'></td>";
				menu_string = menu_string + "<td><a href='" + link_pre + ssocial_menu_links[i] + "'>" + ssocial_menu[i] + "</a></td>";
				menu_string = menu_string + "</tr>";
			}
			break;
		case "Soporte":
			for (var i = 0; i < soporte_menu.length; i++) {
				menu_string = menu_string + "<tr>";
				menu_string = menu_string + "<td width='25'><img src='" + link_pre + "imagenes/footer_bullet.png' alt='HBullet " + i + "' name='HBullet" + i + "' width='25' height='18' id='HBullet" + i + "'></td>";
				menu_string = menu_string + "<td><a href='" + link_pre + soporte_menu_links[i] + "'>" + soporte_menu[i] + "</a></td>";
				menu_string = menu_string + "</tr>";
			}
			break;
		default:
			for (var i = 0; i < default_menu.length; i++) {
				menu_string = menu_string + "<tr>";
				menu_string = menu_string + "<td width='25'><img src='" + link_pre + "imagenes/footer_bullet.png' alt='HBullet " + i + "' name='HBullet" + i + "' width='25' height='18' id='HBullet" + i + "'></td>";
				menu_string = menu_string + "<td><a href='" + link_pre + default_menu_links[i] + "'>" + default_menu[i] + "</a></td>";
				menu_string = menu_string + "</tr>";
			}
	}
	menu_string = menu_string + "</table>";
	document.write(menu_string);
}
