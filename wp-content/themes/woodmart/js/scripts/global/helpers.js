var woodmartThemeModule = {};
/* global woodmart_settings */

(function($) {
	woodmartThemeModule.supports_html5_storage = false;

	try {
		woodmartThemeModule.supports_html5_storage = ('sessionStorage' in window && window.sessionStorage !== null);
		window.sessionStorage.setItem('xts', 'test');
		window.sessionStorage.removeItem('xts');
	}
	catch (err) {
		woodmartThemeModule.supports_html5_storage = false;
	}

	woodmartThemeModule.$window = $(window);

	woodmartThemeModule.$document = $(document);

	woodmartThemeModule.$body = $('body');

	woodmartThemeModule.windowWidth = woodmartThemeModule.$window.width();

	woodmartThemeModule.removeURLParameter = function(url, parameter) {
		var urlParts = url.split('?');

		if (urlParts.length >= 2) {
			var prefix = encodeURIComponent(parameter) + '=';
			var pars = urlParts[1].split(/[&;]/g);

			for (var i = pars.length; i-- > 0;) {
				if (pars[i].lastIndexOf(prefix, 0) !== -1) {
					pars.splice(i, 1);
				}
			}

			return urlParts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
		}

		return url;
	};

	woodmartThemeModule.removeDuplicatedStylesFromHTML = function(html, callback) {
		var $data = $('<div class="temp-wrapper"></div>').append(html);
		var $links = $data.find('link');
		var counter = 0;
		var timeout = false;

		if (0 === $links.length || 'yes' === woodmart_settings.combined_css) {
			callback(html);
			return;
		}

		setTimeout(function(){
			if (counter <= $links.length && ! timeout) {
				callback($($data.html()));
				timeout = true;
			}
		}, 500);

		$links.each(function() {
			var $link = $(this);
			var id = $link.attr('id');
			var href = $link.attr('href');

			$link.remove();

			if ('undefined' === typeof woodmart_page_css[id]) {
				$('head').append($link.on('load', function() {
					counter++;

					woodmart_page_css[id] = href;

					if (counter >= $links.length && ! timeout) {
						callback($($data.html()));
						timeout = true;
					}
				}));
			} else {
				counter++;

				if (counter >= $links.length && ! timeout) {
					callback($($data.html()));
					timeout = true;
				}
			}
		});
	};

	woodmartThemeModule.debounce = function(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this;
			var args = arguments;
			var later = function() {
				timeout = null;

				if (!immediate) {
					func.apply(context, args);
				}
			};
			var callNow = immediate && !timeout;

			clearTimeout(timeout);
			timeout = setTimeout(later, wait);

			if (callNow) {
				func.apply(context, args);
			}
		};
	};

	woodmartThemeModule.wdElementorAddAction = function(name, callback) {
		woodmartThemeModule.$window.on('elementor/frontend/init', function() {
			if (!elementorFrontend.isEditMode()) {
				return;
			}

			elementorFrontend.hooks.addAction(name, callback);
		});
	};

	woodmartThemeModule.wdElementorAddAction('frontend/element_ready/global', function($wrapper) {
		if ($wrapper.attr('style') && $wrapper.attr('style').indexOf('transform:translate3d') === 0 && !$wrapper.hasClass('wd-parallax-on-scroll')) {
			$wrapper.attr('style', '');
		}
	});

	woodmartThemeModule.wdElementorAddAction('frontend/element_ready/column', function($wrapper) {
		if ($wrapper.attr('style') && $wrapper.attr('style').indexOf('transform:translate3d') === 0 && !$wrapper.hasClass('wd-parallax-on-scroll')) {
			$wrapper.attr('style', '');
		}

		setTimeout(function() {
			woodmartThemeModule.stickyColumn();
		}, 100);
	});

	woodmartThemeModule.ajaxLinks = '.wd-nav-product-cat a, .widget_product_categories a, .widget_layered_nav_filters a, .woocommerce-widget-layered-nav a, .filters-area:not(.custom-content) a, body.post-type-archive-product:not(.woocommerce-account) .woocommerce-pagination a, body.tax-product_cat:not(.woocommerce-account) .woocommerce-pagination a, .wd-shop-tools a, .woodmart-woocommerce-layered-nav a, .woodmart-price-filter a, .wd-clear-filters a, .woodmart-woocommerce-sort-by a, .woocommerce-widget-layered-nav-list a, .wd-widget-stock-status a';

	woodmartThemeModule.shopLoadMoreBtn = '.wd-products-load-more.load-on-scroll';

	woodmartThemeModule.mainCarouselArg = {
		rtl            : woodmartThemeModule.$body.hasClass('rtl'),
		items          : woodmart_settings.product_gallery.thumbs_slider.position === 'centered' ? 2 : 1,
		autoplay       : woodmart_settings.product_slider_autoplay,
		autoplayTimeout: 3000,
		loop           : woodmart_settings.product_slider_autoplay,
		center         : woodmart_settings.product_gallery.thumbs_slider.position === 'centered',
		startPosition  : woodmart_settings.product_gallery.thumbs_slider.position === 'centered' ? woodmart_settings.centered_gallery_start : 0,
		dots           : false,
		nav            : true,
		autoHeight     : woodmart_settings.product_slider_auto_height === 'yes',
		navText        : false,
		navClass : ['owl-prev wd-btn-arrow', 'owl-next wd-btn-arrow'],
	};

	woodmartThemeModule.$window.on('elementor/frontend/init', function() {
		if (!elementorFrontend.isEditMode()) {
			return;
		}

		if ('enabled' === woodmart_settings.elementor_no_gap) {
			elementorFrontend.hooks.addAction('frontend/element_ready/section', function($wrapper) {
				var cid = $wrapper.data('model-cid');

				if (typeof elementorFrontend.config.elements.data[cid] !== 'undefined') {
					var size = elementorFrontend.config.elements.data[cid].attributes.content_width.size;

					if (!size) {
						$wrapper.addClass('wd-negative-gap');
					}
				}
			});

			elementor.channels.editor.on('change:section', function(view) {
				var changed = view.elementSettingsModel.changed;

				if (typeof changed.content_width !== 'undefined') {
					var sectionId = view._parent.model.id;
					var $section = $('.elementor-element-' + sectionId);
					var size = changed.content_width.size;

					if (size) {
						$section.removeClass('wd-negative-gap');
					} else {
						$section.addClass('wd-negative-gap');
					}
				}
			});
		}
	});

	woodmartThemeModule.$window.on('load', function() {
		$('.wd-preloader').delay(parseInt(woodmart_settings.preloader_delay)).addClass('preloader-hide');
		$('.wd-preloader-style').remove();
		setTimeout(function() {
			$('.wd-preloader').remove();
		}, 200);
	});
})(jQuery);

window.onload = function() {
	var events = [
		'keydown',
		'scroll',
		'mouseover',
		'touchmove',
		'touchstart',
		'mousedown',
		'mousemove'
	];

	var triggerListener = function(e) {
		jQuery(window).trigger('wdEventStarted');
		removeListener();
	};

	var removeListener = function() {
		events.forEach(function(eventName) {
			window.removeEventListener(eventName, triggerListener);
		});
	};

	var addListener = function(eventName) {
		window.addEventListener(eventName, triggerListener);
	};

	events.forEach(function(eventName) {
		addListener(eventName);
	});
};