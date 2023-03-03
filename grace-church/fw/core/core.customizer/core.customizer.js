// Grace-Church Customizer script

jQuery(document).ready(function(){
	"use strict";

	// Add scheme name to each demo block
	jQuery('.to_demo_wrap').each(function(idx) {
		"use strict";
		var tab = jQuery(this).parents('.grace_church_options_tab_content');
		var tab_id = tab.attr('id');
		var scheme = tab_id.substring(4, tab_id.length-8);
		var info = jQuery(this).parents('.grace_church_options_field_info').attr('id', 'to_demo_scheme_'+scheme).attr('data-scheme', scheme).data('scheme', scheme);
		if (idx==0 && jQuery(this).find('.to_demo_pin').length==1 && grace_church_get_cookie('to_demo_fixed')==1) grace_church_options_fix_demo(info);
	});

	// Stick demo panel
	jQuery(document).on('click', '.grace_church_options_field_info .to_demo_pin', function(e) {
		grace_church_options_fix_demo(jQuery(this).parents('.grace_church_options_field_info'));
		e.preventDefault();
		return false;
	});	

	// Update demo on page load
	grace_church_options_decorate_demo();

	// Update demo on change fields
	jQuery('#partition_schemes_content .grace_church_options_input,#partition_schemes_content .grace_church_options_field input[type="hidden"]').change(function(e) {
		"use strict";
		var tab = jQuery(this).parents('.grace_church_options_tab_content');
		var tab_id = tab.attr('id');
		var scheme = tab_id.substring(4, tab_id.length-8);
		grace_church_options_decorate_demo(scheme);
	});

	// Update demo on blur color fields
	jQuery('#partition_schemes_content .grace_church_options_input_color').blur(function(e) {
		"use strict";
		var tab = jQuery(this).parents('.grace_church_options_tab_content');
		var tab_id = tab.attr('id');
		var scheme = tab_id.substring(4, tab_id.length-8);
		grace_church_options_decorate_demo(scheme);
	});
	
	// Sort tabs with color schemes
	jQuery('#partition_schemes_content .grace_church_options_tab > ul').sortable({
		update: function(event, ui) {
			var tabs = "";
			ui.item.parents('.grace_church_options_tab').find('> ul > li').each(function() {
				tabs += (tabs ? GRACE_CHURCH_GLOBALS['to_delimiter'] : '') + jQuery(this).attr('id').substr(4);
			});
			ui.item.parents('.grace_church_options_tab').find('> .grace_church_options_schemes_order').val(tabs);
		}
	}).disableSelection().after('<input type="hidden" name="grace_church_options_schemes_order" class="grace_church_options_schemes_order" value="">');
});


// When switch tabs - update fixed demo block
function grace_church_init_hidden_elements(panel) {
	"use strict";
	var demo = panel.find('.grace_church_options_field_info[data-scheme]');
	if (demo.length == 0) return;
	var info2 = jQuery('.grace_church_options').find('>.grace_church_options_field_info');
	if (info2.length == 0) return;
	var scheme = demo.data('scheme');
	info2.attr('data-scheme', scheme).data('scheme', scheme);
	grace_church_options_decorate_demo(scheme);
}



// Delete current color scheme
function grace_church_options_action_scheme_delete(obj) {
	"use strict";
	var button = jQuery(obj);
	var tab_id = button.parents('.grace_church_options_tab_content').attr('id');
	var scheme = tab_id.substring(4, tab_id.length-8);
	grace_church_message_confirm(GRACE_CHURCH_GLOBALS['to_strings']['scheme_delete_confirm'], GRACE_CHURCH_GLOBALS['to_strings']['scheme_delete']+': "'+scheme+'"', function(btn) {
		"use strict";
		if (btn != 1) return;
		var data = {
			action: 'grace_church_options_scheme_delete',
			nonce: GRACE_CHURCH_GLOBALS['ajax_nonce'],
			scheme: scheme,
			order: button.parents('.grace_church_options_tab').find('> .grace_church_options_schemes_order').val()
		};
		setTimeout(function(){
			grace_church_message_info(GRACE_CHURCH_GLOBALS['to_strings']['recompile_styles'], GRACE_CHURCH_GLOBALS['to_strings']['wait'], 'spin3 animate-spin', 60000);
		}, 600);
		jQuery.post(GRACE_CHURCH_GLOBALS['ajax_url'], data, function(response) {
			"use strict";
			var rez = JSON.parse(response);
			if (rez.error === '') {
				grace_church_message_success(GRACE_CHURCH_GLOBALS['to_strings']['scheme_delete_complete']+'<br>'+GRACE_CHURCH_GLOBALS['to_strings']['reload_page'], GRACE_CHURCH_GLOBALS['to_strings']['scheme_delete']);
				setTimeout(function() { location.reload(); }, 3000);
			} else {
				grace_church_message_warning(GRACE_CHURCH_GLOBALS['to_strings']['scheme_delete_failed']+':<br>'+rez.error, GRACE_CHURCH_GLOBALS['to_strings']['scheme_delete']);
			}
		});
		
	});
}


// Copy current color scheme
function grace_church_options_action_scheme_copy(obj) {
	"use strict";
	var button = jQuery(obj);
	var tab_id = button.parents('.grace_church_options_tab_content').attr('id');
	var scheme = tab_id.substring(4, tab_id.length-8);
	grace_church_message_confirm(GRACE_CHURCH_GLOBALS['to_strings']['scheme_copy_confirm'], GRACE_CHURCH_GLOBALS['to_strings']['scheme_copy']+': "'+scheme+'"', function(btn) {
		"use strict";
		if (btn != 1) return;
		var data = {
			action: 'grace_church_options_scheme_copy',
			nonce: GRACE_CHURCH_GLOBALS['ajax_nonce'],
			scheme: scheme,
			order: button.parents('.grace_church_options_tab').find('> .grace_church_options_schemes_order').val()
		};
		setTimeout(function(){
			grace_church_message_info(GRACE_CHURCH_GLOBALS['to_strings']['recompile_styles'], GRACE_CHURCH_GLOBALS['to_strings']['wait'], 'spin3 animate-spin', 60000);
		}, 600);
		jQuery.post(GRACE_CHURCH_GLOBALS['ajax_url'], data, function(response) {
			"use strict";
			var rez = JSON.parse(response);
			if (rez.error === '') {
				grace_church_message_success(GRACE_CHURCH_GLOBALS['to_strings']['scheme_copy_complete']+'<br>'+GRACE_CHURCH_GLOBALS['to_strings']['reload_page'], GRACE_CHURCH_GLOBALS['to_strings']['scheme_copy']);
				setTimeout(function() { location.reload(); }, 3000);
			} else {
				grace_church_message_warning(GRACE_CHURCH_GLOBALS['to_strings']['scheme_copy_failed']+':<br>'+rez.error, GRACE_CHURCH_GLOBALS['to_strings']['scheme_copy']);
			}
		});
		
	});
}


// Fix/Unfix demo panel
function grace_church_options_fix_demo(info) {
    "use strict";
	var info2 = info.parents('.grace_church_options').toggleClass('to_demo_fixed').find('>.grace_church_options_field_info');
	if (info2.length == 0) {
		var clonedObj = info.clone();
		clonedObj.removeAttr('id');
		info.parents('.grace_church_options').append(clonedObj);
		info2 = info.parents('.grace_church_options').find('>.grace_church_options_field_info');
	}
	grace_church_set_cookie('to_demo_fixed', jQuery('.grace_church_options').hasClass('to_demo_fixed') ? 1 : 0, 365);
}


// Decorate demo block
function grace_church_options_decorate_demo() {
	"use strict";
	var refresh_scheme = arguments[0] ? arguments[0] : '';

	jQuery('.to_demo_body').each(function() {
		"use strict";
		
		var scheme = jQuery(this).parents('.grace_church_options_field_info').data('scheme');
		if (refresh_scheme!='' && scheme!=refresh_scheme) return;
		
		var tab = jQuery('#tab_'+scheme+'_content');

		var clr = '';

		// Body
		jQuery(this).css({
			'border': '1px solid '+((clr=tab.find('[name="'+scheme+'-bd_color"]').val()) ? clr : 'transparent'),
			'backgroundColor': (clr=tab.find('[name="'+scheme+'-bg_color"]').val()) ? clr : 'transparent'
		});
		var img  = tab.find('[name="'+scheme+'-bg_image"]').val();
		var img2 = tab.find('[name="'+scheme+'-bg_image2"]').val();
		if (img || img2) {
			jQuery(this).css({
				'backgroundImage': (img2 ? 'url('+img2+')' : '') + (img ? (img2 ? ',' : '') + 'url('+img+')' : ''),
				'backgroundRepeat': (img2 ? tab.find('[name="'+scheme+'-bg_image2_repeat"]').val() : '') + (img ? (img2 ? ',' : '') + tab.find('[name="'+scheme+'-bg_image_repeat"]').val() : ''),
				'backgroundPosition': (img2 ? tab.find('[name="'+scheme+'-bg_image2_position"]').val() : '') + (img ? (img2 ? ',' : '') + tab.find('[name="'+scheme+'-bg_image_position"]').val() : '')
			});
		} else
			jQuery(this).css({'backgroundImage': 'none'});
		
		
		
		// Accent 1
		if (jQuery(this).find('.to_demo_accent1').length > 0) {
			jQuery(this).find('.to_demo_accent1').css({'color': (clr=tab.find('[name="'+scheme+'-accent1"]').val()) ? clr : 'inherit'});
			jQuery(this).find('.to_demo_accent1_hover').css({'color': (clr=tab.find('[name="'+scheme+'-accent1_hover"]').val()) ? clr : 'inherit'});
			jQuery(this).find('.to_demo_accent1_bg').css({'backgroundColor': (clr=tab.find('[name="'+scheme+'-accent1"]').val()) ? clr : 'transparent'});
			jQuery(this).find('.to_demo_accent1_hover_bg').css({'backgroundColor': (clr=tab.find('[name="'+scheme+'-accent1_hover"]').val()) ? clr : 'transparent'});
		}

		// Accent 2
		if (jQuery(this).find('.to_demo_accent2').length > 0) {
			jQuery(this).find('.to_demo_accent2').css({'color': (clr=tab.find('[name="'+scheme+'-accent2"]').val()) ? clr : 'inherit'});
			jQuery(this).find('.to_demo_accent2_hover').css({'color': (clr=tab.find('[name="'+scheme+'-accent2_hover"]').val()) ? clr : 'inherit'});
			jQuery(this).find('.to_demo_accent2_bg').css({'backgroundColor': (clr=tab.find('[name="'+scheme+'-accent2"]').val()) ? clr : 'transparent'});
			jQuery(this).find('.to_demo_accent2_hover_bg').css({'backgroundColor': (clr=tab.find('[name="'+scheme+'-accent2_hover"]').val()) ? clr : 'transparent'});
		}

		// Accent 3
		if (jQuery(this).find('.to_demo_accent3').length > 0) {
			jQuery(this).find('.to_demo_accent3').css({'color': (clr=tab.find('[name="'+scheme+'-accent3"]').val()) ? clr : 'inherit'});
			jQuery(this).find('.to_demo_accent3_hover').css({'color': (clr=tab.find('[name="'+scheme+'-accent3_hover"]').val()) ? clr : 'inherit'});
			jQuery(this).find('.to_demo_accent3_bg').css({'backgroundColor': (clr=tab.find('[name="'+scheme+'-accent3"]').val()) ? clr : 'transparent'});
			jQuery(this).find('.to_demo_accent3_hover_bg').css({'backgroundColor': (clr=tab.find('[name="'+scheme+'-accent3_hover"]').val()) ? clr : 'transparent'});
		}
		
		
		
		// Headers
		jQuery(this).find('.to_demo_header').css({'color': (clr=tab.find('[name="'+scheme+'-text_dark"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_header_link').css({'color': (clr=tab.find('[name="'+scheme+'-text_dark"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_header_hover').css({'color': (clr=tab.find('[name="'+scheme+'-accent1_hover"]').val()) ? clr : 'inherit'});
		
		// Text and links
		jQuery(this).find('.to_demo_text').css({'color': (clr=tab.find('[name="'+scheme+'-text"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_info').css({'color': (clr=tab.find('[name="'+scheme+'-text_light"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_text_link,.to_demo_info_link').css({'color': (clr=tab.find('[name="'+scheme+'-accent1"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_text_hover,.to_demo_info_hover').css({'color': (clr=tab.find('[name="'+scheme+'-accent1_hover"]').val()) ? clr : 'inherit'});
		
		// Inverse
		jQuery(this).find('.to_demo_inverse_text').css({'color': (clr=tab.find('[name="'+scheme+'-inverse_text"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_inverse_light').css({'color': (clr=tab.find('[name="'+scheme+'-inverse_light"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_inverse_dark').css({'color': (clr=tab.find('[name="'+scheme+'-inverse_dark"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_inverse_link').css({'color': (clr=tab.find('[name="'+scheme+'-inverse_link"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_inverse_hover').css({'color': (clr=tab.find('[name="'+scheme+'-inverse_hover"]').val()) ? clr : 'inherit'});


		
		// Alternative blocks: highlight blocks and form fields
		jQuery(this).find('.to_demo_alter_text,.to_demo_field').css({'color': (clr=tab.find('[name="'+scheme+'-alter_text"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_alter_info').css({'color': (clr=tab.find('[name="'+scheme+'-alter_light"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_alter_header').css({'color': (clr=tab.find('[name="'+scheme+'-alter_dark"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_alter_link').css({'color': (clr=tab.find('[name="'+scheme+'-alter_link"]').val()) ? clr : 'inherit'});
		jQuery(this).find('.to_demo_alter_hover').css({'color': (clr=tab.find('[name="'+scheme+'-alter_hover"]').val()) ? clr : 'inherit'});

		// Highlight block and submenu
		jQuery(this).find('.to_demo_alter_block,.to_demo_field').css({
			'border': '1px solid '+((clr=tab.find('[name="'+scheme+'-alter_bd_color"]').val()) ? clr : 'transparent'),
			'backgroundColor': (clr=tab.find('[name="'+scheme+'-alter_bg_color"]').val()) ? clr : 'transparent'
		});

		// Focused field
		jQuery(this).find('.to_demo_field_focused').css({
			'border': '1px solid '+((clr=tab.find('[name="'+scheme+'-alter_bd_hover"]').val()) ? clr : 'transparent'),
			'backgroundColor': (clr=tab.find('[name="'+scheme+'-alter_bg_hover"]').val()) ? clr : 'transparent',
			'color': (clr=tab.find('[name="'+scheme+'-alter_dark"]').val()) ? clr : 'inherit'
		});

	});
}
