/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_google_map.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.googleMapInit();
		});
	});

	woodmartThemeModule.googleMapInit = function() {
		$('.google-map-container').each(function() {
			var $map = $(this);
			var data = $map.data('map-args');

			var config = {
				locations      : [
					{
						lat      : data.latitude,
						lon      : data.longitude,
						icon     : data.marker_icon,
						animation: google.maps.Animation.DROP
					}
				],
				controls_on_map: false,
				map_div        : '#' + data.selector,
				start          : 1,
				map_options    : {
					zoom       : parseInt(data.zoom),
					scrollwheel: 'yes' === data.mouse_zoom
				}
			};

			if (data.json_style && !data.elementor) {
				config.styles = {};
				config.styles[woodmart_settings.google_map_style_text] = $.parseJSON(data.json_style);
			} else if (data.json_style && data.elementor) {
				config.styles = {};
				config.styles[woodmart_settings.google_map_style_text] = $.parseJSON(atob(data.json_style));
			}

			if ('yes' === data.marker_text_needed) {
				config.locations[0].html = data.marker_text;
			}

			if ('button' === data.init_type) {
				$map.find('.wd-init-map').on('click', function(e) {
					e.preventDefault();

					if ($map.hasClass('wd-map-inited')) {
						return;
					}

					$map.addClass('wd-map-inited');
					new Maplace(config).Load();
				});
			} else if ('scroll' === data.init_type) {
				woodmartThemeModule.$window.on('scroll', function() {
					if (window.innerHeight + woodmartThemeModule.$window.scrollTop() + parseInt(data.init_offset) > $map.offset().top) {
						if ($map.hasClass('wd-map-inited')) {
							return;
						}

						$map.addClass('wd-map-inited');
						new Maplace(config).Load();
					}
				});
			} else if ('interaction' === data.init_type) {
				woodmartThemeModule.$window.on('wdEventStarted', function() {
					new Maplace(config).Load();
				});
			} else {
				new Maplace(config).Load();
			}
		});

		var $gmap = $('.google-map-container-with-content');

		woodmartThemeModule.$window.on('resize', function() {
			$gmap.css({
				'height': $gmap.find('.wd-google-map.with-content').outerHeight()
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.googleMapInit();
	});
})(jQuery);
