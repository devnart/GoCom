/* global woodmart_settings */
(function($) {
	woodmartThemeModule.onePageMenu = function() {
		var scrollToRow = function(hash) {
			var row = $('#' + hash + ', .wd-menu-anchor[data-id="' + hash + '"]');

			if (row.length < 1) {
				return;
			}

			var position = row.offset().top;

			$('html, body').animate({
				scrollTop: position - woodmart_settings.one_page_menu_offset
			}, 800);

			setTimeout(function() {
				activeMenuItem(hash);
			}, 800);
		};

		var activeMenuItem = function(hash) {
			var itemHash;

			$('.onepage-link').each(function() {
				var $this = $(this);
				itemHash = $this.find('> a').attr('href').split('#')[1];

				if (itemHash === hash) {
					$('.onepage-link').removeClass('current-menu-item');
					$this.addClass('current-menu-item');
				}
			});
		};

		woodmartThemeModule.$body.on('click', '.onepage-link > a', function(e) {
			var $this = $(this),
			    hash  = $this.attr('href').split('#')[1];

			if ($('#' + hash).length < 1 && $('.wd-menu-anchor[data-id="' + hash + '"]').length < 1) {
				return;
			}

			e.preventDefault();

			scrollToRow(hash);

			// close mobile menu
			$('.wd-close-side').trigger('click');
			$('.wd-fs-close').trigger('click');
		});

		if ($('.onepage-link').length > 0) {
			$('.entry-content > .vc_section, .entry-content > .vc_row').waypoint(function() {
				var $this = $($(this)[0].element);
				var hash = $this.attr('id');
				activeMenuItem(hash);
			}, {offset: 150});

			$('.wd-menu-anchor').waypoint(function() {
				activeMenuItem($($(this)[0].element).data('id'));
			}, {
				offset: function() {
					return $($(this)[0].element).data('offset');
				}
			});

			var locationHash = window.location.hash.split('#')[1];

			if (window.location.hash.length > 1) {
				setTimeout(function() {
					scrollToRow(locationHash);
				}, 500);
			}
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.onePageMenu();
	});
})(jQuery);
