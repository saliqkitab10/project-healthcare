"use strict";
/**********************************
************ CWS DEFAULT **********
**********************************/

jQuery(document).ready(function (){

	jQuery("#mobile_menu .menu-item.menu-item-has-children").on( "click", function (e){
		event.stopPropagation(e);
		jQuery(this).toggleClass('active_menu');
		jQuery(this).children('.sub-menu').slideToggle();
	});	

});	