"use strict";
(function($) {
	$(document).ready(function() {
		cwsInitAdditionalItemOptions();
		cwsInitSelectIcons();
		cwsInitColorPicker();
		cwsInitAjaxCatch();
	});

	function cwsInitAjaxCatch() {
		jQuery(document).ajaxSuccess(function(e, xhr, settings) {
			if (undefined !== settings.data) {
				
				var action = settings.data.match(/action=(.+?)($|&)/);
				if (action && action[1] == 'add-menu-item'){
					cwsInitSelectIcons();
					cwsInitColorPicker();
				}
			}
		});
	}

	//Function that serializes additional menu item options in a single field
	function cwsInitAdditionalItemOptions() {
		var navForm = $('#update-nav-menu');

		navForm.on('change', '[data-item-option]', function() {
			cwsGenerateSerializedString();
		});
	}

	function cwsGenerateSerializedString() {
		var dataArrayString = '';
		var navForm = $('#update-nav-menu');
		var menuItemsData = navForm.find("[data-name]");

		menuItemsData.each(function() {
			//get it's value and name
			var attributeName = $(this).data('name');
			var attributeVal  = $(this).val();

			if(attributeVal !== '') {
				//check if current field is checkbox
				if($(this).is('input[type="checkbox"]')) {
					//append it to serialized string only if it's checked
					if($(this).is(':checked')) {
						dataArrayString += attributeName+"="+attributeVal+'&';
					}
				} else {
					dataArrayString += attributeName+"="+attributeVal+'&';
				}
			}
		});

		//remove last & character
		dataArrayString = dataArrayString.substr(0, dataArrayString.length - 1);

		if($('input[name="cws_menu_options"]').length) {
			$('input[name="cws_menu_options"]').val(encodeURIComponent(dataArrayString));
		} else {
			//generate hidden input field html with serialized string value
			var hiddenMenuItem = '<input type="hidden" name="cws_menu_options" value="'+encodeURIComponent(dataArrayString)+'">';

			//append hidden options field to navigation form
			navForm.append(hiddenMenuItem);
		}
	}

	function cwsInitSelectIcons() {
		$('select.icons-select:not(.select2_init)').each(function(e, k){
			processSelects(k);
		});
	}

	function cwsInitColorPicker() {
		var init_ColorPicker_done = false;
		$('.color_picker:not(.wp-color-picker)').each(function(e, k){
			jQuery(k).wpColorPicker({
				change: function(event, ui){
					if (init_ColorPicker_done){
						//Get new value from color picker
						var new_color = ui.color.toString();
						//Set value to this text field
						this.value = new_color;
						//Triger change
						cwsGenerateSerializedString();
					}
				},
			});
			var color = k.value.length ? k.value : k.dataset['defaultColor'];
			k.value = color; 
			jQuery(k).wpColorPicker('color', color);
		});
		init_ColorPicker_done = true;
	}

	function processSelects(k) {
		jQuery(k).select2({
			allowClear: true,
			placeholder: " ",
			templateResult: addIconToSelectFa,
			templateSelection: addIconToSelectFa,
			escapeMarkup: function(m) { return m; }
		});
		jQuery(k).addClass('select2_init');
	}

	function addIconToSelectFa(icon) {
		if ( icon.hasOwnProperty( 'id' ) && icon.id.length > 0 ) {
			return "<span><i class='" + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.text.toUpperCase() + "</span>";
		} else {
			return icon.text;
		}
	}	

})(jQuery);