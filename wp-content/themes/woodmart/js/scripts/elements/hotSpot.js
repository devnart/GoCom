/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_image_hotspot.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.imageHotspot();
		});
	});

	woodmartThemeModule.imageHotspot = function() {
		$('.wd-image-hotspot').each(function() {
			var _this = $(this);
			var btn = _this.find('.hotspot-btn');
			var parentWrapper = _this.parents('.wd-image-hotspot-wrapper');

			if (!parentWrapper.hasClass('hotspot-action-click') && woodmartThemeModule.$window.width() > 1024) {
				return;
			}

			btn.on('click', function() {
				if (_this.hasClass('hotspot-opened')) {
					_this.removeClass('hotspot-opened');
				} else {
					_this.addClass('hotspot-opened');
					_this.siblings().removeClass('hotspot-opened');
				}

				woodmartThemeModule.$document.trigger('wood-images-loaded');
				return false;
			});

			woodmartThemeModule.$document.on('click', function(e) {
				var target = e.target;

				if (_this.hasClass('hotspot-opened') && !$(target).is('.wd-image-hotspot') && !$(target).parents().is('.wd-image-hotspot')) {
					_this.removeClass('hotspot-opened');
					return false;
				}
			});
		});

		//Image loaded
		$('.wd-image-hotspot-wrapper').each(function() {
			var _this = $(this);
			_this.imagesLoaded(function() {
				_this.addClass('loaded');
			});
		});

		$('.wd-image-hotspot .hotspot-content').each(function() {
			var content = $(this);
			var offsetLeft = content.offset().left;
			var offsetRight = woodmartThemeModule.$window.width() - (offsetLeft + content.outerWidth());

			if (woodmartThemeModule.$window.width() > 768) {
				if (offsetLeft <= 0) {
					content.addClass('hotspot-overflow-right');
				}
				if (offsetRight <= 0) {
					content.addClass('hotspot-overflow-left');
				}
			}

			if (woodmartThemeModule.$window.width() <= 768) {
				if (offsetLeft <= 0) {
					content.css('marginLeft', Math.abs(offsetLeft - 15) + 'px');
				}
				if (offsetRight <= 0) {
					content.css('marginLeft', offsetRight - 15 + 'px');
				}
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.imageHotspot();
	});
})(jQuery);
