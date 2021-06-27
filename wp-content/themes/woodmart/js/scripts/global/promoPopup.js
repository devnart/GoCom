/* global woodmart_settings */
(function($) {
	woodmartThemeModule.promoPopup = function() {
		var promo_version = woodmart_settings.promo_version;

		if (woodmartThemeModule.$body.hasClass('page-template-maintenance') || woodmart_settings.enable_popup !== 'yes' || (woodmart_settings.promo_popup_hide_mobile === 'yes' && woodmartThemeModule.windowWidth < 768) || (Cookies.get('woodmart_age_verify') !== 'confirmed' && woodmart_settings.age_verify === 'yes')) {
			return;
		}

		var shown = false,
		    pages = Cookies.get('woodmart_shown_pages');

		var showPopup = function() {
			$.magnificPopup.open({
				items       : {
					src: '.wd-promo-popup'
				},
				type        : 'inline',
				removalDelay: 500, //delay removal by X to allow out-animation
				tClose      : woodmart_settings.close,
				tLoading    : woodmart_settings.loading,
				callbacks   : {
					beforeOpen: function() {
						this.st.mainClass = 'mfp-move-horizontal wd-promo-popup-wrapper';
					},
					close     : function() {
						Cookies.set('woodmart_popup_' + promo_version, 'shown', {
							expires: 7,
							path   : '/'
						});
					}
				}
			});

			woodmartThemeModule.$document.trigger('wood-images-loaded');
		};

		$('.woodmart-open-newsletter').on('click', function(e) {
			e.preventDefault();
			showPopup();
		});

		if (!pages) {
			pages = 0;
		}

		if (pages < woodmart_settings.popup_pages) {
			pages++;

			Cookies.set('woodmart_shown_pages', pages, {
				expires: 7,
				path   : '/'
			});

			return false;
		}

		if (Cookies.get('woodmart_popup_' + promo_version) !== 'shown') {
			if (woodmart_settings.popup_event === 'scroll') {
				woodmartThemeModule.$window.on('scroll', function() {
					if (shown) {
						return false;
					}

					if (woodmartThemeModule.$document.scrollTop() >= woodmart_settings.popup_scroll) {
						showPopup();
						shown = true;
					}
				});
			} else {
				setTimeout(function() {
					showPopup();
				}, woodmart_settings.popup_delay);
			}
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.promoPopup();
	});
})(jQuery);
