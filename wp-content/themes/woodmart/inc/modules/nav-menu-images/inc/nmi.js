/**
 * Display thumb when set as featured.
 *
 * Overwritess built in function.
 */
function WPSetThumbnailID( id ) {
	tb_remove();
	jQuery.post( ajaxurl, {
		action: "nmi_added_thumbnail",
		thumbnail_id: id,
		post_id: window.clicked_item_id,
		security: woodmartConfig.mega_menu_added_thumbnail_nonce,
	}, function( response ) {
		jQuery("li#menu-item-"+window.clicked_item_id+" .nmi-current-image").html( response );
		tb_remove();
	}
	);
};

function WPSetThumbnailHTML( html ) {
};

jQuery( document ).ready( function( $ ) {
	// Get all menu items
	var items = $("ul#menu-to-edit li.menu-item");

	// Go through all items and display link & thumb
	for ( var i = 0; i < items.length; i++ ) {
		var id = $(items[i]).children("#nmi_item_id").val();

		var sibling   = $("#edit-menu-item-attr-title-"+id).parent().parent();
		var image_div = $("li#menu-item-"+id+" .nmi-current-image");
		var link_div  = $("li#menu-item-"+id+" .nmi-upload-link");
		var customFields = $("li#menu-item-"+id+" .nmi-item-custom-fields");

		if ( customFields ) {
			sibling.after( customFields );
			customFields.show();
		}

		if ( link_div ) {
			link_div.show();
		}

		if ( image_div ) {
			image_div.show();
		}
	}

	// Save item ID on click on a link
	$(".nmi-upload-link").click( function() {
		window.clicked_item_id = $(this).parent().parent().children("#nmi_item_id").val();
	} );

	// Display alert when not added as featured
	window.send_to_editor = function( html ) {
		alert(nmi_vars.alert);
		tb_remove();
	};

	$('.nmi-item-custom-fields').find('select').bind('change', function(){

		var selectValue = $(this).val();

		var $block = $(this).parents('li').find('.nmi-block');
		var $width = $(this).parents('li').find('.nmi-width');
		var $height = $(this).parents('li').find('.nmi-height');

		if (selectValue == 'full-width') {
			$block.show();
			$width.hide();
			$height.hide();
		}

		if (selectValue == 'sized') {
			$block.show();
			$width.show();
			$height.show();
		}

		if (selectValue == 'default') {
			$block.hide();
			$width.hide();
			$height.hide();
		}

	}).change();

	// Menu block edit link
	$('.nmi-block select').change(function() {
		$('.edit-block-link').attr('href', $(this).find('option:selected').data('edit-link')).show();
	});

} );
