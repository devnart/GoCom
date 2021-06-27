/* global woodmart_settings */
(function($) {
	woodmartThemeModule.instagramAjaxQuery = function() {
		$('.instagram-widget').each(function() {
			var $instagram = $(this);

			if (!$instagram.hasClass('instagram-with-error')) {
				return;
			}

			var username = $instagram.data('username');
			var atts = $instagram.data('atts');
			var request_param = username.indexOf('#') > -1 ? 'explore/tags/' + username.substr(1) : username;
			var url = 'https://www.instagram.com/' + request_param + '/';

			$instagram.addClass('loading');

			$.ajax({
				url    : url,
				success: function(response) {
					$.ajax({
						url     : woodmart_settings.ajaxurl,
						data    : {
							action: 'woodmart_instagram_ajax_query',
							body  : response,
							atts  : atts
						},
						dataType: 'json',
						method  : 'POST',
						success : function(response) {
							$instagram.parent().html(response);
							woodmartThemeModule.$document.trigger('wdInstagramAjaxSuccess');
						},
						error   : function() {
							console.log('instagram ajax error');
						}
					});
				},
				error  : function() {
					console.log('instagram ajax error');
				}
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.instagramAjaxQuery();
	});
})(jQuery);
