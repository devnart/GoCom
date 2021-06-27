/* global woodmart_settings */
(function($) {
	woodmartThemeModule.menuDropdownsAJAX = function() {
		woodmartThemeModule.$body.on('mousemove', checkMenuProximity);

		function checkMenuProximity(event) {
			$('.menu').has('.dropdown-load-ajax').each(function() {
				var $menu = $(this);

				if ($menu.hasClass('dropdowns-loading') || $menu.hasClass('dropdowns-loaded')) {
					return;
				}

				if (!isNear($menu, 50, event)) {
					return;
				}

				loadDropdowns($menu);
			});
		}

		function loadDropdowns($menu) {
			$menu.addClass('dropdowns-loading');

			var storageKey = woodmart_settings.menu_storage_key + '_' + $menu.attr('id');
			var storedData = false;

			var $items = $menu.find('.dropdown-load-ajax'),
			    ids    = [];

			$items.each(function() {
				ids.push($(this).find('.dropdown-html-placeholder').data('id'));
			});

			if (woodmart_settings.ajax_dropdowns_save && woodmartThemeModule.supports_html5_storage) {
				var unparsedData = localStorage.getItem(storageKey);

				try {
					storedData = JSON.parse(unparsedData);
				}
				catch (e) {
					console.log('cant parse Json', e);
				}
			}
			if (storedData) {
				renderResults(storedData);
			} else {
				$.ajax({
					url     : woodmart_settings.ajaxurl,
					data    : {
						action: 'woodmart_load_html_dropdowns',
						ids   : ids
					},
					dataType: 'json',
					method  : 'POST',
					success : function(response) {
						if (response.status === 'success') {
							renderResults(response.data);
							if (woodmart_settings.ajax_dropdowns_save && woodmartThemeModule.supports_html5_storage) {
								localStorage.setItem(storageKey, JSON.stringify(response.data));
							}
						} else {
							console.log('loading html dropdowns returns wrong data - ', response.message);
						}
					},
					error   : function() {
						console.log('loading html dropdowns ajax error');
					}
				});
			}

			function renderResults(data) {
				Object.keys(data).forEach(function(id) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(data[id], function(html) {
						$menu.find('[data-id="' + id + '"]').replaceWith(html);

						$menu.addClass('dropdowns-loaded');
						setTimeout(function() {
							$menu.removeClass('dropdowns-loading');
						}, 1000);
					});
				});

				woodmartThemeModule.$document.trigger('wdLoadDropdownsSuccess');
			}
		}

		function isNear($element, distance, event) {
			var left   = $element.offset().left - distance,
			    top    = $element.offset().top - distance,
			    right  = left + $element.width() + (2 * distance),
			    bottom = top + $element.height() + (2 * distance),
			    x      = event.pageX,
			    y      = event.pageY;

			return (x > left && x < right && y > top && y < bottom);
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.menuDropdownsAJAX();
	});
})(jQuery);
