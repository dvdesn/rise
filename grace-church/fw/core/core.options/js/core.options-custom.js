/* global jQuery:false */

jQuery(document).ready(function() {
    "use strict";
	GRACE_CHURCH_GLOBALS['media_frame'] = null;
	GRACE_CHURCH_GLOBALS['media_link'] = '';
});

function grace_church_show_media_manager(el) {
	"use strict";

	GRACE_CHURCH_GLOBALS['media_link'] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( GRACE_CHURCH_GLOBALS['media_frame'] ) {
		GRACE_CHURCH_GLOBALS['media_frame'].open();
		return false;
	}

	// Create the media frame.
	GRACE_CHURCH_GLOBALS['media_frame'] = wp.media({
		// Set the title of the modal.
		title: GRACE_CHURCH_GLOBALS['media_link'].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: 'image'
		},
		// Multiple choise
		multiple: GRACE_CHURCH_GLOBALS['media_link'].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: GRACE_CHURCH_GLOBALS['media_link'].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	GRACE_CHURCH_GLOBALS['media_frame'].on( 'select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+GRACE_CHURCH_GLOBALS['media_link'].data('linked-field')).eq(0);
		var attachment = '';
		if (GRACE_CHURCH_GLOBALS['media_link'].data('multiple')===true) {
			GRACE_CHURCH_GLOBALS['media_frame'].state().get('selection').map( function( att ) {
				attachment += (attachment ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment = val + (val ? "\n" : '') + attachment;
		} else {
			attachment = GRACE_CHURCH_GLOBALS['media_frame'].state().get('selection').first().toJSON().url;
		}
		field.val(attachment);
		field.trigger('change');
	});

	// Finally, open the modal.
	GRACE_CHURCH_GLOBALS['media_frame'].open();
	return false;
}
