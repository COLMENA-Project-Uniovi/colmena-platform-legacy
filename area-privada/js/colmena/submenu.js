

/*SUBMENU*/

function loadSubmenu(){
	'use strict';
	$("#menu-navegacion section.desplegable").mouseenter(function(){
		var elemento = $(this);
		var posicion = elemento.position();
		var height = elemento.height();
		var alto = posicion.top + height+26;
		var left = posicion.left;
		
		var submenu = elemento.children(".submenu");
		submenu.css("top",alto);
	
		submenu.show();
	});
	
	$("#menu-navegacion section.desplegable").mouseleave(function(){
		$(this).children(".submenu").hide();
	});
}


