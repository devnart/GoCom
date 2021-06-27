/* global woodmart_settings */
(function($) {
	woodmartThemeModule.ageVerify = function() {
		if (woodmart_settings.age_verify !== 'yes' || Cookies.get('woodmart_age_verify') === 'confirmed') {
			return;
		}

		$.magnificPopup.open({
			items          : {
				src: '.wd-age-verify'
			},
			type           : 'inline',
			closeOnBgClick : false,
			closeBtnInside : false,
			showCloseBtn   : false,
			enableEscapeKey: false,
			removalDelay   : 500,
			tClose         : woodmart_settings.close,
			tLoading       : woodmart_settings.loading,
			callbacks      : {
				beforeOpen: function() {
					this.st.mainClass = 'mfp-move-horizontal wd-promo-popup-wrapper';
				}
			}
		});

		$('.wd-age-verify-allowed').on('click', function() {
			Cookies.set('woodmart_age_verify', 'confirmed', {
				expires: parseInt(woodmart_settings.age_verify_expires),
				path   : '/'
			});

			$.magnificPopup.close();
		});

		$('.wd-age-verify-forbidden').on('click', function() {
			$('.wd-age-verify').addClass('wd-forbidden');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.ageVerify();
	});
})(jQuery);
