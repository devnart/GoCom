/* global woodmart_settings */
(function($) {
	woodmartThemeModule.headerBanner = function() {
		var banner_version = woodmart_settings.header_banner_version;

		if ('closed' === Cookies.get('woodmart_tb_banner_' + banner_version) || 'no' === woodmart_settings.header_banner_close_btn || 'no' === woodmart_settings.header_banner_enabled) {
			return;
		}

		if (!woodmartThemeModule.$body.hasClass('page-template-maintenance')) {
			woodmartThemeModule.$body.addClass('header-banner-display');
		}

		$('.header-banner').on('click', '.close-header-banner', function(e) {
			e.preventDefault();
			closeBanner();
		});

		var closeBanner = function() {
			woodmartThemeModule.$body.removeClass('header-banner-display').addClass('header-banner-hide');

			Cookies.set('woodmart_tb_banner_' + banner_version, 'closed', {
				expires: 60,
				path   : '/'
			});
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.headerBanner();
	});
})(jQuery);
