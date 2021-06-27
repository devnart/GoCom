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
/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_blog.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.blogLoadMore();
		});
	});

	woodmartThemeModule.blogLoadMore = function() {
		var btnClass = '.wd-blog-load-more.load-on-scroll',
		    process  = false;

		woodmartThemeModule.clickOnScrollButton(btnClass, false, false);

		$('.wd-blog-load-more').on('click', function(e) {
			e.preventDefault();

			var $this = $(this);

			if (process || $this.hasClass('no-more-posts')) {
				return;
			}

			process = true;

			var holder   = $this.parent().siblings('.wd-blog-holder'),
			    source   = holder.data('source'),
			    action   = 'woodmart_get_blog_' + source,
			    ajaxurl  = woodmart_settings.ajaxurl,
			    dataType = 'json',
			    method   = 'POST',
			    atts     = holder.data('atts'),
			    paged    = holder.data('paged');

			$this.addClass('loading');

			var data = {
				atts  : atts,
				paged : paged,
				action: action
			};

			if (source === 'main_loop') {
				ajaxurl = $this.attr('href');
				method = 'GET';
				data = {};
			}

			$.ajax({
				url     : ajaxurl,
				data    : data,
				dataType: dataType,
				method  : method,
				success : function(data) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(data.items, function(html) {
						var items = $(html);

						if (items) {
							if (holder.hasClass('masonry-container')) {
								holder.append(items).isotope('appended', items);
								holder.imagesLoaded().progress(function() {
									holder.isotope('layout');
									woodmartThemeModule.clickOnScrollButton(btnClass, true, false);
								});
							} else {
								holder.append(items);
								holder.imagesLoaded().progress(function() {
									woodmartThemeModule.clickOnScrollButton(btnClass, true, false);
								});
							}

							if ('yes' === woodmart_settings.load_more_button_page_url){
								window.history.pushState('', '', data.currentPage);
							}
							holder.data('paged', paged + 1);

							if (source === 'main_loop') {
								$this.attr('href', data.nextPage);
								if (data.status === 'no-more-posts') {
									$this.hide().remove();
								}
							}
						}

						if (data.status === 'no-more-posts') {
							$this.addClass('no-more-posts');
							$this.hide();
						}
					});
				},
				error   : function() {
					console.log('ajax error');
				},
				complete: function() {
					$this.removeClass('loading');
					process = false;
				}
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.blogLoadMore();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_banner_carousel.default',
		'frontend/element_ready/wd_banner.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.bannersHover();
		});
	});

	woodmartThemeModule.bannersHover = function() {
		if (typeof ($.fn.panr) === 'undefined') {
			return;
		}

		$('.promo-banner.banner-hover-parallax').panr({
			sensitivity         : 20,
			scale               : false,
			scaleOnHover        : true,
			scaleTo             : 1.15,
			scaleDuration       : .34,
			panY                : true,
			panX                : true,
			panDuration         : 0.5,
			resetPanOnMouseLeave: true
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.bannersHover();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.buttonSmoothScroll = function() {
		$('.wd-button-wrapper.wd-smooth-scroll a').on('click', function(e) {
			e.stopPropagation();
			e.preventDefault();

			var $button = $(this);
			var time = $button.parent().data('smooth-time');
			var offset = $button.parent().data('smooth-offset');
			var hash = $button.attr('href').split('#')[1];

			var $anchor = $('#' + hash);

			if ($anchor.length < 1) {
				return;
			}

			var position = $anchor.offset().top;

			$('html, body').animate({
				scrollTop: position - offset
			}, time);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.buttonSmoothScroll();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_popup.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.contentPopup();
		});
	});

	woodmartThemeModule.contentPopup = function() {
		if ('undefined' === typeof $.fn.magnificPopup) {
			return;
		}

		$('.wd-open-popup').magnificPopup({
			type        : 'inline',
			removalDelay: 500, //delay removal by X to allow out-animation
			tClose      : woodmart_settings.close,
			tLoading    : woodmart_settings.loading,
			callbacks   : {
				beforeOpen: function() {
					this.st.mainClass = 'mfp-move-horizontal content-popup-wrapper';
				},
				open      : function() {
					woodmartThemeModule.$document.trigger('wood-images-loaded');
				}
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.contentPopup();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdProductsTabsLoaded wdUpdateWishlist wdShopPageInit', function () {
		woodmartThemeModule.countDownTimer();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default',
		'frontend/element_ready/wd_countdown_timer.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.countDownTimer();
		});
	});

	woodmartThemeModule.countDownTimer = function() {
		$('.wd-timer').each(function() {
			var $this = $(this);
			dayjs.extend(window.dayjs_plugin_utc);
			dayjs.extend(window.dayjs_plugin_timezone);
			var time = dayjs.tz($this.data('end-date'), $this.data('timezone'));
			$this.countdown(time.toDate(), function(event) {
				$this.html(event.strftime(''
					+ '<span class="countdown-days">%-D <span>' + woodmart_settings.countdown_days + '</span></span> '
					+ '<span class="countdown-hours">%H <span>' + woodmart_settings.countdown_hours + '</span></span> '
					+ '<span class="countdown-min">%M <span>' + woodmart_settings.countdown_mins + '</span></span> '
					+ '<span class="countdown-sec">%S <span>' + woodmart_settings.countdown_sec + '</span></span>'));
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.countDownTimer();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_counter.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.visibleElements();
		});
	});

	woodmartThemeModule.counterShortcode = function(counter) {
		if (counter.attr('data-state') === 'done' || parseInt(counter.text()) !== counter.data('final')) {
			return;
		}

		counter.prop('Counter', 0).animate({
			Counter: counter.text()
		}, {
			duration: 3000,
			easing  : 'swing',
			step    : function(now) {
				if (now >= counter.data('final')) {
					counter.attr('data-state', 'done');
				}

				counter.text(Math.ceil(now));
			}
		});
	};

	woodmartThemeModule.visibleElements = function() {
		$('.woodmart-counter .counter-value').each(function() {
			var $this = $(this);

			$this.waypoint(function() {
				woodmartThemeModule.counterShortcode($this);
			}, {offset: '100%'});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.visibleElements();
	});
})(jQuery);

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

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_images_gallery.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.imagesGalleryMasonry();
			woodmartThemeModule.imagesGalleryJustified();
		});
	});

	woodmartThemeModule.imagesGalleryMasonry = function() {
		if (typeof ($.fn.isotope) == 'undefined' || typeof ($.fn.imagesLoaded) == 'undefined') {
			return;
		}

		var $container = $('.view-masonry .gallery-images');

		$container.imagesLoaded(function() {
			$container.isotope({
				gutter      : 0,
				isOriginLeft: !woodmartThemeModule.$body.hasClass('rtl'),
				itemSelector: '.wd-gallery-item'
			});
		});
	};

	woodmartThemeModule.imagesGalleryJustified = function() {
		$('.view-justified').each(function() {
			$(this).find('.gallery-images').justifiedGallery({
				margins     : 1,
				cssAnimation: true
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.imagesGalleryMasonry();
		woodmartThemeModule.imagesGalleryJustified();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_infobox_carousel.default',
		'frontend/element_ready/wd_infobox.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.infoboxSvg();
		});
	});

	woodmartThemeModule.infoboxSvg = function() {
		$('.wd-info-box.with-animation').each(function() {
			var $this = $(this);

			if ($this.find('.info-svg-wrapper > svg').length > 0) {
				new Vivus($this.find('.info-svg-wrapper > svg')[0], {
					type              : 'delayed',
					duration          : 200,
					start             : 'inViewport',
					animTimingFunction: Vivus.EASE_OUT
				}, function() {});
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.infoboxSvg();
	});
})(jQuery);

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

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.woodSliderLazyLoad = function() {
		woodmartThemeModule.$window.on('wdEventStarted', function() {
			var $slider = $('.wd-slider');
			$slider.on('changed.owl.carousel', function(event) {
				var $this = $(this);
				var active = $this.find('.owl-item').eq(event.item.index + 1);
				var id = active.find('.wd-slide').attr('id');
				var $els = $this.find('[id="' + id + '"]');

				active.find('.wd-slide').addClass('woodmart-loaded');
				$this.find('.owl-item').eq(event.item.index).find('.wd-slide').addClass('woodmart-loaded');

				$els.each(function() {
					$(this).addClass('woodmart-loaded');
				});
			});

			$slider.trigger('refresh.owl.carousel');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.woodSliderLazyLoad();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.videoPoster = function() {
		$('.wd-video-poster-wrapper').on('click', function() {
			var videoWrapper = $(this),
			    video        = videoWrapper.parent().find('iframe'),
			    videoScr     = video.attr('src'),
			    videoNewSrc  = videoScr + '&autoplay=1';

			if (videoScr.indexOf('vimeo.com') + 1) {
				videoNewSrc = videoScr + '?autoplay=1';
			}

			video.attr('src', videoNewSrc);
			videoWrapper.addClass('hidden-poster');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.videoPoster();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_3d_view.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.view3d();
		});
	});

	woodmartThemeModule.view3d = function() {
		$('.wd-threed-view:not(.wd-product-threed)').each(function() {
			init($(this));
		});

		$('.product-360-button a').on('click', function(e) {
			e.preventDefault();
			init($('.wd-threed-view.wd-product-threed'));
		});

		function init($this) {
			var data = $this.data('args');

			if (!data || $this.hasClass('wd-threed-view-inited')) {
				return false;
			}

			$this.ThreeSixty({
				totalFrames : data.frames_count,
				endFrame    : data.frames_count,
				currentFrame: 1,
				imgList     : '.threed-view-images',
				progress    : '.spinner',
				imgArray    : data.images,
				height      : data.height,
				width       : data.width,
				responsive  : true,
				navigation  : true,
				framerate   : woodmart_settings.three_sixty_framerate,
			});

			$this.addClass('wd-threed-view-inited');
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.view3d();
	});
})(jQuery);

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

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.ajaxSearch();
	});

	$.each([
		'frontend/element_ready/wd_search.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.ajaxSearch();
		});
	});

	woodmartThemeModule.ajaxSearch = function() {
		if (typeof ($.fn.devbridgeAutocomplete) == 'undefined') {
			return;
		}

		var escapeRegExChars = function(value) {
			return value.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, '\\$&');
		};

		$('form.woodmart-ajax-search').each(function() {
			var $this         = $(this),
			    number        = parseInt($this.data('count')),
			    thumbnail     = parseInt($this.data('thumbnail')),
			    symbols_count = parseInt($this.data('symbols_count')),
			    productCat    = $this.find('[name="product_cat"]'),
			    $results      = $this.parent().find('.wd-dropdown-results > .wd-scroll-content'),
			    postType      = $this.data('post_type'),
			    url           = woodmart_settings.ajaxurl + '?action=woodmart_ajax_search',
			    price         = parseInt($this.data('price')),
			    sku           = $this.data('sku');

			if (number > 0) {
				url += '&number=' + number;
			}
			url += '&post_type=' + postType;

			$results.on('click', '.view-all-results', function() {
				$this.submit();
			});

			if (productCat.length && productCat.val() !== '') {
				url += '&product_cat=' + productCat.val();
			}

			$this.find('[type="text"]').on('focus', function() {
				var $input = $(this);

				if ($input.hasClass('wd-search-inited')) {
					return;
				}

				$input.devbridgeAutocomplete({
					serviceUrl      : url,
					appendTo        : $results,
					minChars        : symbols_count,
					onSelect        : function(suggestion) {
						if (suggestion.permalink.length > 0) {
							window.location.href = suggestion.permalink;
						}
					},
					onHide          : function() {
						$results.parent().removeClass('wd-opened');
					},
					onSearchStart   : function() {
						$this.addClass('search-loading');
					},
					beforeRender    : function(container) {
						$(container).find('.suggestion-divider-title').parent().addClass('suggestion-divider');
						if (container[0].childElementCount > 2) {
							$(container).append('<div class="view-all-results"><span>' + woodmart_settings.all_results + '</span></div>');
						}

						$(container).removeAttr('style');
					},
					onSearchComplete: function() {
						$this.removeClass('search-loading');

						woodmartThemeModule.$document.trigger('wood-images-loaded');

					},
					formatResult    : function(suggestion, currentValue) {
						if (currentValue === '&') {
							currentValue = '&#038;';
						}
						var pattern     = '(' + escapeRegExChars(currentValue) + ')',
						    returnValue = '';

						if (suggestion.divider) {
							returnValue += ' <h5 class="suggestion-divider-title">' + suggestion.divider + '</h5>';
						}

						if (thumbnail && suggestion.thumbnail) {
							returnValue += ' <div class="suggestion-thumb">' + suggestion.thumbnail + '</div>';
						}

						if (suggestion.value) {
							returnValue += ' <div class="suggestion-content set-cont-mb-s reset-last-child">';
							returnValue += '<h4 class="wd-entities-title">' + suggestion.value
								.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>')
								.replace(/&lt;(\/?strong)&gt;/g, '<$1>') + '</h4>';
						}

						if (sku && suggestion.sku) {
							returnValue += ' <p class="suggestion-sku">' + suggestion.sku + '</p>';
						}

						if (price && suggestion.price) {
							returnValue += ' <p class="price">' + suggestion.price + '</p>';
						}

						if (suggestion.value) {
							returnValue += ' </div>';
						}

						if (suggestion.no_found) {
							returnValue = '<span class="no-found-msg">' + suggestion.value + '</span>';
						}

						$results.parent().addClass('wd-opened');

						return returnValue;
					}
				});

				if (productCat.length) {
					var searchForm = $this.find('[type="text"]').devbridgeAutocomplete(),
					    serviceUrl = woodmart_settings.ajaxurl + '?action=woodmart_ajax_search';

					if (number > 0) {
						serviceUrl += '&number=' + number;
					}

					serviceUrl += '&post_type=' + postType;

					productCat.on('cat_selected', function() {
						if (productCat.val() !== '') {
							searchForm.setOptions({
								serviceUrl: serviceUrl + '&product_cat=' + productCat.val()
							});
						} else {
							searchForm.setOptions({
								serviceUrl: serviceUrl
							});
						}

						searchForm.hide();
						searchForm.onValueChange();
					});
				}

				$input.addClass('wd-search-inited');
			});


			woodmartThemeModule.$document.on('click', function(e) {
				var target = e.target;

				if (!$(target).is('.wd-search-form') && !$(target).parents().is('.wd-search-form')) {
					$this.find('[type="text"]').devbridgeAutocomplete('hide');
				}
			});

			$('.wd-dropdown-results > .wd-scroll-content').on('click', function(e) {
				e.stopPropagation();
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.ajaxSearch();
	});
})(jQuery);
/* global woodmart_settings */
(function($) {
	woodmartThemeModule.animationsOffset = function() {
		if (typeof ($.fn.waypoint) == 'undefined') {
			return;
		}

		$('.wpb_animate_when_almost_visible:not(.wpb_start_animation)').waypoint(function() {
			var $this = $($(this)[0].element);
			$this.addClass('wpb_start_animation animated');
		}, {
			offset: '100%'
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.animationsOffset();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdBackHistory', function () {
		woodmartThemeModule.backHistory();
	});

	woodmartThemeModule.backHistory = function() {
		$('.wd-back-btn > a').on('click', function(e) {
			e.preventDefault();

			history.go(-1);

			setTimeout(function() {
				$('.filters-area').removeClass('filters-opened').stop().hide();
				if (woodmartThemeModule.$window.width() <= 1024) {
					$('.wd-nav-product-cat').removeClass('categories-opened').stop().hide();
					$('.wd-btn-show-cat').removeClass('button-open');
				}

				woodmartThemeModule.$document.trigger('wdBackHistory');
			}, 20);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.backHistory();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdBackHistory wdProductsTabsLoaded wdActionAfterAddToCart wdShopPageInit wdArrowsLoadProducts wdLoadMoreLoadProducts wdUpdateWishlist wdQuickViewOpen wdQuickShopSuccess wdProductBaseHoverIconsResize', function () {
		woodmartThemeModule.btnsToolTips();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.btnsToolTips();
		});
	});

	woodmartThemeModule.btnsToolTips = function() {
		$('.woodmart-css-tooltip, .wd-buttons[class*="wd-pos-r"] div > a').on('mouseenter touchstart', function () {
			var $this = $(this);

			if (!$this.hasClass('wd-add-img-msg') && $(window).width() <= 1024 || $this.hasClass('wd-tooltip-inited')) {
				return;
			}

			$this.find('.wd-tooltip-label').remove();
			$this.addClass('wd-tltp').prepend('<span class="wd-tooltip-label">' + $this.text() + '</span>');

			var $label = $this.find('.wd-tooltip-label');

			if ($this.hasClass('wd-tltp-top')) {
				var width = $label.outerWidth();
				$label.css({
					marginLeft: - parseInt(width / 2)
				})
			}

			$this.addClass('wd-tooltip-inited');
		});

		// Bootstrap tooltips
		if (woodmartThemeModule.windowWidth <= 1024) {
			return;
		}

		$('.wd-tooltip, .wd-hover-icons .wd-buttons .wd-action-btn:not(.wd-add-btn) > a, .wd-hover-icons .wd-buttons .wd-add-btn, body:not(.catalog-mode-on):not(.login-see-prices) .wd-hover-base .wd-bottom-actions .wd-action-btn.wd-style-icon:not(.wd-add-btn) > a, body:not(.catalog-mode-on):not(.login-see-prices) .wd-hover-base .wd-bottom-actions .wd-action-btn.wd-style-icon.wd-add-btn, .wd-hover-base .wd-compare-btn > a, .wd-products-nav .wd-back-btn').on('mouseenter touchstart', function() {
			var $this = $(this);

			if ($this.hasClass('wd-tooltip-inited')) {
				return;
			}

			$this.tooltip({
				animation: false,
				container: 'body',
				trigger  : 'hover',
				boundary: 'window',
				title    : function() {
					var $this = $(this);

					if ($this.find('.added_to_cart').length > 0) {
						return $this.find('.add_to_cart_button').text();
					}

					return $this.text();
				}
			});

			$this.tooltip('show');

			$this.addClass('wd-tooltip-inited');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.btnsToolTips();
	});
})(jQuery);


/* global woodmart_settings */
(function($) {
	woodmartThemeModule.callPhotoSwipe = function(index, items) {
		if (woodmartThemeModule.$body.hasClass('rtl')) {
			index = items.length - index - 1;
			items = items.reverse();
		}

		var options = {
			index        : index,
			shareButtons : [
				{
					id   : 'facebook',
					label: woodmart_settings.share_fb,
					url  : 'https://www.facebook.com/sharer/sharer.php?u={{url}}'
				},
				{
					id   : 'twitter',
					label: woodmart_settings.tweet,
					url  : 'https://twitter.com/intent/tweet?text={{text}}&url={{url}}'
				},
				{
					id   : 'pinterest',
					label: woodmart_settings.pin_it,
					url  : 'http://www.pinterest.com/pin/create/button/' +
						'?url={{url}}&media={{image_url}}&description={{text}}'
				},
				{
					id      : 'download',
					label   : woodmart_settings.download_image,
					url     : '{{raw_image_url}}',
					download: true
				}
			],
			closeOnScroll: woodmart_settings.photoswipe_close_on_scroll
		};

		woodmartThemeModule.$body.find('.pswp').remove();
		woodmartThemeModule.$body.append(woodmart_settings.photoswipe_template);
		var pswpElement = document.querySelectorAll('.pswp')[0];

		// Initializes and opens PhotoSwipe
		var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
		gallery.init();
	};
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.clickOnScrollButton = function(btnClass, destroy, offset) {
		if (typeof $.fn.waypoint != 'function') {
			return;
		}

		var $btn = $(btnClass);
		if ($btn.length === 0) {
			return;
		}

		$btn.trigger('wd-waypoint-destroy');

		if (!offset) {
			offset = 0;
		}

		var waypoint = new Waypoint({
			element: $btn[0],
			handler: function() {
				$btn.trigger('click');
			},
			offset : function() {
				return woodmartThemeModule.$window.outerHeight() + parseInt(offset);
			}
		});

		$btn.data('waypoint-inited', true).off('wd-waypoint-destroy').on('wd-waypoint-destroy', function() {
			if ($btn.data('waypoint-inited')) {
				waypoint.destroy();
				$btn.data('waypoint-inited', false);
			}
		});
	};
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.cookiesPopup = function() {
		var cookies_version = woodmart_settings.cookies_version;

		if (Cookies.get('woodmart_cookies_' + cookies_version) === 'accepted') {
			return;
		}

		var popup = $('.wd-cookies-popup');

		setTimeout(function() {
			popup.addClass('popup-display');
			popup.on('click', '.cookies-accept-btn', function(e) {
				e.preventDefault();
				acceptCookies();
			});
		}, 2500);

		var acceptCookies = function() {
			popup.removeClass('popup-display').addClass('popup-hide');
			Cookies.set('woodmart_cookies_' + cookies_version, 'accepted', {
				expires: 60,
				path   : '/'
			});
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.cookiesPopup();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.footerWidgetsAccordion = function() {
		if (woodmartThemeModule.windowWidth >= 576) {
			return;
		}

		$('.footer-widget-collapse .widget-title').on('click', function() {
			var $title = $(this);
			var $widget = $title.parent();
			var $content = $widget.find('> *:not(.widget-title)');

			if ($widget.hasClass('footer-widget-opened')) {
				$widget.removeClass('footer-widget-opened');
				$content.stop().slideUp(200);
			} else {
				$widget.addClass('footer-widget-opened');
				$content.stop().slideDown(200);
				woodmartThemeModule.$document.trigger('wood-images-loaded');
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.footerWidgetsAccordion();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPjaxStart', function () {
		woodmartThemeModule.hideShopSidebar();
	});

	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.hiddenSidebar();
	});

	woodmartThemeModule.hiddenSidebar = function() {
		var position = woodmartThemeModule.$body.hasClass('rtl') ? 'right' : 'left';

		if (woodmartThemeModule.$body.hasClass('offcanvas-sidebar-desktop') && woodmartThemeModule.windowWidth > 1024 || woodmartThemeModule.$body.hasClass('offcanvas-sidebar-tablet') && woodmartThemeModule.windowWidth <= 1024) {
			$('.area-sidebar-shop').addClass('wd-side-hidden wd-' + position + ' wd-inited wd-scroll');
			$('.area-sidebar-shop .widget-area').addClass('wd-scroll-content');
		}

		if (woodmartThemeModule.$body.hasClass('offcanvas-sidebar-mobile') && woodmartThemeModule.windowWidth <= 768) {
			$('.sidebar-container').addClass('wd-side-hidden wd-' + position + ' wd-inited wd-scroll');
			$('.sidebar-container .widget-area').addClass('wd-scroll-content');
		}

		woodmartThemeModule.$body.off('click', '.wd-show-sidebar-btn, .wd-sidebar-opener').on('click', '.wd-show-sidebar-btn, .wd-sidebar-opener', function(e) {
			e.preventDefault();

			if ($('.sidebar-container').hasClass('wd-opened')) {
				woodmartThemeModule.hideShopSidebar();
			} else {
				showSidebar();
			}
		});

		woodmartThemeModule.$body.on('click touchstart', '.wd-close-side, .close-side-widget', function() {
			woodmartThemeModule.hideShopSidebar();
		});

		var showSidebar = function() {
			$('.sidebar-container').addClass('wd-opened');
			$('.wd-close-side').addClass('wd-close-side-opened');
		};

		woodmartThemeModule.$document.trigger('wdHiddenSidebarsInited');
	};

	woodmartThemeModule.hideShopSidebar = function() {
		$('.sidebar-container').removeClass('wd-opened');
		$('.wd-close-side').removeClass('wd-close-side-opened');
	};

	$(document).ready(function() {
		woodmartThemeModule.hiddenSidebar();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdHiddenSidebarsInited', function () {
		woodmartThemeModule.lazyLoading();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default',
		'frontend/element_ready/wd_product_categories.default',
		'frontend/element_ready/wd_products_brands.default',
		'frontend/element_ready/wd_blog.default',
		'frontend/element_ready/wd_images_gallery.default',
		'frontend/element_ready/wd_product_categories.default',
		'frontend/element_ready/wd_slider.default',
		'frontend/element_ready/wd_banner_carousel.default',
		'frontend/element_ready/wd_banner.default',
		'frontend/element_ready/wd_infobox_carousel.default',
		'frontend/element_ready/wd_infobox.default',
		'frontend/element_ready/wd_instagram.default',
		'frontend/element_ready/wd_testimonials.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.lazyLoading();
		});
	});

	woodmartThemeModule.lazyLoading = function() {
		if (!window.addEventListener || !window.requestAnimationFrame || !document.getElementsByClassName) {
			return;
		}

		var pItem = document.getElementsByClassName('wd-lazy-load'), pCount, timer;

		woodmartThemeModule.$document.on('wood-images-loaded added_to_cart', function() {
			inView();
		});

		$('.wd-scroll-content').on('scroll', function() {
			woodmartThemeModule.$document.trigger('wood-images-loaded');
		});

		// WooCommerce tabs fix
		$('.wc-tabs > li').on('click', function() {
			woodmartThemeModule.$document.trigger('wood-images-loaded');
		});

		// scroll and resize events
		window.addEventListener('scroll', scroller, false);
		window.addEventListener('resize', scroller, false);

		// DOM mutation observer
		if (MutationObserver) {
			var observer = new MutationObserver(function() {
				if (pItem.length !== pCount) {
					inView();
				}
			});

			observer.observe(document.body, {
				subtree      : true,
				childList    : true,
				attributes   : true,
				characterData: true
			});
		}

		// initial check
		inView();

		// throttled scroll/resize
		function scroller() {
			timer = timer || setTimeout(function() {
				timer = null;
				inView();
			}, 100);
		}

		// image in view?
		function inView() {
			if (pItem.length) {
				requestAnimationFrame(function() {
					var offset = parseInt(woodmart_settings.lazy_loading_offset);
					var wT = window.pageYOffset, wB = wT + window.innerHeight + offset, cRect, pT, pB, p = 0;

					while (p < pItem.length) {
						cRect = pItem[p].getBoundingClientRect();
						pT = wT + cRect.top;
						pB = pT + cRect.height;

						if (wT < pB && wB > pT && !pItem[p].loaded) {
							loadFullImage(pItem[p], p);
						} else {
							p++;
						}
					}

					pCount = pItem.length;
				});
			}
		}

		// replace with full image
		function loadFullImage(item) {
			item.onload = addedImg;

			if (item.querySelector('img') !== null) {
				item.querySelector('img').onload = addedImg;
				item.querySelector('img').src = item.dataset.woodSrc;
				item.querySelector('source').srcset = item.dataset.woodSrc;

				if (typeof (item.dataset.srcset) != 'undefined') {
					item.querySelector('img').srcset = item.dataset.srcset;
				}
			}

			item.src = item.dataset.woodSrc;
			if (typeof (item.dataset.srcset) != 'undefined') {
				item.srcset = item.dataset.srcset;
			}

			item.loaded = true;

			// replace image
			function addedImg() {
				requestAnimationFrame(function() {
					item.classList.add('wd-loaded');

					var $masonry = jQuery(item).parents('.view-masonry .gallery-images, .grid-masonry, .masonry-container');
					if ($masonry.length > 0) {
						$masonry.isotope('layout');
					}
					var $categories = jQuery(item).parents('.categories-masonry');
					if ($categories.length > 0) {
						$categories.packery();
					}
				});
			}
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.lazyLoading();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_blog.default',
		'frontend/element_ready/wd_portfolio.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.masonryLayout();
		});
	});

	woodmartThemeModule.masonryLayout = function() {
		if (typeof ($.fn.isotope) === 'undefined' || typeof ($.fn.imagesLoaded) === 'undefined') {
			return;
		}

		var $container = $('.masonry-container');

		$container.imagesLoaded(function() {
			$container.isotope({
				gutter      : 0,
				isOriginLeft: !woodmartThemeModule.$body.hasClass('rtl'),
				itemSelector: '.blog-design-masonry, .blog-design-mask, .masonry-item'
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.masonryLayout();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioLoadMoreLoaded', function () {
		woodmartThemeModule.mfpPopup();
	});

	woodmartThemeModule.mfpPopup = function() {
		if ('undefined' === typeof $.fn.magnificPopup) {
			return;
		}

		$('.gallery').magnificPopup({
			delegate    : ' > a',
			type        : 'image',
			removalDelay: 500,
			tClose      : woodmart_settings.close,
			tLoading    : woodmart_settings.loading,
			callbacks   : {
				beforeOpen: function() {
					this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
					this.st.mainClass = 'mfp-move-horizontal';
				}
			},
			image       : {
				verticalFit: true
			},
			gallery     : {
				enabled           : true,
				navigateByImgClick: true
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.mfpPopup();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdInstagramAjaxSuccess wdLoadDropdownsSuccess', function() {
		woodmartThemeModule.owlCarouselInit();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default',
		'frontend/element_ready/wd_product_categories.default',
		'frontend/element_ready/wd_products_brands.default',
		'frontend/element_ready/wd_blog.default',
		'frontend/element_ready/wd_images_gallery.default',
		'frontend/element_ready/wd_product_categories.default',
		'frontend/element_ready/wd_slider.default',
		'frontend/element_ready/wd_banner_carousel.default',
		'frontend/element_ready/wd_infobox_carousel.default',
		'frontend/element_ready/wd_instagram.default',
		'frontend/element_ready/wd_testimonials.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.owlCarouselInit();
		});
	});

	woodmartThemeModule.owlCarouselInit = function() {
		$('div[data-owl-carousel]:not(.scroll-init):not(.wd-slider-wrapper)').each(function() {
			owlInit($(this));
		});

		$('div[data-owl-carousel].wd-slider-wrapper:not(.scroll-init)').each(function() {
			owlInit($(this));
		});

		if (typeof ($.fn.waypoint) != 'undefined') {
			$('div[data-owl-carousel].scroll-init').waypoint(function() {
				var $this = $($(this)[0].element);
				owlInit($this);
			}, {
				offset: '100%'
			});
		}

		function owlInit($this) {
			var $owl = $this.find('.owl-carousel');

			if (woodmartThemeModule.windowWidth <= 1024 && $this.hasClass('disable-owl-mobile') || $owl.hasClass('owl-loaded')) {
				return;
			}

			var options = {
				rtl               : woodmartThemeModule.$body.hasClass('rtl'),
				items             : $this.data('desktop') ? $this.data('desktop') : 1,
				responsive        : {
					1025: {
						items: $this.data('desktop') ? $this.data('desktop') : 1
					},
					769 : {
						items: $this.data('tablet_landscape') ? $this.data('tablet_landscape') : 1
					},
					577 : {
						items: $this.data('tablet') ? $this.data('tablet') : 1
					},
					0   : {
						items: $this.data('mobile') ? $this.data('mobile') : 1
					}
				},
				autoplay          : $this.data('autoplay') === 'yes',
				autoplayHoverPause: $this.data('autoplay') === 'yes',
				autoplayTimeout   : $this.data('speed') ? $this.data('speed') : 5000,
				dots              : $this.data('hide_pagination_control') !== 'yes',
				nav               : $this.data('hide_prev_next_buttons') !== 'yes',
				autoHeight        : $this.data('autoheight') === 'yes',
				slideBy           : typeof $this.data('scroll_per_page') !== 'undefined' ? 1 : 'page',
				navText           : false,
				navClass          : ['owl-prev wd-btn-arrow', 'owl-next wd-btn-arrow'],
				center            : $this.data('center_mode') === 'yes',
				loop              : $this.data('wrap') === 'yes',
				dragEndSpeed      : $this.data('dragendspeed') ? $this.data('dragendspeed') : 200
			};

			if ($this.data('sliding_speed')) {
				options.smartSpeed = $this.data('sliding_speed');
				options.dragEndSpeed = $this.data('sliding_speed');
			}

			if ($this.data('animation')) {
				options.animateOut = $this.data('animation');
				options.mouseDrag = false;
			}

			function determinePseudoActive() {
				var id = $owl.find('.owl-item.active').find('.wd-slide').attr('id');
				var $els = $owl.find('[id="' + id + '"]');

				$owl.find('.owl-item.pseudo-active').removeClass('pseudo-active');
				$els.each(function() {
					var $this = $(this);

					if (!$this.parent().hasClass('active')) {
						$this.parent().addClass('pseudo-active');
					}
				});
			}

			if ($this.data('content_animation')) {
				determinePseudoActive();

				options.onTranslated = function() {
					determinePseudoActive();
				};
			}

			woodmartThemeModule.$window.on('vc_js', function() {
				$owl.trigger('refresh.owl.carousel');
			});

			$owl.find('link').appendTo('head');
			$owl.owlCarousel(options);

			if ($this.data('autoheight') === 'yes') {
				$owl.imagesLoaded(function() {
					$owl.trigger('refresh.owl.carousel');
				});
			}
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.owlCarouselInit();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.parallax = function() {
		if (woodmartThemeModule.windowWidth <= 1024) {
			return;
		}

		$('.wd-parallax').each(function() {
			var $this = $(this);

			if ($this.hasClass('wpb_column')) {
				$this.find('> .vc_column-inner').parallax('50%', 0.3);
			} else {
				$this.parallax('50%', 0.3);
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.parallax();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.photoswipeImages = function() {
		$('.photoswipe-images').each(function() {
			var $this = $(this);

			$this.on('click', 'a', function(e) {
				e.preventDefault();
				var index = $(e.currentTarget).data('index') - 1;
				var items = getGalleryItems($this, []);

				woodmartThemeModule.callPhotoSwipe(index, items);
			});
		});

		var getGalleryItems = function($gallery, items) {
			var src, width, height, title;

			$gallery.find('a').each(function() {
				var $this = $(this);

				src = $this.attr('href');
				width = $this.data('width');
				height = $this.data('height');
				title = $this.attr('title');

				if (!isItemInArray(items, src)) {
					items.push({
						src  : src,
						w    : width,
						h    : height,
						title: title
					});
				}
			});

			return items;
		};

		var isItemInArray = function(items, src) {
			var i;
			for (i = 0; i < items.length; i++) {
				if (items[i].src === src) {
					return true;
				}
			}

			return false;
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.photoswipeImages();
	});
})(jQuery);

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

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.scrollTop = function() {
		var $scrollTop = $('.scrollToTop');

		woodmartThemeModule.$window.on('scroll', function() {
			if ($(this).scrollTop() > 100) {
				$scrollTop.addClass('button-show');
			} else {
				$scrollTop.removeClass('button-show');
			}
		});

		$scrollTop.on('click', function() {
			$('html, body').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.scrollTop();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.searchFullScreen = function() {
		var searchWrapper = $('.wd-search-full-screen');

		woodmartThemeModule.$body.on('click', '.wd-header-search:not(.wd-header-search-mobile) > a', function(e) {

			e.preventDefault();

			if ($(this).parent().find('.wd-search-dropdown').length > 0) {
				return;
			}

			if (woodmartThemeModule.$body.hasClass('global-search-dropdown') || woodmartThemeModule.$window.width() < 1024) {
				return;
			}

			if (isOpened()) {
				closeWidget();
			} else {
				setTimeout(function() {
					openWidget();
				}, 10);
			}
		});

		woodmartThemeModule.$body.on('click', '.wd-close-search a, .main-page-wrapper, .header-banner', function(event) {

			if (!$(event.target).is('.wd-close-search a') && $(event.target).closest('.wd-search-full-screen').length) {
				return;
			}

			if (isOpened()) {
				closeWidget();
			}
		});

		var closeByEsc = function(e) {
			if (e.keyCode === 27) {
				closeWidget();
				woodmartThemeModule.$body.unbind('keyup', closeByEsc);
			}
		};

		var closeWidget = function() {
			woodmartThemeModule.$body.removeClass('wd-search-opened');
			searchWrapper.removeClass('wd-opened');
		};

		var openWidget = function() {
			var $bar = $('#wpadminbar');
			var barHeight = $bar.length > 0 ? $bar.outerHeight() : 0;
			var $sticked = $('.whb-sticked');
			var $mainHeader = $('.whb-main-header');
			var offset;

			if ($sticked.length > 0) {
				if ($('.whb-clone').length > 0) {
					offset = $sticked.outerHeight() + barHeight;
				} else {
					offset = $mainHeader.outerHeight() + barHeight;
				}
			} else {
				offset = $mainHeader.outerHeight() + barHeight;
				if (woodmartThemeModule.$body.hasClass('header-banner-display')) {
					offset += $('.header-banner').outerHeight();
				}
			}

			searchWrapper.css('top', offset);

			// Close by esc
			woodmartThemeModule.$body.on('keyup', closeByEsc);
			woodmartThemeModule.$body.addClass('wd-search-opened');

			searchWrapper.addClass('wd-opened');

			setTimeout(function() {
				searchWrapper.find('input[type="text"]').focus();

				woodmartThemeModule.$window.one('scroll', function() {
					if (isOpened()) {
						closeWidget();
					}
				});
			}, 300);
		};

		var isOpened = function() {
			return woodmartThemeModule.$body.hasClass('wd-search-opened');
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.searchFullScreen();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.stickyColumn = function() {
		$('.woodmart-sticky-column').each(function() {
			var $column = $(this),
			    offset  = 0;

			if (woodmartThemeModule.$body.hasClass('enable-sticky-header') || $('.whb-sticky-row').length > 0 || $('.whb-sticky-header').length > 0) {
				offset = 150;
			}

			$column.find(' > .vc_column-inner > .wpb_wrapper').stick_in_parent({
				offset_top: offset
			});
		});

		$('.wd-elementor-sticky-column').each(function() {
			var $column = $(this);
			var offset = 150;
			var classes = $column.attr('class').split(' ');

			for (var index = 0; index < classes.length; index++) {
				if (classes[index].indexOf('wd_sticky_offset_') >= 0) {
					var data = classes[index].split('_');
					offset = parseInt(data[3]);
				}
			}

			var $widgetWrap = $column.find('> .elementor-column-wrap > .elementor-widget-wrap');

			if ($widgetWrap.length <= 0) {
				$widgetWrap = $column.find('> .elementor-widget-wrap');
			}

			$widgetWrap.stick_in_parent({
				offset_top: offset
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.stickyColumn();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.stickyFooter = function() {
		if (!woodmartThemeModule.$body.hasClass('sticky-footer-on') || woodmartThemeModule.$window.width() <= 1024) {
			return;
		}

		var $footer    = $('.footer-container'),
		    $page      = $('.main-page-wrapper'),
		    $prefooter = $('.wd-prefooter'),
		    $window    = woodmartThemeModule.$window;

		if ($prefooter.length > 0) {
			$page = $prefooter;
		}

		var footerOffset = function() {
			$page.css({
				marginBottom: $footer.outerHeight()
			});
		};

		$window.on('resize', footerOffset);

		$footer.imagesLoaded(function() {
			footerOffset();
		});

		//Safari fix
		var footerSafariFix = function() {
			if (!$('html').hasClass('browser-Safari')) {
				return;
			}

			var windowScroll = $window.scrollTop();
			var footerOffsetTop = woodmartThemeModule.$document.outerHeight() - $footer.outerHeight();

			if (footerOffsetTop < windowScroll + $footer.outerHeight() + $window.outerHeight()) {
				$footer.addClass('visible-footer');
			} else {
				$footer.removeClass('visible-footer');
			}
		};

		footerSafariFix();
		$window.on('scroll', footerSafariFix);
	};

	$(document).ready(function() {
		woodmartThemeModule.stickyFooter();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.stickySocialButtons = function() {
		$('.wd-sticky-social').addClass('buttons-loaded');
	};

	$(document).ready(function() {
		woodmartThemeModule.stickySocialButtons();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.widgetsHidable = function() {
		woodmartThemeModule.$document.on('click', '.widget-hidable .widget-title', function() {
			var $this = $(this);
			var $content = $this.siblings('ul, div, form, label, select');

			$this.parent().toggleClass('widget-hidden');
			$content.stop().slideToggle(200);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.widgetsHidable();
	});
})(jQuery);

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

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.headerBuilder = function() {
		var $header         = $('.whb-header'),
		    $headerBanner   = $('.header-banner'),
		    $stickyElements = $('.whb-sticky-row'),
		    $firstSticky    = '',
		    $window         = woodmartThemeModule.$window,
		    isSticked       = false,
		    stickAfter      = 300,
		    cloneHTML       = '',
		    previousScroll,
		    isHideOnScroll  = $header.hasClass('whb-hide-on-scroll');

		$stickyElements.each(function() {
			var $this = $(this);

			if ($this[0].offsetHeight > 10) {
				$firstSticky = $this;
				return false;
			}
		});

		// Real header sticky option
		if ($header.hasClass('whb-sticky-real') || $header.hasClass('whb-scroll-slide')) {
			var $adminBar = $('#wpadminbar');
			var headerHeight = $header.find('.whb-main-header')[0].offsetHeight;
			var adminBarHeight = $adminBar.length > 0 ? $adminBar[0].offsetHeight : 0;

			if ($header.hasClass('whb-sticky-real')) {
				// if no sticky rows
				if ($firstSticky.length === 0 || $firstSticky[0].offsetHeight < 10) {
					return;
				}

				$header.addClass('whb-sticky-prepared').css({
					paddingTop: headerHeight
				});

				stickAfter = $firstSticky.offset().top - adminBarHeight;
			}

			if ($header.hasClass('whb-scroll-slide')) {
				stickAfter = headerHeight + adminBarHeight;
			}
		}

		if ($header.hasClass('whb-sticky-clone')) {
			var data = [];
			data['cloneClass'] = $header.find('.whb-general-header').attr('class');

			if (isHideOnScroll) {
				data['wrapperClasses'] = 'whb-hide-on-scroll';
			}

			cloneHTML = woodmart_settings.whb_header_clone;

			cloneHTML = cloneHTML.replace(/<%([^%>]+)?%>/g, function(replacement) {
				var selector = replacement.slice(2, -2);

				return $header.find(selector).length
					? $('<div>')
						.append($header.find(selector).first().clone())
						.html()
					: (data[selector] !== undefined) ? data[selector] : '';
			});

			cloneHTML = cloneHTML.replace(/<link[^>]*>/g,"");

			$header.after(cloneHTML);
			$header = $header.parent().find('.whb-clone');

			$header.find('.whb-row').removeClass('whb-flex-equal-sides').addClass('whb-flex-flex-middle');
		}

		$window.on('scroll', function() {
			var after = stickAfter;
			var currentScroll = woodmartThemeModule.$window.scrollTop();
			var windowHeight = woodmartThemeModule.$window.height();
			var documentHeight = woodmartThemeModule.$document.height();

			if ($headerBanner.length > 0 && woodmartThemeModule.$body.hasClass('header-banner-display')) {
				after += $headerBanner[0].offsetHeight;
			}

			if (!$('.close-header-banner').length && $header.hasClass('whb-scroll-stick')) {
				after = stickAfter;
			}

			if (currentScroll > after) {
				stickHeader();
			} else {
				unstickHeader();
			}

			var startAfter = 100;

			if ($header.hasClass('whb-scroll-stick')) {
				startAfter = 500;
			}

			if (isHideOnScroll) {
				if (previousScroll - currentScroll > 0 && currentScroll > after) {
					$header.addClass('whb-scroll-up');
					$header.removeClass('whb-scroll-down');
				} else if (currentScroll - previousScroll > 0 && currentScroll + windowHeight !== documentHeight && currentScroll > (after + startAfter)) {
					$header.addClass('whb-scroll-down');
					$header.removeClass('whb-scroll-up');
				} else if (currentScroll <= after) {
					$header.removeClass('whb-scroll-down');
					$header.removeClass('whb-scroll-up');
				} else if (currentScroll + windowHeight >= documentHeight - 5) {
					$header.addClass('whb-scroll-up');
					$header.removeClass('whb-scroll-down');
				}
			}

			previousScroll = currentScroll;
		});

		function stickHeader() {
			if (isSticked) {
				return;
			}

			isSticked = true;
			$header.addClass('whb-sticked');
		}

		function unstickHeader() {
			if (!isSticked) {
				return;
			}

			isSticked = false;
			$header.removeClass('whb-sticked');
		}

		woodmartThemeModule.$document.trigger('wdHeaderBuilderInited');
	};

	woodmartThemeModule.$window.on('wdEventStarted', function() {
		woodmartThemeModule.headerBuilder();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.mobileSearchIcon = function() {
		$('.wd-header-search-mobile').on('click', function(e) {
			e.preventDefault();
			var $nav = $('.mobile-nav');

			if (!$nav.hasClass('wd-opened')) {
				$nav.addClass('wd-opened');
				$('.wd-close-side').addClass('wd-close-side-opened');
				$('.mobile-nav .searchform').find('input[type="text"]').focus();
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.mobileSearchIcon();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.fullScreenMenu = function() {
		$('.wd-header-fs-nav > a').on('click', function(e) {
			e.preventDefault();

			$('.wd-fs-menu').addClass('wd-opened');
		});

		woodmartThemeModule.$document.on('keyup', function(e) {
			if (e.keyCode === 27) {
				$('.wd-fs-close').click();
			}
		});

		$('.wd-fs-close').on('click', function() {
			$('.wd-fs-menu').removeClass('wd-opened');

			setTimeout(function() {
				$('.wd-nav-fs .menu-item-has-children').removeClass('sub-menu-open');
				$('.wd-nav-fs .menu-item-has-children .wd-nav-opener').removeClass('wd-active');
			}, 200);
		});

		$('.wd-nav-fs > .menu-item-has-children > a, .wd-nav-fs .wd-dropdown-fs-menu.wd-design-default .menu-item-has-children > a').append('<span class="wd-nav-opener"></span>');

		$('.wd-nav-fs').on('click', '.wd-nav-opener', function(e) {
			e.preventDefault();
			var $icon       = $(this),
			    $parentItem = $icon.parent().parent();

			if ($parentItem.hasClass('sub-menu-open')) {
				$parentItem.removeClass('sub-menu-open');
				$icon.removeClass('wd-active');
			} else {
				$parentItem.siblings('.sub-menu-open').find('.wd-nav-opener').removeClass('wd-active');
				$parentItem.siblings('.sub-menu-open').removeClass('sub-menu-open');
				$parentItem.addClass('sub-menu-open');
				$icon.addClass('wd-active');
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.fullScreenMenu();
	});
})(jQuery);

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

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.menuOffsets = function() {
		var setOffset = function(li) {
			var $dropdown = li.find(' > .wd-dropdown-menu');
			var dropdownWidth = $dropdown.outerWidth();
			var dropdownOffset = $dropdown.offset();
			var toRight;
			var viewportWidth;
			var dropdownOffsetRight;

			$dropdown.attr('style', '');

			if (!dropdownWidth || !dropdownOffset) {
				return;
			}

			if ($dropdown.hasClass('wd-design-full-width')) {
				viewportWidth = woodmartThemeModule.$window.width();

				if (woodmartThemeModule.$body.hasClass('rtl')) {
					dropdownOffsetRight = viewportWidth - dropdownOffset.left - dropdownWidth;

					if (dropdownOffsetRight + dropdownWidth >= viewportWidth) {
						toRight = dropdownOffsetRight + dropdownWidth - viewportWidth;

						$dropdown.css({
							right: -toRight
						});
					}
				} else {
					if (dropdownOffset.left + dropdownWidth >= viewportWidth) {
						toRight = dropdownOffset.left + dropdownWidth - viewportWidth;

						$dropdown.css({
							left: -toRight
						});
					}
				}
			} else if ($dropdown.hasClass('wd-design-sized') || $dropdown.hasClass('wd-design-default')) {
				viewportWidth = woodmart_settings.site_width;

				if (woodmartThemeModule.$window.width() < viewportWidth || ! viewportWidth) {
					viewportWidth = woodmartThemeModule.$window.width();
				}

				dropdownOffsetRight = viewportWidth - dropdownOffset.left - dropdownWidth;

				var extraSpace = 15;
				var containerOffset = (woodmartThemeModule.$window.width() - viewportWidth) / 2;
				var dropdownOffsetLeft;

				if (woodmartThemeModule.$body.hasClass('rtl')) {
					dropdownOffsetLeft = containerOffset + dropdownOffsetRight;

					if (dropdownOffsetLeft + dropdownWidth >= viewportWidth) {
						toRight = dropdownOffsetLeft + dropdownWidth - viewportWidth;

						$dropdown.css({
							right: -toRight - extraSpace
						});
					}
				} else {
					dropdownOffsetLeft = dropdownOffset.left - containerOffset;

					if (dropdownOffsetLeft + dropdownWidth >= viewportWidth) {
						toRight = dropdownOffsetLeft + dropdownWidth - viewportWidth;

						$dropdown.css({
							left: -toRight - extraSpace
						});
					}
				}
			}
		};

		$('.wd-header-main-nav ul.menu > li, .wd-header-secondary-nav ul.menu > li').each(function() {
			var $menu = $(this);

			if ($menu.hasClass('menu-item')) {
				$menu = $(this).parent();
			}

			$menu.on('mouseenter mousemove', function() {
				if ($menu.hasClass('wd-offsets-calculated')) {
					return;
				}

				$menu.find(' > .menu-item-has-children').each(function() {
					setOffset($(this));
				});

				woodmartThemeModule.$document.trigger('resize.vcRowBehaviour');

				$menu.addClass('wd-offsets-calculated');
			});

			if ('yes' === woodmart_settings.clear_menu_offsets_on_resize) {
				setTimeout(function() {
					woodmartThemeModule.$window.on('resize', woodmartThemeModule.debounce(function() {
						$menu.removeClass('wd-offsets-calculated');
						$menu.find(' > .menu-item-has-children > .wd-dropdown-menu').attr('style', '');
					}, 300));
				}, 2000);
			}
		});
	};

	woodmartThemeModule.$window.on('wdEventStarted', function() {
		woodmartThemeModule.menuOffsets();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.menuSetUp = function() {
		var hasChildClass = 'menu-item-has-children',
		    mainMenu      = $('.wd-nav'),
		    openedClass   = 'wd-opened';

		$('.mobile-nav').find('ul.wd-nav-mobile').find(' > li').has('.wd-dropdown-menu').addClass(hasChildClass);

		mainMenu.on('click', ' > .wd-event-click > a', function(e) {
			e.preventDefault();
			var $this = $(this);

			if (!$this.parent().hasClass(openedClass)) {
				$('.' + openedClass).removeClass(openedClass);
			}

			$this.parent().toggleClass(openedClass);
		});

		woodmartThemeModule.$document.on('click', function(e) {
			var target = e.target;

			if ($('.' + openedClass).length > 0 && !$(target).is('.wd-event-hover') && !$(target).parents().is('.wd-event-hover') && !$(target).parents().is('.' + openedClass + '')) {
				mainMenu.find('.' + openedClass + '').removeClass(openedClass);
			}
		});

		if ('yes' === woodmart_settings.menu_item_hover_to_click_on_responsive) {
			function menuIpadClick() {
				if (woodmartThemeModule.$window.width() <= 1024) {
					mainMenu.find(' > .menu-item-has-children.wd-event-hover').each(function() {
						$(this).data('original-event', 'hover').removeClass('wd-event-hover').addClass('wd-event-click');
					});
				} else {
					mainMenu.find(' > .wd-event-click').each(function() {
						var $this = $(this);

						if ($this.data('original-event') === 'hover') {
							$this.removeClass('wd-event-click').addClass('wd-event-hover');
						}
					});
				}
			}

			menuIpadClick();

			woodmartThemeModule.$window.on('resize', woodmartThemeModule.debounce(function() {
				menuIpadClick();
			}, 300));
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.menuSetUp();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.mobileNavigation = function() {
		var body        = woodmartThemeModule.$body,
		    mobileNav   = $('.mobile-nav'),
		    dropDownCat = $('.mobile-nav .wd-nav-mobile .menu-item-has-children'),
		    elementIcon = '<span class="wd-nav-opener"></span>';

		var closeSide = $('.wd-close-side');

		dropDownCat.append(elementIcon);

		mobileNav.on('click', '.wd-nav-opener', function(e) {
			e.preventDefault();
			var $this = $(this);
			var $parent = $this.parent();

			if ($parent.hasClass('opener-page')) {
				$parent.removeClass('opener-page').find('> ul').slideUp(200);
				$parent.removeClass('opener-page').find('.wd-dropdown-menu .container > ul, .wd-dropdown-menu > ul').slideUp(200);
				$parent.find('> .wd-nav-opener').removeClass('wd-active');
			} else {
				$parent.addClass('opener-page').find('> ul').slideDown(200);
				$parent.addClass('opener-page').find('.wd-dropdown-menu .container > ul, .wd-dropdown-menu > ul').slideDown(200);
				$parent.find('> .wd-nav-opener').addClass('wd-active');
			}
		});

		mobileNav.on('click', '.wd-nav-mob-tab li', function(e) {
			e.preventDefault();
			var $this = $(this);
			var menuName = $this.data('menu');

			if ($this.hasClass('wd-active')) {
				return;
			}

			$this.parent().find('.wd-active').removeClass('wd-active');
			$this.addClass('wd-active');
			$('.wd-nav-mobile').removeClass('wd-active');
			$('.mobile-' + menuName + '-menu').addClass('wd-active');
		});

		body.on('click', '.wd-header-mobile-nav > a', function(e) {
			e.preventDefault();

			if (mobileNav.hasClass('wd-opened')) {
				closeMenu();
			} else {
				openMenu();
			}
		});

		body.on('click touchstart', '.wd-close-side', function() {
			closeMenu();
		});

		body.on('click', '.mobile-nav .login-side-opener', function() {
			closeMenu();
		});

		function openMenu() {
			mobileNav.addClass('wd-opened');
			closeSide.addClass('wd-close-side-opened');
		}

		function closeMenu() {
			mobileNav.removeClass('wd-opened');
			closeSide.removeClass('wd-close-side-opened');
			$('.mobile-nav .searchform input[type=text]').blur();
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.mobileNavigation();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.moreCategoriesButton = function() {
		$('.wd-more-cat').each(function() {
			var $wrapper = $(this);

			$wrapper.find('.wd-more-cat-btn a').on('click', function(e) {
				e.preventDefault();
				$wrapper.toggleClass('wd-show-cat');
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.moreCategoriesButton();
	});
})(jQuery);

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

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.simpleDropdown = function() {
		$('.wd-search-cat').each(function() {
			var dd = $(this);
			var btn = dd.find('> a');
			var input = dd.find('> input');
			var list = dd.find('> .wd-dropdown');
			var $searchInput = dd.parent().parent().find('.s');

			$searchInput.on('focus', function() {
				inputPadding();
			});

			woodmartThemeModule.$document.on('click', function(e) {
				var target = e.target;

				if (list.hasClass('wd-opened') && !$(target).is('.wd-search-cat') && !$(target).parents().is('.wd-search-cat')) {
					hideList();
					return false;
				}
			});

			btn.on('click', function(e) {
				e.preventDefault();

				if (list.hasClass('wd-opened')) {
					hideList();
				} else {
					showList();
				}

				return false;
			});

			list.on('click', 'a', function(e) {
				e.preventDefault();
				var $this = $(this);
				var value = $this.data('val');
				var label = $this.text();

				list.find('.current-item').removeClass('current-item');
				$this.parent().addClass('current-item');

				if (value !== 0) {
					list.find('ul:not(.children) > li:first-child').show();
				} else if (value === 0) {
					list.find('ul:not(.children) > li:first-child').hide();
				}

				btn.find('span').text(label);
				input.val(value).trigger('cat_selected');
				hideList();
				inputPadding();
			});

			function showList() {
				list.addClass('wd-opened');

				if (typeof ($.fn.devbridgeAutocomplete) != 'undefined') {
					dd.parent().siblings('[type="text"]').devbridgeAutocomplete('hide');
				}

				setTimeout(function() {
					woodmartThemeModule.$document.trigger('wdSimpleDropdownOpened');
				}, 300);
			}

			function hideList() {
				list.removeClass('wd-opened');
			}

			function inputPadding() {
				if (woodmartThemeModule.$window.width() <= 768 || $searchInput.hasClass('wd-padding-inited') || 'yes' !== woodmart_settings.search_input_padding) {
					return;
				}

				var paddingValue = dd.innerWidth() + dd.parent().siblings('.searchsubmit').innerWidth() + 17,
				    padding      = 'padding-right';

				if (woodmartThemeModule.$body.hasClass('rtl')) {
					padding = 'padding-left';
				}

				$searchInput.css(padding, paddingValue);
				$searchInput.addClass('wd-padding-inited');
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.simpleDropdown();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.ajaxPortfolio = function() {
		if ('no' === woodmart_settings.ajax_portfolio || 'undefined' === typeof ($.fn.pjax)) {
			return;
		}

		var ajaxLinks = '.wd-type-links .wd-nav-portfolio a, .tax-project-cat .wd-pagination a, .post-type-archive-portfolio .wd-pagination a';

		woodmartThemeModule.$body.on('click', '.tax-project-cat .wd-pagination a, .post-type-archive-portfolio .wd-pagination a', function() {
			scrollToTop(true);
		});

		woodmartThemeModule.$document.pjax(ajaxLinks, '.main-page-wrapper', {
			timeout : woodmart_settings.pjax_timeout,
			scrollTo: false,
			renderCallback: function(context, html, afterRender) {
				woodmartThemeModule.removeDuplicatedStylesFromHTML(html, function(html) {
					context.html(html);
					afterRender();
				});
			}
		});

		woodmartThemeModule.$document.on('pjax:start', function() {
			var $siteContent = $('.site-content');

			$siteContent.removeClass('ajax-loaded');
			$siteContent.addClass('ajax-loading');

			woodmartThemeModule.$document.trigger('wdPortfolioPjaxStart');
			woodmartThemeModule.$window.trigger('scroll.loaderVerticalPosition');
		});

		woodmartThemeModule.$document.on('pjax:end', function() {
			$('.site-content').removeClass('ajax-loading');
		});

		woodmartThemeModule.$document.on('pjax:complete', function() {
			if (!woodmartThemeModule.$body.hasClass('tax-project-cat') && !woodmartThemeModule.$body.hasClass('post-type-archive-portfolio')) {
				return;
			}

			woodmartThemeModule.$document.trigger('wdPortfolioPjaxComplete');
			woodmartThemeModule.$document.trigger('wood-images-loaded');

			scrollToTop(false);

			$('.wd-ajax-content').removeClass('wd-loading');
		});

		var scrollToTop = function(type) {
			if (woodmart_settings.ajax_scroll === 'no' && type === false) {
				return false;
			}

			var $scrollTo = $(woodmart_settings.ajax_scroll_class),
			    scrollTo  = $scrollTo.offset().top - woodmart_settings.ajax_scroll_offset;

			$('html, body').stop().animate({
				scrollTop: scrollTo
			}, 400);
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.ajaxPortfolio();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioLoadMoreLoaded wdPortfolioPjaxComplete', function () {
		woodmartThemeModule.portfolioEffects();
	});

	$.each([
		'frontend/element_ready/wd_portfolio.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.portfolioEffects();
		});
	});

	woodmartThemeModule.portfolioEffects = function() {
		if (typeof ($.fn.panr) === 'undefined') {
			return;
		}

		$('.wd-portfolio-holder .portfolio-parallax').panr({
			sensitivity         : 15,
			scale               : false,
			scaleOnHover        : true,
			scaleTo             : 1.12,
			scaleDuration       : 0.45,
			panY                : true,
			panX                : true,
			panDuration         : 1.5,
			resetPanOnMouseLeave: true
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.portfolioEffects();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioPjaxComplete', function () {
		woodmartThemeModule.portfolioLoadMore();
	});

	$.each([
		'frontend/element_ready/wd_portfolio.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.portfolioLoadMore();
		});
	});

	woodmartThemeModule.portfolioLoadMore = function() {
		if (typeof $.fn.waypoint !== 'function') {
			return;
		}

		var waypoint = $('.wd-portfolio-load-more.load-on-scroll').waypoint(function() {
			    $('.wd-portfolio-load-more.load-on-scroll').trigger('click');
		    }, {offset: '100%'}),
		    process  = false;

		$('.wd-portfolio-load-more').on('click', function(e) {
			e.preventDefault();

			var $this = $(this);

			if (process || $this.hasClass('no-more-posts')) {
				return;
			}

			process = true;

			var holder   = $this.parent().parent().find('.wd-portfolio-holder'),
			    source   = holder.data('source'),
			    action   = 'woodmart_get_portfolio_' + source,
			    ajaxurl  = woodmart_settings.ajaxurl,
			    dataType = 'json',
			    method   = 'POST',
			    timeout,
			    atts     = holder.data('atts'),
			    paged    = holder.data('paged');

			$this.addClass('loading');

			var data = {
				atts  : atts,
				paged : paged,
				action: action
			};

			if (source === 'main_loop') {
				ajaxurl = $this.attr('href');
				method = 'GET';
				data = {};
			}

			$.ajax({
				url     : ajaxurl,
				data    : data,
				dataType: dataType,
				method  : method,
				success : function(data) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(data.items, function(html) {
						var items = $(html);

						if (items) {
							if (holder.hasClass('masonry-container')) {
								holder.append(items).isotope('appended', items);
								holder.imagesLoaded().progress(function() {
									holder.isotope('layout');

									clearTimeout(timeout);

									timeout = setTimeout(function() {
										waypoint = $('.wd-portfolio-load-more.load-on-scroll').waypoint(function() {
											$('.wd-portfolio-load-more.load-on-scroll').trigger('click');
										}, {offset: '100%'});
									}, 1000);
								});
							} else {
								holder.append(items);
							}

							holder.data('paged', paged + 1);

							$this.attr('href', data.nextPage);

							if ('yes' === woodmart_settings.load_more_button_page_url){
								window.history.pushState('', '', data.currentPage);
							}
						}

						woodmartThemeModule.$document.trigger('wdPortfolioLoadMoreLoaded');

						if (data.status === 'no-more-posts') {
							$this.addClass('no-more-posts');
							$this.hide();
						}
					});
				},
				error   : function() {
					console.log('ajax error');
				},
				complete: function() {
					$this.removeClass('loading');
					process = false;
				}
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.portfolioLoadMore();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioPjaxComplete', function () {
		woodmartThemeModule.portfolioMasonryFilters();
	});

	$.each([
		'frontend/element_ready/wd_portfolio.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.portfolioMasonryFilters();
		});
	});

	woodmartThemeModule.portfolioMasonryFilters = function() {
		var $filer = $('.wd-nav-portfolio');
		$filer.on('click', 'li', function(e) {
			e.preventDefault();
			var $this = $(this);
			var filterValue = $this.attr('data-filter');

			setTimeout(function() {
				woodmartThemeModule.$document.trigger('wood-images-loaded');
			}, 300);

			$filer.find('.wd-active').removeClass('wd-active');
			$this.addClass('wd-active');
			$this.parents('.portfolio-filter').siblings('.masonry-container.wd-portfolio-holder').isotope({
				filter: filterValue
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.portfolioMasonryFilters();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioPjaxComplete', function () {
		woodmartThemeModule.portfolioPhotoSwipe();
	});

	$.each([
		'frontend/element_ready/wd_portfolio.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.portfolioPhotoSwipe();
		});
	});

	woodmartThemeModule.portfolioPhotoSwipe = function() {
		woodmartThemeModule.$document.on('click', '.portfolio-enlarge', function(e) {
			e.preventDefault();
			var $this = $(this);
			var $parent = $this.parents('.owl-item');

			if ($parent.length === 0) {
				$parent = $this.parents('.portfolio-entry');
			}

			var index = $parent.index();
			var items = getPortfolioImages();

			woodmartThemeModule.callPhotoSwipe(index, items);
		});

		var getPortfolioImages = function() {
			var items = [];

			$('.portfolio-entry').find('figure a img').each(function() {
				var $this = $(this);

				items.push({
					src: $this.attr('src'),
					w  : $this.attr('width'),
					h  : $this.attr('height')
				});
			});

			return items;
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.portfolioPhotoSwipe();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.addToCart();
		});
	});

	woodmartThemeModule.addToCart = function() {
		var that = this;
		var timeoutNumber = 0;
		var timeout;

		woodmartThemeModule.$body.on('added_to_cart', function() {
			if (woodmart_settings.add_to_cart_action === 'popup') {
				var html = [
					'<div class="added-to-cart">',
					'<h3>' + woodmart_settings.added_to_cart + '</h3>',
					'<a href="#" class="btn btn-style-link btn-color-default close-popup">' + woodmart_settings.continue_shopping + '</a>',
					'<a href="' + woodmart_settings.cart_url + '" class="btn btn-color-primary view-cart">' + woodmart_settings.view_cart + '</a>',
					'</div>'
				].join('');

				$.magnificPopup.open({
					removalDelay: 500, //delay removal by X to allow out-animation
					tClose      : woodmart_settings.close,
					tLoading    : woodmart_settings.loading,
					callbacks   : {
						beforeOpen: function() {
							this.st.mainClass = 'mfp-move-horizontal cart-popup-wrapper';
						}
					},
					items       : {
						src : '<div class="mfp-with-anim wd-popup white-popup popup-added_to_cart">' + html + '</div>',
						type: 'inline'
					}
				});

				$('.white-popup').on('click', '.close-popup', function(e) {
					e.preventDefault();
					$.magnificPopup.close();
				});

				closeAfterTimeout();
			} else if (woodmart_settings.add_to_cart_action === 'widget') {
				clearTimeout(timeoutNumber);
				var $selector = $('.act-scroll .wd-header-cart .wd-dropdown-cart, .whb-sticked .wd-header-cart .wd-dropdown-cart');

				if ($selector.length > 0) {
					$selector.addClass('wd-opened');
				} else {
					$('.whb-header .wd-header-cart .wd-dropdown-cart').addClass('wd-opened');
				}

				var $cartOpener = $('.cart-widget-opener');
				if ($cartOpener.length > 0) {
					$cartOpener.first().trigger('click');
				}

				timeoutNumber = setTimeout(function() {
					$('.wd-dropdown-cart').removeClass('wd-opened');
				}, 3500);

				closeAfterTimeout();
			}

			woodmartThemeModule.$document.trigger('wdActionAfterAddToCart');
		});

		var closeAfterTimeout = function() {
			if ('yes' !== woodmart_settings.add_to_cart_action_timeout) {
				return false;
			}

			clearTimeout(timeout);

			timeout = setTimeout(function() {
				$('.wd-close-side').trigger('click');
				$.magnificPopup.close();
			}, parseInt(woodmart_settings.add_to_cart_action_timeout_number) * 1000);
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.addToCart();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.addToCartAllTypes = function() {
		if (woodmart_settings.ajax_add_to_cart == false) {
			return;
		}

		woodmartThemeModule.$body.on('submit', 'form.cart', function(e) {
			var $form = $(this);
			var $productWrapper = $form.parents('.single-product-page');

			if ($productWrapper.length === 0) {
				$productWrapper = $form.parents('.product-quick-view');
			}

			if ($productWrapper.hasClass('product-type-external') || $productWrapper.hasClass('product-type-zakeke') || $productWrapper.hasClass('product-type-gift-card')) {
				return;
			}

			e.preventDefault();

			var $thisbutton = $form.find('.single_add_to_cart_button'),
			    data        = $form.serialize();

			data += '&action=woodmart_ajax_add_to_cart';

			if ($thisbutton.val()) {
				data += '&add-to-cart=' + $thisbutton.val();
			}

			$thisbutton.removeClass('added not-added');
			$thisbutton.addClass('loading');

			// Trigger event
			woodmartThemeModule.$body.trigger('adding_to_cart', [
				$thisbutton,
				data
			]);

			$.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : data,
				method  : 'POST',
				success : function(response) {
					if (!response) {
						return;
					}

					var this_page = window.location.toString();

					this_page.replace('add-to-cart', 'added-to-cart');

					if (response.error && response.product_url) {
						window.location = response.product_url;
						return;
					}

					// Redirect to cart option
					if (woodmart_settings.cart_redirect_after_add === 'yes') {
						window.location = woodmart_settings.cart_url;
					} else {

						$thisbutton.removeClass('loading');

						var fragments = response.fragments;
						var cart_hash = response.cart_hash;

						// Block fragments class
						if (fragments) {
							$.each(fragments, function(key) {
								$(key).addClass('updating');
							});
						}

						// Replace fragments
						if (fragments) {
							$.each(fragments, function(key, value) {
								$(key).replaceWith(value);
							});
						}

						// Show notices
						if (response.notices.indexOf('error') > 0) {
							woodmartThemeModule.$body.append(response.notices);
							$thisbutton.addClass('not-added');
						} else {
							if (woodmart_settings.add_to_cart_action === 'widget') {
								$.magnificPopup.close();
							}

							// Changes button classes
							$thisbutton.addClass('added');
							// Trigger event so themes can refresh other areas
							woodmartThemeModule.$body.trigger('added_to_cart', [
								fragments,
								cart_hash,
								$thisbutton
							]);
						}
					}
				},
				error   : function() {
					console.log('ajax adding to cart error');
				},
				complete: function() { }
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.addToCartAllTypes();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.ajaxFilters = function() {
		if (!woodmartThemeModule.$body.hasClass('woodmart-ajax-shop-on') || typeof ($.fn.pjax) === 'undefined' || woodmartThemeModule.$body.hasClass('single-product')) {
			return;
		}

		var that         = this,
		    filtersState = false;

		woodmartThemeModule.$body.on('click', '.post-type-archive-product .products-footer .woocommerce-pagination a', function() {
			scrollToTop(true);
		});

		woodmartThemeModule.$document.pjax(woodmartThemeModule.ajaxLinks, '.main-page-wrapper', {
			timeout       : woodmart_settings.pjax_timeout,
			scrollTo      : false,
			renderCallback: function(context, html, afterRender) {
				woodmartThemeModule.removeDuplicatedStylesFromHTML(html, function(html) {
					context.html(html);
					afterRender();
					woodmartThemeModule.$document.trigger('wdShopPageInit');
					woodmartThemeModule.$document.trigger('wood-images-loaded');
				});
			}
		});

		if (woodmart_settings.price_filter_action === 'click') {
			woodmartThemeModule.$document.on('click', '.widget_price_filter form .button', function() {
				var form = $('.widget_price_filter form');
				$.pjax({
					container: '.main-page-wrapper',
					timeout  : woodmart_settings.pjax_timeout,
					url      : form.attr('action'),
					data     : form.serialize(),
					scrollTo : false,
					renderCallback: function(context, html, afterRender) {
						woodmartThemeModule.removeDuplicatedStylesFromHTML(html, function(html) {
							context.html(html);
							afterRender();
							woodmartThemeModule.$document.trigger('wdShopPageInit');
							woodmartThemeModule.$document.trigger('wood-images-loaded');
						});
					}
				});

				return false;
			});
		} else if (woodmart_settings.price_filter_action === 'submit') {
			woodmartThemeModule.$document.on('submit', '.widget_price_filter form', function(event) {
				var container = $('.main-page-wrapper');
				$.pjax.submit(event, container);
			});
		}

		woodmartThemeModule.$document.on('pjax:error', function(xhr, textStatus, error) {
			console.log('pjax error ' + error);
		});

		woodmartThemeModule.$document.on('pjax:start', function() {
			var $siteContent = $('.site-content');

			$siteContent.removeClass('ajax-loaded');
			$siteContent.addClass('ajax-loading');

			woodmartThemeModule.$document.trigger('wdPjaxStart');
			woodmartThemeModule.$window.trigger('scroll.loaderVerticalPosition');
		});

		woodmartThemeModule.$document.on('pjax:complete', function() {
			woodmartThemeModule.$window.off('scroll.loaderVerticalPosition');

			scrollToTop(false);

			woodmartThemeModule.$document.trigger('wood-images-loaded');

			$('.wd-scroll-content').on('scroll', function() {
				woodmartThemeModule.$document.trigger('wood-images-loaded');
			});

			$('.site-content').removeClass('ajax-loading');

			if (typeof woodmart_wpml_js_data !== 'undefined' && woodmart_wpml_js_data.languages) {
				$.each(woodmart_wpml_js_data.languages, function(index, language) {
					$('.wpml-ls-item-' + language.code + ' .wpml-ls-link').attr('href', language.url);
				});
			}
		});

		woodmartThemeModule.$document.on('pjax:beforeReplace', function() {
			if ($('.filters-area').hasClass('filters-opened') && woodmart_settings.shop_filters_close === 'yes') {
				filtersState = true;
				woodmartThemeModule.$body.addClass('body-filters-opened');
			}
		});

		woodmartThemeModule.$document.on('pjax:end', function() {
			var $siteContent = $('.site-content');

			if (filtersState) {
				$('.filters-area').css('display', 'block');
				woodmartThemeModule.openFilters(200);
				filtersState = false;
			}

			$siteContent.removeClass('ajax-loading');
			$siteContent.addClass('ajax-loaded');
		});

		var scrollToTop = function(type) {
			if (woodmart_settings.ajax_scroll === 'no' && type === false) {
				return false;
			}

			var $scrollTo = $(woodmart_settings.ajax_scroll_class),
			    scrollTo  = $scrollTo.offset().top - woodmart_settings.ajax_scroll_offset;

			$('html, body').stop().animate({
				scrollTop: scrollTo
			}, 400);
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.ajaxFilters();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.cartWidget = function() {
		var body = woodmartThemeModule.$body;

		var cartWidgetSide = $('.cart-widget-side');
		var closeSide = $('.wd-close-side');

		body.on('click', '.cart-widget-opener', function(e) {
			if (!isCart() && !isCheckout()) {
				e.preventDefault();
			}

			if (isOpened()) {
				closeWidget();
			} else {
				setTimeout(function() {
					openWidget();
				}, 10);
			}
		});

		body.on('click touchstart', '.wd-close-side', function() {
			if (isOpened()) {
				closeWidget();
			}
		});

		body.on('click', '.close-side-widget', function(e) {
			e.preventDefault();
			if (isOpened()) {
				closeWidget();
			}
		});

		woodmartThemeModule.$document.on('keyup', function(e) {
			if (e.keyCode === 27 && isOpened()) {
				closeWidget();
			}
		});

		var closeWidget = function() {
			cartWidgetSide.removeClass('wd-opened');
			closeSide.removeClass('wd-close-side-opened');
		};

		var openWidget = function() {
			if (isCart() || isCheckout()) {
				return false;
			}
			cartWidgetSide.addClass('wd-opened');
			closeSide.addClass('wd-close-side-opened');
		};

		var isOpened = function() {
			return cartWidgetSide.hasClass('wd-opened');
		};

		var isCart = function() {
			return woodmartThemeModule.$body.hasClass('woocommerce-cart');
		};

		var isCheckout = function() {
			return woodmartThemeModule.$body.hasClass('woocommerce-checkout');
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.cartWidget();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdBackHistory wdShopPageInit', function () {
		woodmartThemeModule.categoriesAccordion();
	});

	woodmartThemeModule.categoriesAccordion = function() {

		if (woodmart_settings.categories_toggle === 'no') {
			return;
		}

		var $widget = $('.widget_product_categories'),
		    $list   = $widget.find('.product-categories'),
		    time    = 300;

		$list.find('.cat-parent').each(function() {
			var $this = $(this);

			if ($this.find(' > .wd-cats-toggle').length > 0) {
				return;
			}
			if ($this.find(' > .children').length === 0 || $this.find(' > .children > *').length === 0) {
				return;
			}

			$this.append('<div class="wd-cats-toggle"></div>');
		});

		$list.on('click', '.wd-cats-toggle', function() {
			var $btn     = $(this),
			    $subList = $btn.prev();

			if ($subList.hasClass('list-shown')) {
				$btn.removeClass('toggle-active');
				$subList.stop().slideUp(time).removeClass('list-shown');
			} else {
				$subList.parent().parent().find('> li > .list-shown').slideUp().removeClass('list-shown');
				$subList.parent().parent().find('> li > .toggle-active').removeClass('toggle-active');
				$btn.addClass('toggle-active');
				$subList.stop().slideDown(time).addClass('list-shown');
			}
		});

		if ($list.find('li.current-cat.cat-parent, li.current-cat-parent').length > 0) {
			$list.find('li.current-cat.cat-parent, li.current-cat-parent').find('> .wd-cats-toggle').click();
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.categoriesAccordion();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function() {
		woodmartThemeModule.categoriesDropdowns();
	});

	woodmartThemeModule.categoriesDropdowns = function() {
		$('.dropdown_product_cat').on('change', function() {
			var $this = $(this);

			if ('' !== $this.val()) {
				var this_page;
				var home_url = woodmart_settings.home_url;

				if (home_url.indexOf('?') > 0) {
					this_page = home_url + '&product_cat=' + $this.val();
				} else {
					this_page = home_url + '?product_cat=' + $this.val();
				}

				location.href = this_page;
			} else {
				location.href = woodmart_settings.shop_url;
			}
		});

		$('.widget_product_categories').each(function() {
			var $select = $(this).find('select');

			if ($().selectWoo) {
				$select.selectWoo({
					minimumResultsForSearch: 5,
					width                  : '100%',
					allowClear             : true,
					placeholder            : woodmart_settings.product_categories_placeholder,
					language               : {
						noResults: function() {
							return woodmart_settings.product_categories_no_results;
						}
					}
				});
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.categoriesDropdowns();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.categoriesMenu = function() {
		if (woodmartThemeModule.windowWidth > 1024) {
			return;
		}

		var categories = $('.wd-nav-product-cat'),
		    time       = 200;

		woodmartThemeModule.$body.on('click', '.wd-nav-opener', function(e) {
			var $this = $(this);
			e.preventDefault();

			if ($this.closest('.has-sub').find('> ul').hasClass('child-open')) {
				$this.removeClass('wd-active').closest('.has-sub').find('> ul').slideUp(time).removeClass('child-open');
			} else {
				$this.addClass('wd-active').closest('.has-sub').find('> ul').slideDown(time).addClass('child-open');
			}
		});

		woodmartThemeModule.$body.on('click', '.wd-btn-show-cat > a', function(e) {
			e.preventDefault();

			if (isOpened()) {
				closeCats();
			} else {
				openCats();
			}
		});

		woodmartThemeModule.$body.on('click', '.wd-nav-product-cat a', function(e) {
			if (!$(e.target).hasClass('wd-nav-opener')) {
				closeCats();
				categories.stop().attr('style', '');
			}
		});

		var isOpened = function() {
			return $('.wd-nav-product-cat').hasClass('categories-opened');
		};

		var openCats = function() {
			$('.wd-nav-product-cat').addClass('categories-opened').stop().slideDown(time);
			$('.wd-btn-show-cat').addClass('button-open');
		};

		var closeCats = function() {
			$('.wd-nav-product-cat').removeClass('categories-opened').stop().slideUp(time);
			$('.wd-btn-show-cat').removeClass('button-open');
		};
	};

	woodmartThemeModule.categoriesMenuBtns = function() {
		if (woodmartThemeModule.windowWidth > 1024) {
			return;
		}

		var categories    = $('.wd-nav-product-cat'),
		    subCategories = categories.find('li > ul'),
		    iconDropdown  = '<span class="wd-nav-opener"></span>';

		subCategories.parent().addClass('has-sub').append(iconDropdown);
	};

	$(document).ready(function() {
		woodmartThemeModule.categoriesMenu();
		woodmartThemeModule.categoriesMenuBtns();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.commentImage = function() {
		$('form.comment-form').attr('enctype', 'multipart/form-data');

		var $form = $('.comment-form');
		var $input = $form.find('#wd-add-img-btn');
		var allowedMimes = [];

		if ($input.length === 0) {
			return;
		}

		$.each(woodmart_settings.comment_images_upload_mimes, function(index, value) {
			allowedMimes.push(String(value));
		});

		$form.find('#wd-add-img-btn').on('change', function() {
			$form.find('.wd-add-img-count').text(woodmart_settings.comment_images_added_count_text.replace('%s', this.files.length));
		});

		$form.on('submit', function(e) {
			$form.find('.woocommerce-error').remove();

			var hasLarge = false;
			var hasNotAllowedMime = false;

			if ($input[0].files.length > woodmart_settings.comment_images_count) {
				showError(woodmart_settings.comment_images_count_text);
				e.preventDefault();
			}

			Array.prototype.forEach.call($input[0].files, function(file) {
				var size = file.size;
				var type = String(file.type);

				if (size > woodmart_settings.comment_images_upload_size) {
					hasLarge = true;
				}

				if ($.inArray(type, allowedMimes) < 0) {
					hasNotAllowedMime = true;
				}
			});

			if (hasLarge) {
				showError(woodmart_settings.comment_images_upload_size_text);
				e.preventDefault();
			}

			if (hasNotAllowedMime) {
				showError(woodmart_settings.comment_images_upload_mimes_text);
				e.preventDefault();
			}
		});

		function showError(text) {
			$form.prepend('<ul class="woocommerce-error" role="alert"><li>' + text + '</li></ul>');
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.commentImage();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.filterDropdowns();
	});

	woodmartThemeModule.filterDropdowns = function() {
		$('.wd-widget-layered-nav-dropdown-form').each(function() {
			var $form = $(this);
			var $select = $form.find('select');
			var slug = $select.data('slug');

			$select.change(function() {
				var val = $(this).val();
				$('input[name=filter_' + slug + ']').val(val);
			});

			if ($().selectWoo) {
				$select.selectWoo({
					placeholder            : $select.data('placeholder'),
					minimumResultsForSearch: 5,
					width                  : '100%',
					allowClear             : !$select.attr('multiple'),
					language               : {
						noResults: function() {
							return $select.data('noResults');
						}
					}
				}).on('select2:unselecting', function() {
					$(this).data('unselecting', true);
				}).on('select2:opening', function(e) {
					var $this = $(this);

					if ($this.data('unselecting')) {
						$this.removeData('unselecting');
						e.preventDefault();
					}
				});
			}
		});

		function ajaxAction($element) {
			var $form = $element.parent('.wd-widget-layered-nav-dropdown-form');

			if (!woodmartThemeModule.$body.hasClass('woodmart-ajax-shop-on') || typeof ($.fn.pjax) === 'undefined') {
				return;
			}

			$.pjax({
				container: '.main-page-wrapper',
				timeout  : woodmart_settings.pjax_timeout,
				url      : $form.attr('action'),
				data     : $form.serialize(),
				scrollTo : false,
				renderCallback: function(context, html, afterRender) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(html, function(html) {
						context.html(html);
						afterRender();
						woodmartThemeModule.$document.trigger('wdShopPageInit');
						woodmartThemeModule.$document.trigger('wood-images-loaded');
					});
				}
			});
		}

		$('.wd-widget-layered-nav-dropdown__submit').on('click', function() {
			var $this = $(this);

			if (!$this.siblings('select').attr('multiple') || !woodmartThemeModule.$body.hasClass('woodmart-ajax-shop-on')) {
				return;
			}

			ajaxAction($this);

			$this.prop('disabled', true);
		});

		$('.wd-widget-layered-nav-dropdown-form select').on('change', function() {
			var $this = $(this);

			if (!woodmartThemeModule.$body.hasClass('woodmart-ajax-shop-on')) {
				$this.parent().submit();
				return;
			}

			if ($this.attr('multiple')) {
				return;
			}

			ajaxAction($this);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.filterDropdowns();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.filtersArea = function() {
		var filters = $('.filters-area'),
		    time    = 200;

		woodmartThemeModule.$body.on('click', '.open-filters', function(e) {
			e.preventDefault();

			if (isOpened()) {
				closeFilters();
			} else {
				woodmartThemeModule.openFilters(time);
				setTimeout(function() {
					woodmartThemeModule.$document.trigger('wdFiltersOpened');
				}, time);
			}
		});

		if (woodmart_settings.shop_filters_close === 'no') {
			woodmartThemeModule.$body.on('click', woodmartThemeModule.ajaxLinks, function() {
				if (isOpened()) {
					closeFilters();
				}
			});
		}

		var isOpened = function() {
			filters = $('.filters-area');
			return filters.hasClass('filters-opened');
		};

		var closeFilters = function() {
			filters = $('.filters-area');
			filters.removeClass('filters-opened');
			filters.stop().slideUp(time);
		};
	};

	woodmartThemeModule.openFilters = function(time) {
		var filters = $('.filters-area');
		filters.stop().slideDown(time);

		setTimeout(function() {
			filters.addClass('filters-opened');
			woodmartThemeModule.$document.trigger('wdFiltersOpened');

			woodmartThemeModule.$body.removeClass('body-filters-opened');
			woodmartThemeModule.$document.trigger('wood-images-loaded');
		}, time);
	};

	$(document).ready(function() {
		woodmartThemeModule.filtersArea();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdProductsTabsLoaded wdArrowsLoadProducts wdLoadMoreLoadProducts wdUpdateWishlist', function () {
		woodmartThemeModule.gridQuantity();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.gridQuantity();
		});
	});

	woodmartThemeModule.gridQuantity = function() {
		$('.product-grid-item').on('change input', '.quantity .qty', function() {
			var $this = $(this);
			var add_to_cart_button = $this.parent().parent().find('.add_to_cart_button');

			add_to_cart_button.attr('data-quantity', $this.val());
			add_to_cart_button.attr('href', '?add-to-cart=' + add_to_cart_button.attr('data-product_id') + '&quantity=' + $this.val());
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.gridQuantity();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.headerCategoriesMenu = function() {
		if (woodmartThemeModule.windowWidth > 1024) {
			return;
		}

		var categories    = $('.wd-header-cats'),
		    catsUl        = categories.find('.categories-menu-dropdown'),
		    subCategories = categories.find('.menu-item-has-children'),
		    button        = categories.find('.menu-opener'),
		    time          = 200,
		    iconDropdown  = '<span class="drop-category"></span>';

		subCategories.find('> a').before(iconDropdown);

		catsUl.on('click', '.drop-category', function() {
			var $this = $(this);
			var sublist = $this.parent().find('> .wd-dropdown-menu, >.sub-sub-menu');

			if (sublist.hasClass('child-open')) {
				$this.removeClass('act-icon');
				sublist.slideUp(time).removeClass('child-open');
			} else {
				$this.addClass('act-icon');
				sublist.slideDown(time).addClass('child-open');
			}
		});

		categories.on('click', '.menu-opener', function(e) {
			e.preventDefault();

			if (isOpened()) {
				closeCats();
			} else {
				openCats();
			}
		});

		catsUl.on('click', 'a', function() {
			closeCats();
			catsUl.stop().attr('style', '');
		});

		var isOpened = function() {
			return catsUl.hasClass('categories-opened');
		};

		var openCats = function() {
			catsUl.addClass('categories-opened').stop().slideDown(time);
			button.addClass('button-open');

		};

		var closeCats = function() {
			catsUl.removeClass('categories-opened').stop().slideUp(time);
			button.removeClass('button-open');
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.headerCategoriesMenu();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdReplaceMainGalleryNotQuickView wdShowVariationNotQuickView', function () {
		woodmartThemeModule.initZoom();
	});

	woodmartThemeModule.initZoom = function() {
		var $mainGallery = $('.woocommerce-product-gallery__wrapper:not(.quick-view-gallery)');

		if (woodmart_settings.zoom_enable !== 'yes') {
			return false;
		}

		var zoomOptions = {
			touch: false
		};

		if ('ontouchstart' in window) {
			zoomOptions.on = 'click';
		}

		var $productGallery = $('.woocommerce-product-gallery');
		if ($productGallery.hasClass('thumbs-position-bottom') || $productGallery.hasClass('thumbs-position-left')) {
			$mainGallery.on('changed.owl.carousel', function(e) {
				var $wrapper = $mainGallery.find('.product-image-wrap').eq(e.item.index).find('.woocommerce-product-gallery__image');

				init($wrapper);
			});

			init($mainGallery.find('.product-image-wrap').eq(0).find('.woocommerce-product-gallery__image'));
		} else {
			$mainGallery.find('.product-image-wrap').each(function() {
				var $wrapper = $(this).find('.woocommerce-product-gallery__image');

				init($wrapper);
			});
		}

		function init($wrapper) {
			var image = $wrapper.find('img');

			if (image.data('large_image_width') > $wrapper.width()) {
				$wrapper.trigger('zoom.destroy');
				$wrapper.zoom(zoomOptions);
			}
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.initZoom();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.loginDropdown = function() {
		if (woodmartThemeModule.windowWidth <= 1024) {
			return;
		}

		$('.wd-dropdown-register').each(function() {
			var $this    = $(this),
			    $content = $this.find('.login-dropdown-inner');

			$content.find('input[id="username"]').on('click', function() {
				$this.addClass('wd-active-login').removeClass('wd-active-link');
			});

			$content.find('input[id="username"]').on('input', function() {
				if ($this.hasClass('wd-active-login')) {
					$this.removeClass('wd-active-login').addClass('wd-active-link');
				}
			});

			$content.find('input').not('[id="username"]').on('click', function() {
				$this.removeClass('wd-active-login').removeClass('wd-active-link');
			});

			woodmartThemeModule.$document.click(function(a) {
				if ('undefined' != typeof (a.target.className.length) && a.target.className.indexOf('wd-dropdown-register') === -1 && a.target.className.indexOf('input-text') === -1) {
					$this.removeClass('wd-active-login').removeClass('wd-active-link');
				}
			});

			$('.wd-dropdown-register').on('mouseout', function() {
				if ($this.hasClass('wd-active-link')) {
					$this.removeClass('wd-active-link');
				}
			}).on('mouseleave', function() {
				if ($this.hasClass('wd-active-link')) {
					$this.removeClass('wd-active-link');
				}
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.loginDropdown();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.loginSidebar = function() {
		var body = woodmartThemeModule.$body;
		var loginFormSide = $('.login-form-side');
		var closeSide = $('.wd-close-side');

		$('.login-side-opener').on('click', function(e) {
			e.preventDefault();

			if (isOpened()) {
				closeWidget();
			} else {
				setTimeout(function() {
					openWidget();
				}, 10);
			}
		});

		body.on('click touchstart', '.wd-close-side', function() {
			if (isOpened()) {
				closeWidget();
			}
		});

		body.on('click', '.close-side-widget', function(e) {
			e.preventDefault();
			if (isOpened()) {
				closeWidget();
			}
		});

		woodmartThemeModule.$document.on('keyup', function(e) {
			if (e.keyCode === 27 && isOpened()) {
				closeWidget();
			}
		});

		var closeWidget = function() {
			loginFormSide.removeClass('wd-opened');
			closeSide.removeClass('wd-close-side-opened');
		};

		var openWidget = function() {
			loginFormSide.find('form').removeClass('hidden-form');
			loginFormSide.addClass('wd-opened');
			closeSide.addClass('wd-close-side-opened');
		};

		if (loginFormSide.find('.woocommerce-notices-wrapper > ul').length > 0) {
			openWidget();
		}

		var isOpened = function() {
			return loginFormSide.hasClass('wd-opened');
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.loginSidebar();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.loginTabs = function() {
		var tabs               = $('.wd-register-tabs'),
		    btn                = tabs.find('.wd-switch-to-register'),
		    title              = $('.col-register-text h2'),
		    loginText          = tabs.find('.login-info'),
		    classOpened        = 'active-register',
		    loginLabel         = btn.data('login'),
		    registerLabel      = btn.data('register'),
		    loginTitleLabel    = btn.data('login-title'),
		    registerTitleLabel = btn.data('reg-title');

		btn.on('click', function(e) {
			e.preventDefault();

			if (isShown()) {
				hideRegister();
			} else {
				showRegister();
			}

			if (woodmartThemeModule.$window.width() < 768) {
				$('html, body').stop().animate({
					scrollTop: tabs.offset().top - 90
				}, 400);
			}
		});

		var showRegister = function() {
			tabs.addClass(classOpened);
			btn.text(loginLabel);

			if (loginText.length > 0) {
				title.text(loginTitleLabel);
			}
		};

		var hideRegister = function() {
			tabs.removeClass(classOpened);
			btn.text(registerLabel);

			if (loginText.length > 0) {
				title.text(registerTitleLabel);
			}
		};

		var isShown = function() {
			return tabs.hasClass(classOpened);
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.loginTabs();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.miniCartQuantity = function() {
		var timeout;

		woodmartThemeModule.$document.on('change input', '.woocommerce-mini-cart .quantity .qty', function() {
			var input = $(this);
			var qtyVal = input.val();
			var itemID = input.parents('.woocommerce-mini-cart-item').data('key');
			var cart_hash_key = woodmart_settings.cart_hash_key;
			var fragment_name = woodmart_settings.fragment_name;

			clearTimeout(timeout);

			timeout = setTimeout(function() {
				input.parents('.mini_cart_item').addClass('wd-loading');

				$.ajax({
					url     : woodmart_settings.ajaxurl,
					data    : {
						action : 'woodmart_update_cart_item',
						item_id: itemID,
						qty    : qtyVal
					},
					success : function(data) {
						if (data && data.fragments) {
							$.each(data.fragments, function(key, value) {
								if ($(key).hasClass('widget_shopping_cart_content')) {
									var dataItemValue = $(value).find('.woocommerce-mini-cart-item[data-key="' + itemID + '"]');
									var dataFooterValue = $(value).find('.shopping-cart-widget-footer');
									var $itemSelector = $(key).find('.woocommerce-mini-cart-item[data-key="' + itemID + '"]');

									if (!data.cart_hash) {
										$(key).replaceWith(value);
									} else {
										$itemSelector.replaceWith(dataItemValue);
										$('.shopping-cart-widget-footer').replaceWith(dataFooterValue);
									}
								} else {
									$(key).replaceWith(value);
								}
							});

							if (woodmartThemeModule.supports_html5_storage) {
								sessionStorage.setItem(fragment_name, JSON.stringify(data.fragments));
								localStorage.setItem(cart_hash_key, data.cart_hash);
								sessionStorage.setItem(cart_hash_key, data.cart_hash);

								if (data.cart_hash) {
									sessionStorage.setItem('wc_cart_created', (new Date()).getTime());
								}
							}
						}
					},
					dataType: 'json',
					method  : 'GET'
				});
			}, 500);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.miniCartQuantity();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.onRemoveFromCart = function() {
		if ('no' === woodmart_settings.woocommerce_ajax_add_to_cart) {
			return;
		}

		woodmartThemeModule.$document.on('click', '.widget_shopping_cart .remove', function(e) {
			e.preventDefault();
			$(this).parent().addClass('removing-process');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.onRemoveFromCart();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.product360Button = function() {
		if ('undefined' === typeof $.fn.magnificPopup) {
			return;
		}

		$('.product-360-button a').magnificPopup({
			type           : 'inline',
			mainClass      : 'mfp-fade',
			preloader      : false,
			tClose         : woodmart_settings.close,
			tLoading       : woodmart_settings.loading,
			fixedContentPos: false,
			callbacks      : {
				open: function() {
					woodmartThemeModule.$window.trigger('resize');
				}
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.product360Button();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.productAccordion = function() {
		var $accordion = $('.wc-tabs-wrapper');
		var time = 300;
		var hash = window.location.hash;
		var url = window.location.href;

		if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews') {
			$accordion.find('.tab-title-reviews').addClass('active');
		} else if (url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {
			$accordion.find('.tab-title-reviews').addClass('active');
		} else {
			$accordion.find('.wd-accordion-title').first().addClass('active');
		}

		$('.woocommerce-review-link').on('click', function() {
			$('.wd-accordion-title.tab-title-reviews').click();
		});

		$accordion.on('click', '.wd-accordion-title', function(e) {
			e.preventDefault();

			var $this  = $(this),
			    $panel = $this.siblings('.woocommerce-Tabs-panel');

			var currentIndex = $this.parent().index();
			var oldIndex = $this.parent().siblings().find('.active').parent('.wd-tab-wrapper').index();

			if ($this.hasClass('active')) {
				oldIndex = currentIndex;
				$this.removeClass('active');
				$panel.stop().slideUp(time);
			} else {
				$accordion.find('.wd-accordion-title').removeClass('active');
				$accordion.find('.woocommerce-Tabs-panel').slideUp();
				$this.addClass('active');
				$panel.stop().slideDown(time);
			}

			if (currentIndex === -1) {
				oldIndex = currentIndex;
			}

			woodmartThemeModule.$window.trigger('resize');

			setTimeout(function() {
				woodmartThemeModule.$window.trigger('resize');

				if (woodmartThemeModule.$window.width() < 1024 && currentIndex > oldIndex) {
					var $header = $('.sticky-header');
					var headerHeight = $header.length > 0 ? $header.outerHeight() : 0;
					$('html, body').animate({
						scrollTop: $this.offset().top - $this.outerHeight() - headerHeight - 50
					}, 500);
				}
			}, time);

			woodmartThemeModule.$document.trigger('wood-images-loaded');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.productAccordion();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.productFilters();
	});

	$.each([
		'frontend/element_ready/wd_product_filters.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.productFilters();
		});
	});

	woodmartThemeModule.productFilters = function() {
		var removeValue = function($mainInput, currentVal) {
			if ($mainInput.length === 0) {
				return;
			}

			var mainInputVal = $mainInput.val();

			if (mainInputVal.indexOf(',') > 0) {
				$mainInput.val(mainInputVal.replace(',' + currentVal, '').replace(currentVal + ',', ''));
			} else {
				$mainInput.val(mainInputVal.replace(currentVal, ''));
			}
		};

		$('.wd-pf-checkboxes li > .pf-value').on('click', function(e) {
			e.preventDefault();
			var $this = $(this);
			var $li = $this.parent();
			var $widget = $this.parents('.wd-pf-checkboxes');
			var $mainInput = $widget.find('.result-input');
			var $results = $widget.find('.wd-pf-results');

			var multiSelect = $widget.hasClass('multi_select');
			var mainInputVal = $mainInput.val();
			var currentText = $this.data('title');
			var currentVal = $this.data('val');

			if (multiSelect) {
				if (!$li.hasClass('pf-active')) {
					if (mainInputVal === '') {
						$mainInput.val(currentVal);
					} else {
						$mainInput.val(mainInputVal + ',' + currentVal);
					}

					$results.prepend('<li class="selected-value" data-title="' + currentVal + '">' + currentText + '</li>');
					$li.addClass('pf-active');
				} else {
					removeValue($mainInput, currentVal);
					$results.find('li[data-title="' + currentVal + '"]').remove();
					$li.removeClass('pf-active');
				}
			} else {
				if (!$li.hasClass('pf-active')) {
					$mainInput.val(currentVal);
					$results.find('.selected-value').remove();
					$results.prepend('<li class="selected-value" data-title="' + currentVal + '">' + currentText + '</li>');
					$li.parents('.wd-scroll-content').find('.pf-active').removeClass('pf-active');
					$li.addClass('pf-active');
				} else {
					$mainInput.val('');
					$results.find('.selected-value').remove();
					$li.removeClass('pf-active');
				}
			}
		});

		//Label clear
		var $checkboxes = $('.wd-pf-checkboxes');
		$checkboxes.on('click', '.selected-value', function() {
			var $this = $(this);
			var $widget = $this.parents('.wd-pf-checkboxes');
			var $mainInput = $widget.find('.result-input');
			var currentVal = $this.data('title');

			//Price filter clear
			if (currentVal === 'price-filter') {
				var min = $this.data('min');
				var max = $this.data('max');
				var $slider = $widget.find('.price_slider_widget');
				$slider.slider('values', 0, min);
				$slider.slider('values', 1, max);
				$widget.find('.min_price').val('');
				$widget.find('.max_price').val('');
				woodmartThemeModule.$body.trigger('filter_price_slider_slide', [
					min,
					max,
					min,
					max,
					$slider
				]);
				return;
			}

			removeValue($mainInput, currentVal);
			$widget.find('.pf-value[data-val="' + currentVal + '"]').parent().removeClass('pf-active');
			$this.remove();
		});

		//Checkboxes value dropdown
		$checkboxes.each(function() {
			var $this = $(this);
			var $btn = $this.find('.wd-pf-title');
			var $list = $btn.siblings('.wd-pf-dropdown');
			var multiSelect = $this.hasClass('multi_select');

			$btn.on('click', function(e) {
				var target = e.target;

				if ($(target).is($btn.find('.selected-value'))) {
					return;
				}

				if (!$this.hasClass('opened')) {
					$this.addClass('opened');
					$list.slideDown(100);
					setTimeout(function() {
						woodmartThemeModule.$document.trigger('wdProductFiltersOpened');
					}, 300);
				} else {
					close();
				}
			});

			woodmartThemeModule.$document.on('click', function(e) {
				var target = e.target;

				if ($this.hasClass('opened') && (multiSelect && !$(target).is($this) && !$(target).parents().is($this)) || (!multiSelect && !$(target).is($btn) && !$(target).parents().is($btn))) {
					close();
				}
			});

			var close = function() {
				$this.removeClass('opened');
				$list.slideUp(100);
			};
		});

		var removeEmptyValues = function($selector) {
			$selector.find('.wd-pf-checkboxes').each(function() {
				var $this = $(this);

				if (!$this.find('input[type="hidden"]').val()) {
					$this.find('input[type="hidden"]').remove();
				}
			});
		};

		var changeFormAction = function($form) {
			var activeCat = $form.find('.wd-pf-categories .pf-active .pf-value');

			if (activeCat.length > 0) {
				$form.attr('action', activeCat.attr('href'));
			}
		};

		//Price slider init
		woodmartThemeModule.$body.on('filter_price_slider_create filter_price_slider_slide', function(event, min, max, minPrice, maxPrice, $slider) {
			var minHtml = accounting.formatMoney(min, {
				symbol   : woocommerce_price_slider_params.currency_format_symbol,
				decimal  : woocommerce_price_slider_params.currency_format_decimal_sep,
				thousand : woocommerce_price_slider_params.currency_format_thousand_sep,
				precision: woocommerce_price_slider_params.currency_format_num_decimals,
				format   : woocommerce_price_slider_params.currency_format
			});

			var maxHtml = accounting.formatMoney(max, {
				symbol   : woocommerce_price_slider_params.currency_format_symbol,
				decimal  : woocommerce_price_slider_params.currency_format_decimal_sep,
				thousand : woocommerce_price_slider_params.currency_format_thousand_sep,
				precision: woocommerce_price_slider_params.currency_format_num_decimals,
				format   : woocommerce_price_slider_params.currency_format
			});

			$slider.siblings('.filter_price_slider_amount').find('span.from').html(minHtml);
			$slider.siblings('.filter_price_slider_amount').find('span.to').html(maxHtml);

			var $results = $slider.parents('.wd-pf-checkboxes').find('.wd-pf-results');
			var value = $results.find('.selected-value');

			if (min === minPrice && max === maxPrice) {
				value.remove();
			} else {
				if (value.length === 0) {
					$results.prepend('<li class="selected-value" data-title="price-filter" data-min="' + minPrice + '" data-max="' + maxPrice + '">' + minHtml + ' - ' + maxHtml + '</li>');
				} else {
					value.html(minHtml + ' - ' + maxHtml);
				}
			}

			woodmartThemeModule.$body.trigger('price_slider_updated', [
				min,
				max
			]);
		});

		$('.wd-pf-price-range .price_slider_widget').each(function() {
			var $this = $(this);
			var $minInput = $this.siblings('.filter_price_slider_amount').find('.min_price');
			var $maxInput = $this.siblings('.filter_price_slider_amount').find('.max_price');
			var minPrice = parseInt($minInput.data('min'));
			var maxPrice = parseInt($maxInput.data('max'));
			var currentMinPrice = parseInt($minInput.val());
			var currentMaxPrice = parseInt($maxInput.val());

			$('.price_slider_widget, .price_label').show();

			$this.slider({
				range  : true,
				animate: true,
				min    : minPrice,
				max    : maxPrice,
				values : [
					currentMinPrice,
					currentMaxPrice
				],
				create : function() {
					if (currentMinPrice === minPrice && currentMaxPrice === maxPrice) {
						$minInput.val('');
						$maxInput.val('');
					}

					woodmartThemeModule.$body.trigger('filter_price_slider_create', [
						currentMinPrice,
						currentMaxPrice,
						minPrice,
						maxPrice,
						$this
					]);
				},
				slide  : function(event, ui) {
					if (ui.values[0] === minPrice && ui.values[1] === maxPrice) {
						$minInput.val('');
						$maxInput.val('');
					} else {
						$minInput.val(ui.values[0]);
						$maxInput.val(ui.values[1]);
					}

					woodmartThemeModule.$body.trigger('filter_price_slider_slide', [
						ui.values[0],
						ui.values[1],
						minPrice,
						maxPrice,
						$this
					]);
				},
				change : function(event, ui) {
					woodmartThemeModule.$body.trigger('price_slider_change', [
						ui.values[0],
						ui.values[1]
					]);
				}
			});
		});

		//Submit filter form
		$('.wd-product-filters').one('click', '.wd-pf-btn button', function() {
			var $this = $(this);
			var $form = $this.parents('.wd-product-filters');
			removeEmptyValues($form);
			changeFormAction($form);

			if (!woodmartThemeModule.$body.hasClass('woodmart-ajax-shop-on') || typeof ($.fn.pjax) == 'undefined' || !$form.hasClass('with-ajax')) {
				return;
			}

			$.pjax({
				container: '.main-page-wrapper',
				timeout  : woodmart_settings.pjax_timeout,
				url      : $form.attr('action'),
				data     : $form.serialize(),
				scrollTo : false,
				renderCallback: function(context, html, afterRender) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(html, function(html) {
						context.html(html);
						afterRender();
						woodmartThemeModule.$document.trigger('wdShopPageInit');
						woodmartThemeModule.$document.trigger('wood-images-loaded');
					});
				}
			});

			$this.prop('disabled', true);
		});

		//Create labels after ajax
		$('.wd-pf-checkboxes .pf-active > .pf-value').each(function() {
			var $this = $(this);
			var resultsWrapper = $this.parents('.wd-pf-checkboxes').find('.wd-pf-results');

			resultsWrapper.prepend('<li class="selected-value" data-title="' + $this.data('val') + '">' + $this.data('title') + '</li>');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.productFilters();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdUpdateWishlist wdArrowsLoadProducts wdLoadMoreLoadProducts wdProductsTabsLoaded wdArrowsLoadProducts', function() {
		woodmartThemeModule.productHover();
	});

	woodmartThemeModule.wcTabsHoverFix = function() {
		$('.wc-tabs > li').on('click', function() {
			woodmartThemeModule.productHover();
		});
	};

	woodmartThemeModule.$document.on('wdProductMoreDescriptionOpen', function(event, $product) {
		woodmartThemeModule.productHoverRecalc($product);
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.productHover();
		});
	});

	woodmartThemeModule.productHoverRecalc = function($el) {
		if ($el.hasClass('product-in-carousel')) {
			return;
		}

		var heightHideInfo = $el.find('.fade-in-block').outerHeight();

		$el.find('.content-product-imagin').css({
			marginBottom: -heightHideInfo
		});

		$el.addClass('hover-ready');
	};

	woodmartThemeModule.productHover = function() {
		var $hoverBase = $('.wd-hover-base');

		if (woodmartThemeModule.windowWidth <= 1024) {
			$hoverBase.on('click', function(e) {
				var $this = $(this);
				var hoverClass = 'state-hover';
				if (!$this.hasClass(hoverClass) && woodmart_settings.base_hover_mobile_click === 'no') {
					e.preventDefault();
					$('.' + hoverClass).removeClass(hoverClass);
					$this.addClass(hoverClass);
				}
			});

			woodmartThemeModule.$document.on('click touchstart', function(e) {
				if ($(e.target).closest('.state-hover').length === 0) {
					$('.state-hover').removeClass('state-hover');
				}
			});
		}

		$hoverBase.on('mouseenter mousemove touchstart', function() {
			var $product = $(this);
			var $content = $product.find('.xts-more-desc');

			if ($content.hasClass('wd-height-calculated')) {
				return;
			}

			$product.imagesLoaded(function() {
				woodmartThemeModule.productHoverRecalc($product);
			});

			$content.addClass('wd-height-calculated');
		});

		function productHolderWidth($holder) {
			if ($holder.data('column_width')) {
				return;
			}

			var holderWidth = $holder.outerWidth();
			var columns = $holder.data('columns');
			var columnWidth = holderWidth / columns;

			$holder.data('column_width', columnWidth);
		}

		$('.wd-products-holder').on('mouseenter mousemove touchstart', function() {
			productHolderWidth($(this));
		});

		$hoverBase.on('mouseenter mousemove touchstart', function() {
			if (!woodmart_settings.hover_width_small) {
				return;
			}

			var $this = $(this);

			productHolderWidth($this.parent('.wd-products-holder'));

			var columnWidth = $this.parent('.wd-products-holder').data('column_width');

			if (!columnWidth) {
				return;
			}

			if (255 > columnWidth || woodmartThemeModule.windowWidth <= 1024) {
				$this.find('.wd-add-btn').parent().addClass('wd-add-small-btn');
				$this.find('.wd-add-btn').removeClass('wd-add-btn-replace').addClass('wd-action-btn wd-style-icon wd-add-cart-icon');
			} else if (woodmartThemeModule.$body.hasClass('catalog-mode-on') || woodmartThemeModule.$body.hasClass('login-see-prices')) {
				$this.find('.wd-bottom-actions .wd-action-btn').removeClass('wd-style-icon').addClass('wd-style-text');
			}

			woodmartThemeModule.$document.trigger('wdProductBaseHoverIconsResize');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.productHover();
		woodmartThemeModule.wcTabsHoverFix();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.productImages = function() {
		var currentImage,
		    $productGallery   = $('.woocommerce-product-gallery'),
		    $mainImages       = $('.woocommerce-product-gallery__wrapper'),
		    $thumbs           = $productGallery.find('.thumbnails'),
		    PhotoSwipeTrigger = '.woodmart-show-product-gallery';

		$thumbs.addClass('thumbnails-ready');

		if ($productGallery.hasClass('image-action-popup')) {
			PhotoSwipeTrigger += ', .woocommerce-product-gallery__image a';
		}

		$productGallery.on('click', '.woocommerce-product-gallery__image a', function(e) {
			e.preventDefault();
		});

		$productGallery.on('click', PhotoSwipeTrigger, function(e) {
			e.preventDefault();

			currentImage = $(this).attr('href');

			var items = getProductItems();

			woodmartThemeModule.callPhotoSwipe(getCurrentGalleryIndex(e), items);
		});

		$thumbs.on('click', '.image-link', function(e) {
			e.preventDefault();
		});

		var getCurrentGalleryIndex = function(e) {
			if ($mainImages.hasClass('owl-carousel')) {
				return $mainImages.find('.owl-item.active').index();
			} else {
				return $(e.currentTarget).parent().parent().index();
			}
		};

		var getProductItems = function() {
			var items = [];

			$mainImages.find('figure a img').each(function() {
				var $this = $(this);
				var src     = $this.attr('data-large_image'),
				    width   = $this.attr('data-large_image_width'),
				    height  = $this.attr('data-large_image_height'),
				    caption = $this.data('caption');

				items.push({
					src  : src,
					w    : width,
					h    : height,
					title: (woodmart_settings.product_images_captions === 'yes') ? caption : false
				});

			});

			return items;
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.productImages();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdReplaceMainGallery', function () {
		woodmartThemeModule.productImagesGallery();
	});

	woodmartThemeModule.productImagesGallery = function() {
		var $mainGallery = $('.woocommerce-product-gallery__wrapper:not(.quick-view-gallery)');
		var $thumbs = $('.images .thumbnails');
		var $mainOwl = $('.woocommerce-product-gallery__wrapper');
		var thumbs_position = woodmart_settings.product_gallery.thumbs_slider.position;

		$thumbs.addClass('thumbnails-ready');

		if (woodmart_settings.product_gallery.images_slider) {
			if (woodmart_settings.product_slider_auto_height === 'yes') {
				$('.product-images').imagesLoaded(function() {
					initMainGallery();
				});
			} else {
				initMainGallery();
			}
		} else if (woodmartThemeModule.$window.width() <= 1024 && (thumbs_position === 'bottom_combined' || thumbs_position === 'bottom_column' || thumbs_position === 'bottom_grid')) {
			initMainGallery();
		}

		if (woodmart_settings.product_gallery.thumbs_slider.enabled && woodmart_settings.product_gallery.images_slider) {
			initThumbnailsMarkup();
			if (woodmart_settings.product_gallery.thumbs_slider.position === 'left' && woodmartThemeModule.$body.width() > 1024 && typeof ($.fn.slick) != 'undefined') {
				initThumbnailsVertical();
			} else {
				initThumbnailsHorizontal();
			}
		}

		function initMainGallery() {
			if ('undefined' === typeof $.fn.owlCarousel) {
				return;
			}

			$mainGallery.trigger('destroy.owl.carousel');
			$mainGallery.addClass('owl-carousel').owlCarousel(woodmartThemeModule.mainCarouselArg);
			woodmartThemeModule.$document.trigger('wood-images-loaded');
		}

		function initThumbnailsMarkup() {
			var markup = '';

			$mainGallery.find('.woocommerce-product-gallery__image').each(function() {
				var $this = $(this);
				var image = $this.data('thumb'),
				    alt   = $this.find('a img').attr('alt'),
				    title = $this.find('a img').attr('title');

				if ( ! title ) {
					title = $this.find('a picture').attr('title');
				}

				markup += '<div class="product-image-thumbnail"><img alt="' + alt + '" title="' + title + '" src="' + image + '" /></div>';
			});

			if ($thumbs.hasClass('slick-slider')) {
				$thumbs.slick('unslick');
			} else if ($thumbs.hasClass('owl-carousel')) {
				$thumbs.trigger('destroy.owl.carousel');
			}

			$thumbs.empty();
			$thumbs.append(markup);
		}

		function initThumbnailsVertical() {
			$thumbs.slick({
				slidesToShow   : woodmart_settings.product_gallery.thumbs_slider.items.vertical_items,
				slidesToScroll : woodmart_settings.product_gallery.thumbs_slider.items.vertical_items,
				vertical       : true,
				verticalSwiping: true,
				infinite       : false
			});

			$thumbs.on('click', '.product-image-thumbnail', function() {
				$mainOwl.trigger('to.owl.carousel', $(this).index());
			});

			$mainOwl.on('changed.owl.carousel', function(e) {
				var i = e.item.index;

				$thumbs.slick('slickGoTo', i);
				$thumbs.find('.active-thumb').removeClass('active-thumb');
				$thumbs.find('.product-image-thumbnail').eq(i).addClass('active-thumb');
			});

			$thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');

			$thumbs.imagesLoaded(function () {
				$thumbs.slick('setPosition');
			});
		}

		function initThumbnailsHorizontal() {
			if ('undefined' === typeof $.fn.owlCarousel) {
				return;
			}

			$thumbs.addClass('owl-carousel').owlCarousel({
				rtl       : woodmartThemeModule.$body.hasClass('rtl'),
				items     : woodmart_settings.product_gallery.thumbs_slider.items.desktop,
				responsive: {
					1025: {
						items: woodmart_settings.product_gallery.thumbs_slider.items.desktop
					},
					769 : {
						items: woodmart_settings.product_gallery.thumbs_slider.items.tablet_landscape
					},
					577 : {
						items: woodmart_settings.product_gallery.thumbs_slider.items.tablet
					},
					0   : {
						items: woodmart_settings.product_gallery.thumbs_slider.items.mobile
					}
				},
				dots      : false,
				nav       : true,
				navText   : false,
				navClass : ['owl-prev wd-btn-arrow', 'owl-next wd-btn-arrow'],
			});

			var $thumbsOwl = $thumbs.owlCarousel();

			$thumbs.on('mouseup', '.owl-item', function() {
				var i = $(this).index();

				$thumbsOwl.trigger('to.owl.carousel', i);
				$mainOwl.trigger('to.owl.carousel', i);
			});

			$mainOwl.on('changed.owl.carousel', function(e) {
				var i = e.item.index;

				$thumbsOwl.trigger('to.owl.carousel', i);
				$thumbs.find('.active-thumb').removeClass('active-thumb');
				$thumbs.find('.product-image-thumbnail').eq(i).addClass('active-thumb');
			});

			$thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.productImagesGallery();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdLoadMoreLoadProducts wdArrowsLoadProducts wdProductsTabsLoaded wdUpdateWishlist', function () {
		woodmartThemeModule.productMoreDescription();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.productMoreDescription();
		});
	});

	woodmartThemeModule.productMoreDescription = function() {
		$('.wd-hover-base').on('mouseenter touchstart', function() {
			var $content = $(this).find('.wd-more-desc');
			var $inner = $content.find('.wd-more-desc-inner');
			var $moreBtn = $content.find('.wd-more-desc-btn');

			if ($content.hasClass('wd-more-desc-calculated')) {
				return;
			}

			var contentHeight = $content.outerHeight();
			var innerHeight = $inner.outerHeight();
			var delta = innerHeight - contentHeight;

			if (delta > 30) {
				$moreBtn.addClass('wd-shown');
			} else if (delta > 0) {
				$content.css('height', contentHeight + delta);
			}

			$content.addClass('wd-more-desc-calculated');
		});

		woodmartThemeModule.$body.on('click', '.wd-more-desc-btn', function(e) {
			e.preventDefault();
			var $this = $(this);

			$this.parent().addClass('wd-more-desc-full');

			woodmartThemeModule.$document.trigger('wdProductMoreDescriptionOpen', [$this.parents('.wd-hover-base')]);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.productMoreDescription();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.productVideo = function() {
		if ('undefined' === typeof $.fn.magnificPopup) {
			return;
		}

		$('.product-video-button a').magnificPopup({
			tClose         : woodmart_settings.close,
			tLoading       : woodmart_settings.loading,
			type           : 'iframe',
			iframe         : {
				patterns: {
					youtube: {
						index: 'youtube.com/',
						id   : 'v=',
						src  : '//www.youtube.com/embed/%id%?rel=0&autoplay=1'
					}
				}
			},
			preloader      : false,
			fixedContentPos: false
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.productVideo();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdProductsTabsLoaded', function() {
		woodmartThemeModule.productsLoadMore();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.productsLoadMore();
		});
	});

	woodmartThemeModule.productsLoadMore = function() {
		var process = false,
		    intervalID;

		$('.wd-products-element').each(function() {
			var $this = $(this),
			    cache = [],
			    inner = $this.find('.wd-products-holder');

			if (!inner.hasClass('pagination-arrows')) {
				return;
			}

			cache[1] = {
				items : inner.html(),
				status: 'have-posts'
			};

			var body        = woodmartThemeModule.$body,
			    btnWrap     = $this.find('.products-footer'),
			    btnLeft     = btnWrap.find('.wd-products-load-prev'),
			    btnRight    = btnWrap.find('.wd-products-load-next'),
			    loadWrapp   = $this.find('.wd-products-loader'),
			    scrollTop,
			    holderTop,
			    btnLeftOffset,
			    btnRightOffset,
			    holderBottom,
			    holderHeight,
			    holderWidth,
			    btnsHeight,
			    offsetArrow = 50,
			    offset,
			    windowWidth;

			if (body.hasClass('rtl')) {
				btnLeft = btnRight;
				btnRight = btnWrap.find('.wd-products-load-prev');
			}

			woodmartThemeModule.$window.on('scroll', function() {
				buttonsPos();
			});

			setTimeout(function() {
				buttonsPos();
			}, 500);

			function buttonsPos() {
				offset = woodmartThemeModule.$window.height() / 2;
				windowWidth = woodmartThemeModule.$window.outerWidth(true);
				holderWidth = $this.outerWidth(true);
				scrollTop = woodmartThemeModule.$window.scrollTop();
				holderTop = $this.offset().top - offset;
				btnLeftOffset = $this.offset().left - offsetArrow;
				btnRightOffset = holderWidth + $this.offset().left + offsetArrow - btnRight.outerWidth();
				btnsHeight = btnLeft.outerHeight();
				holderHeight = $this.height() - btnsHeight;
				holderBottom = holderTop + holderHeight;

				if (woodmartThemeModule.$window.width() <= 1024) {
					btnLeftOffset = btnLeftOffset + 35;
					btnRightOffset = btnRightOffset - 35;
				}

				btnLeft.css({
					'left': btnLeftOffset + 'px'
				});

				btnRight.css({
					'left': btnRightOffset + 'px'
				});

				if (scrollTop < holderTop || scrollTop > holderBottom) {
					btnWrap.removeClass('show-arrow');
					loadWrapp.addClass('hidden-loader');
				} else {
					btnWrap.addClass('show-arrow');
					loadWrapp.removeClass('hidden-loader');
				}
			}

			$this.find('.wd-products-load-prev, .wd-products-load-next').on('click', function(e) {
				e.preventDefault();
				var $this = $(this);

				if (process || $this.hasClass('disabled')) {
					return;
				}

				process = true;

				clearInterval(intervalID);

				var holder   = $this.parent().parent().prev(),
				    next     = $this.parent().find('.wd-products-load-next'),
				    prev     = $this.parent().find('.wd-products-load-prev'),
				    atts     = holder.data('atts'),
				    action   = 'woodmart_get_products_shortcode',
				    ajaxurl  = woodmart_settings.ajaxurl,
				    dataType = 'json',
				    method   = 'POST',
				    paged    = holder.attr('data-paged');

				paged++;

				if ($this.hasClass('wd-products-load-prev')) {
					if (paged < 2) {
						return;
					}

					paged = paged - 2;
				}

				loadProducts('arrows', atts, ajaxurl, action, dataType, method, paged, holder, $this, cache, function(data) {
					var isBorderedGrid = holder.hasClass('products-bordered-grid');

					if (!isBorderedGrid) {
						holder.addClass('wd-animated-products');
					}

					if (data.items.length) {
						holder.html(data.items).attr('data-paged', paged);
						holder.imagesLoaded().progress(function() {
							holder.parent().trigger('recalc');
						});

						woodmartThemeModule.$document.trigger('wood-images-loaded');
						woodmartThemeModule.$document.trigger('wdArrowsLoadProducts');
					}

					if (woodmartThemeModule.$window.width() < 768) {
						$('html, body').stop().animate({
							scrollTop: holder.offset().top - 150
						}, 400);
					}

					if (!isBorderedGrid) {
						var iter = 0;

						intervalID = setInterval(function() {
							holder.find('.product-grid-item').eq(iter).addClass('wd-animated');
							iter++;
						}, 100);
					}

					if (paged > 1) {
						prev.removeClass('disabled');
					} else {
						prev.addClass('disabled');
					}

					if (data.status === 'no-more-posts') {
						next.addClass('disabled');
					} else {
						next.removeClass('disabled');
					}
				});
			});
		});

		woodmartThemeModule.clickOnScrollButton(woodmartThemeModule.shopLoadMoreBtn, false, woodmart_settings.infinit_scroll_offset);

		woodmartThemeModule.$document.off('click', '.wd-products-load-more').on('click', '.wd-products-load-more', function(e) {
			e.preventDefault();

			if (process) {
				return;
			}

			process = true;

			var $this    = $(this),
			    holder   = $this.parent().siblings('.wd-products-holder'),
			    source   = holder.data('source'),
			    action   = 'woodmart_get_products_' + source,
			    ajaxurl  = woodmart_settings.ajaxurl,
			    dataType = 'json',
			    method   = 'POST',
			    atts     = holder.data('atts'),
			    paged    = holder.data('paged');

			paged++;

			if (source === 'main_loop') {
				ajaxurl = $(this).attr('href');
				method = 'GET';
			}

			loadProducts('load-more', atts, ajaxurl, action, dataType, method, paged, holder, $this, [], function(data) {
				if (data.items.length) {
					if (holder.hasClass('grid-masonry')) {
						isotopeAppend(holder, data.items);
					} else {
						holder.append(data.items);
					}

					if (data.status !== 'no-more-posts') {
						holder.imagesLoaded().progress(function() {
							woodmartThemeModule.clickOnScrollButton(woodmartThemeModule.shopLoadMoreBtn, true, woodmart_settings.infinit_scroll_offset);
						});
					}

					woodmartThemeModule.$document.trigger('wood-images-loaded');
					woodmartThemeModule.$document.trigger('wdLoadMoreLoadProducts');

					holder.data('paged', paged);
				}

				if (source === 'main_loop') {
					$this.attr('href', data.nextPage);

					if (data.status === 'no-more-posts') {
						$this.hide().remove();
					}
				}

				if (data.status === 'no-more-posts') {
					$this.hide();
				}
			});
		});

		var loadProducts = function(btnType, atts, ajaxurl, action, dataType, method, paged, holder, btn, cache, callback) {
			var data = {
				atts    : atts,
				paged   : paged,
				action  : action,
				woo_ajax: 1
			};

			if (method === 'GET') {
				ajaxurl = woodmartThemeModule.removeURLParameter(ajaxurl, 'loop');
				ajaxurl = woodmartThemeModule.removeURLParameter(ajaxurl, 'woo_ajax');
			}

			if (cache[paged]) {
				holder.addClass('loading');

				setTimeout(function() {
					callback(cache[paged]);
					holder.removeClass('loading');
					process = false;
				}, 300);

				return;
			}

			if (btnType === 'arrows') {
				holder.addClass('loading').parent().addClass('element-loading');
			}

			btn.addClass('loading');

			if (action === 'woodmart_get_products_main_loop') {
				var loop = holder.find('.product').last().data('loop');
				data = {
					loop    : loop,
					woo_ajax: 1
				};
			}

			$.ajax({
				url     : ajaxurl,
				data    : data,
				dataType: dataType,
				method  : method,
				success : function(data) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(data.items, function(html) {
						data.items = html;
						cache[paged] = data;
						callback(data);
						if ('yes' === woodmart_settings.load_more_button_page_url) {
							window.history.pushState('', '', data.currentPage);
						}
					});
				},
				error   : function() {
					console.log('ajax error');
				},
				complete: function() {
					if (btnType === 'arrows') {
						holder.removeClass('loading').parent().removeClass('element-loading');
					}

					btn.removeClass('loading');
					process = false;
				}
			});
		};

		var isotopeAppend = function(el, items) {
			var $items = $(items);
			el.append($items).isotope('appended', $items);
			el.imagesLoaded().progress(function() {
				el.isotope('layout');
			});
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.productsLoadMore();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.productsTabs();
		});
	});

	woodmartThemeModule.productsTabs = function() {
		var process = false;

		$('.wd-products-tabs').each(function() {
			var $this  = $(this),
			    $inner = $this.find('.wd-tab-content'),
			    cache  = [];

			if ($inner.find('.owl-carousel').length < 1) {
				cache[0] = {
					html: $inner.html()
				};
			}

			$this.find('.products-tabs-title li').on('click', function(e) {
				e.preventDefault();

				var $this = $(this),
				    atts  = $this.data('atts'),
				    index = $this.index();

				if (process || $this.hasClass('active-tab-title')) {
					return;
				}
				process = true;

				loadTab(atts, index, $inner, $this, cache, function(data) {
					if (data.html) {
						woodmartThemeModule.removeDuplicatedStylesFromHTML(data.html, function(html) {
							$inner.html(html);

							$inner.removeClass('loading').parent().removeClass('element-loading');
							$this.removeClass('loading');

							woodmartThemeModule.$document.trigger('wdProductsTabsLoaded');
							woodmartThemeModule.$document.trigger('wood-images-loaded');
						});
					}
				});
			});

			var $nav     = $this.find('.tabs-navigation-wrapper'),
			    $subList = $nav.find('ul'),
			    time     = 300;

			$nav.on('click', '.open-title-menu', function() {
					var $btn = $(this);

					if ($subList.hasClass('list-shown')) {
						$btn.removeClass('toggle-active');
						$subList.stop().slideUp(time).removeClass('list-shown');
					} else {
						$btn.addClass('toggle-active');
						$subList.addClass('list-shown');
						setTimeout(function() {
							woodmartThemeModule.$body.one('click', function(e) {
								var target = e.target;

								if (!$(target).is('.tabs-navigation-wrapper') && !$(target).parents().is('.tabs-navigation-wrapper')) {
									$btn.removeClass('toggle-active');
									$subList.removeClass('list-shown');
									return false;
								}
							});
						}, 10);
					}
				})
				.on('click', 'li', function() {
					var $btn = $nav.find('.open-title-menu'),
					    text = $(this).text();

					if ($subList.hasClass('list-shown')) {
						$btn.removeClass('toggle-active').text(text);
						$subList.removeClass('list-shown');
					}
				});
		});

		var loadTab = function(atts, index, holder, btn, cache, callback) {
			btn.parent().find('.active-tab-title').removeClass('active-tab-title');
			btn.addClass('active-tab-title');

			if (cache[index]) {
				holder.addClass('loading').parent().addClass('element-loading');
				setTimeout(function() {
					callback(cache[index]);
					process = false;
				}, 300);
				return;
			}

			holder.addClass('loading').parent().addClass('element-loading');
			btn.addClass('loading');

			$.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : {
					atts  : atts,
					action: 'woodmart_get_products_tab_shortcode'
				},
				dataType: 'json',
				method  : 'POST',
				success : function(data) {
					cache[index] = data;
					callback(data);
				},
				error   : function() {
					console.log('ajax error');
				},
				complete: function() {
					process = false;
				}
			});
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.productsTabs();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.quickShop();
		});
	});

	woodmartThemeModule.quickShop = function() {
		if ('no' === woodmart_settings.quick_shop) {
			return;
		}

		var btnSelector = '.product-grid-item.product-type-variable .add_to_cart_button';

		woodmartThemeModule.$document.on('click', btnSelector, function(e) {
				e.preventDefault();

				var $this        = $(this),
				    $product     = $this.parents('.product').first(),
				    $content     = $product.find('.quick-shop-form'),
				    id           = $product.data('id'),
				    loadingClass = 'btn-loading';

				if ($this.hasClass(loadingClass)) {
					return;
				}

				// Simply show quick shop form if it is already loaded with AJAX previously
				if ($product.hasClass('quick-shop-loaded')) {
					$product.addClass('quick-shop-shown');
					woodmartThemeModule.$body.trigger('woodmart-quick-view-displayed');
					return;
				}

				$this.addClass(loadingClass);
				$product.addClass('wd-loading-quick-shop');

				$.ajax({
					url     : woodmart_settings.ajaxurl,
					data    : {
						action: 'woodmart_quick_shop',
						id    : id
					},
					method  : 'get',
					success : function(data) {
						$content.append(data);

						initVariationForm($product);
						woodmartThemeModule.$document.trigger('wdQuickShopSuccess');
					},
					complete: function() {
						setTimeout(function() {
							$this.removeClass(loadingClass);
							$product.removeClass('wd-loading-quick-shop');
							$product.addClass('quick-shop-shown quick-shop-loaded');
							woodmartThemeModule.$body.trigger('woodmart-quick-view-displayed');
						}, 100);
					}
				});
			})
			.on('click', '.quick-shop-close', function(e) {
				e.preventDefault();

				var $this    = $(this),
				    $product = $this.parents('.product');

				$product.removeClass('quick-shop-shown');
			});

		woodmartThemeModule.$body.on('added_to_cart', function() {
			$('.product').removeClass('quick-shop-shown');
		});

		function initVariationForm($product) {
			$product.find('.variations_form').wc_variation_form().find('.variations select:eq(0)').change();
			$product.find('.variations_form').trigger('wc_variation_form');
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.quickShop();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.quickViewInit();
		});
	});

	woodmartThemeModule.quickViewInit = function() {
		woodmartThemeModule.$document.on('click', '.open-quick-view', function(e) {
			e.preventDefault();

			if ($('.open-quick-view').hasClass('loading')) {
				return true;
			}

			var $this     = $(this),
			    productId = $this.data('id'),
			    loopName  = $this.data('loop-name'),
			    loop      = $this.data('loop'),
			    prev      = '',
			    next      = '',
			    loopBtns  = $('.quick-view').find('[data-loop-name="' + loopName + '"]');

			$this.addClass('loading');

			if (typeof loopBtns[loop - 1] != 'undefined') {
				prev = loopBtns.eq(loop - 1).addClass('quick-view-prev');
				prev = $('<div>').append(prev.clone()).html();
			}

			if (typeof loopBtns[loop + 1] != 'undefined') {
				next = loopBtns.eq(loop + 1).addClass('quick-view-next');
				next = $('<div>').append(next.clone()).html();
			}

			woodmartThemeModule.quickViewLoad(productId, $this, prev, next);
		});
	};

	woodmartThemeModule.quickViewCarousel = function() {
		if ('undefined' === typeof $.fn.owlCarousel) {
			return;
		}

		var $quickViewCarousel = $('.product-quick-view .woocommerce-product-gallery__wrapper');

		$quickViewCarousel.trigger('destroy.owl.carousel');
		$quickViewCarousel.addClass('owl-carousel').owlCarousel({
			rtl    : woodmartThemeModule.$body.hasClass('rtl'),
			items  : 1,
			dots   : false,
			nav    : true,
			navText: false,
			navClass : ['owl-prev wd-btn-arrow', 'owl-next wd-btn-arrow'],
		});
	};

	woodmartThemeModule.quickViewLoad = function(id, btn) {
		var data = {
			id    : id,
			action: 'woodmart_quick_view'
		};

		var initPopup = function(data) {
			var items = $(data);

			$.magnificPopup.open({
				items       : {
					src : items,
					type: 'inline'
				},
				tClose      : woodmart_settings.close,
				tLoading    : woodmart_settings.loading,
				removalDelay: 500,
				callbacks   : {
					beforeOpen: function() {
						this.st.mainClass = 'mfp-move-horizontal quick-view-wrapper';
					},
					open      : function() {
						var $form = $('.variations_form');

						$form.each(function() {
							$(this).wc_variation_form().find('.variations select:eq(0)').change();
						});

						$form.trigger('wc_variation_form');
						woodmartThemeModule.$body.trigger('woodmart-quick-view-displayed');
						woodmartThemeModule.$document.trigger('wdQuickViewOpen');
						setTimeout(function() {
							woodmartThemeModule.$document.trigger('wdQuickViewOpen300');
						}, 300);
						woodmartThemeModule.quickViewCarousel();
					}
				}
			});
		};

		$.ajax({
			url     : woodmart_settings.ajaxurl,
			data    : data,
			method  : 'get',
			success : function(data) {
				woodmartThemeModule.removeDuplicatedStylesFromHTML(data, function(data){
					if (woodmart_settings.quickview_in_popup_fix) {
						$.magnificPopup.close();
						setTimeout(function() {
							initPopup(data);
						}, 500);
					} else {
						initPopup(data);
					}
				});
			},
			complete: function() {
				btn.removeClass('loading');
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.quickViewInit();
		woodmartThemeModule.quickViewCarousel();
	});
})(jQuery);

/* global wd_settings */
(function($) {
	woodmartThemeModule.$document.on('wdFiltersOpened wdShopPageInit wdPjaxStart', function () {
		woodmartThemeModule.shopLoader();
	});

	woodmartThemeModule.shopLoader = function() {
		var loaderVerticalPosition = function() {
			var $products = $('.products[data-source="main_loop"], .wd-portfolio-holder[data-source="main_loop"]');
			var $loader = $products.parent().find('.wd-sticky-loader');

			if ($products.length < 1) {
				return;
			}

			var offset = woodmartThemeModule.$window.height() / 2;
			var scrollTop = woodmartThemeModule.$window.scrollTop();
			var holderTop = $products.offset().top - offset + 45;
			var holderHeight = $products.height();
			var holderBottom = holderTop + holderHeight - 170;

			if (scrollTop < holderTop) {
				$loader.addClass('wd-position-top');
				$loader.removeClass('wd-position-stick');
			} else if (scrollTop > holderBottom) {
				$loader.addClass('wd-position-bottom');
				$loader.removeClass('wd-position-stick');
			} else {
				$loader.addClass('wd-position-stick');
				$loader.removeClass('wd-position-top wd-position-bottom');
			}
		};

		woodmartThemeModule.$window.off('scroll.loaderVerticalPosition');

		woodmartThemeModule.$window.on('scroll.loaderVerticalPosition', loaderVerticalPosition);
	};

	$(document).ready(function() {
		woodmartThemeModule.shopLoader();
	});
})(jQuery);
/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdProductsTabsLoaded wdShopPageInit', function () {
		woodmartThemeModule.shopMasonry();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default',
		'frontend/element_ready/wd_products_brands.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.shopMasonry();
		});
	});

	woodmartThemeModule.shopMasonry = function() {
		if (typeof ($.fn.isotope) == 'undefined' || typeof ($.fn.packery) == 'undefined' || typeof ($.fn.imagesLoaded) == 'undefined') {
			return;
		}

		var $container = $('.elements-grid.grid-masonry');
		$container.imagesLoaded(function() {
			$container.isotope({
				isOriginLeft: !woodmartThemeModule.$body.hasClass('rtl'),
				itemSelector: '.category-grid-item, .product-grid-item'
			});
		});

		woodmartThemeModule.$window.on('resize', function() {
			initMasonry();
		});

		initMasonry();

		function initMasonry() {
			var $catsContainer = $('.categories-masonry');
			var colWidth = ($catsContainer.hasClass('categories-style-masonry')) ? '.category-grid-item' : '.col-lg-3.category-grid-item';
			$catsContainer.imagesLoaded(function() {
				$catsContainer.packery({
					resizable   : false,
					isOriginLeft: !woodmartThemeModule.$body.hasClass('rtl'),
					packery     : {
						gutter     : 0,
						columnWidth: colWidth
					},
					itemSelector: '.category-grid-item'
				});
			});
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.shopMasonry();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.shopPageInit();
	});

	woodmartThemeModule.shopPageInit = function() {
		woodmartThemeModule.clickOnScrollButton(woodmartThemeModule.shopLoadMoreBtn, false, woodmart_settings.infinit_scroll_offset);

		$('body > .tooltip').remove();

		woodmartThemeModule.$body.on('updated_wc_div', function() {
			woodmartThemeModule.$document.trigger('wood-images-loaded');
		});

		woodmartThemeModule.$document.trigger('resize.vcRowBehaviour');
	};
})(jQuery);

/* global woodmart_settings */
(function($) {
	$(window).on('elementor/frontend/init', function() {
		if (!elementorFrontend.isEditMode()) {
			return;
		}

		$(window).on('resize', function() {
			woodmartThemeModule.singleProductTabsAccordion();
		});
	});

	woodmartThemeModule.singleProductTabsAccordion = function() {
		var $wcTabs = $('.woocommerce-tabs');
		if (woodmartThemeModule.windowWidth > 1024 || $wcTabs.length <= 0) {
			return;
		}

		$wcTabs.removeClass('tabs-layout-tabs').addClass('tabs-layout-accordion');
		$('.single-product-page').removeClass('tabs-type-tabs').addClass('tabs-type-accordion');
	};

	$(document).ready(function() {
		woodmartThemeModule.singleProductTabsAccordion();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.singleProductTabsCommentsFix = function() {
		var url = window.location.href;
		var hash = window.location.hash;
		var $tabs = woodmartThemeModule.$body.find('.wc-tabs, ul.tabs').first();

		if (!$('.single-product-page').hasClass('reviews-location-separate')) {
			return;
		}

		if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews') {
			$tabs.find('li:first a').click();
		} else if (url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {
			$tabs.find('li:first a').click();
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.singleProductTabsCommentsFix();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function() {
		woodmartThemeModule.sortByWidget();
	});

	woodmartThemeModule.sortByWidget = function() {
		if (!woodmartThemeModule.$body.hasClass('woodmart-ajax-shop-on') || typeof ($.fn.pjax) == 'undefined') {
			return;
		}

		var $wcOrdering = $('.woocommerce-ordering');

		$wcOrdering.on('change', 'select.orderby', function() {
			var $form = $(this).closest('form');

			$form.find('[name="_pjax"]').remove();

			$.pjax({
				container: '.main-page-wrapper',
				timeout  : woodmart_settings.pjax_timeout,
				url      : '?' + $form.serialize(),
				scrollTo : false,
				renderCallback: function(context, html, afterRender) {
					woodmartThemeModule.removeDuplicatedStylesFromHTML(html, function(html) {
						context.html(html);
						afterRender();
						woodmartThemeModule.$document.trigger('wdShopPageInit');
						woodmartThemeModule.$document.trigger('wood-images-loaded');
					});
				}
			});
		});

		$wcOrdering.on('submit', function(e) {
			e.preventDefault(e);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.sortByWidget();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.stickyAddToCart = function() {
		var $trigger = $('form.cart');
		var $stickyBtn = $('.wd-sticky-btn');

		if ($stickyBtn.length <= 0 || $trigger.length <= 0 || (woodmartThemeModule.$window.width() <= 768 && $stickyBtn.hasClass('mobile-off'))) {
			return;
		}

		var summaryOffset = $trigger.offset().top + $trigger.outerHeight();
		var $scrollToTop = $('.scrollToTop');

		var stickyAddToCartToggle = function() {
			var windowScroll = woodmartThemeModule.$window.scrollTop();
			var windowHeight = woodmartThemeModule.$window.height();
			var documentHeight = woodmartThemeModule.$document.height();

			if (summaryOffset < windowScroll && windowScroll + windowHeight !== documentHeight) {
				$stickyBtn.addClass('wd-sticky-btn-shown');
				$scrollToTop.addClass('wd-sticky-btn-shown');

			} else if (windowScroll + windowHeight === documentHeight || summaryOffset > windowScroll) {
				$stickyBtn.removeClass('wd-sticky-btn-shown');
				$scrollToTop.removeClass('wd-sticky-btn-shown');
			}
		};

		stickyAddToCartToggle();

		woodmartThemeModule.$window.on('scroll', stickyAddToCartToggle);

		$('.wd-sticky-add-to-cart').on('click', function(e) {
			e.preventDefault();

			$('html, body').animate({
				scrollTop: $('.elementor-widget-woocommerce-product-title,.summary-inner .product_title').offset().top
			}, 800);
		});

		// Wishlist.
		$('.wd-sticky-btn .wd-wishlist-btn a').on('click', function(e) {
			if (!$(this).hasClass('added')) {
				e.preventDefault();
			}

			$('.summary-inner > .wd-wishlist-btn a').trigger('click');
		});

		woodmartThemeModule.$document.on('added_to_wishlist', function() {
			$('.wd-sticky-btn .wd-wishlist-btn a').addClass('added');
		});

		// Compare.
		$('.wd-sticky-btn .wd-compare-btn a').on('click', function(e) {
			if (!$(this).hasClass('added')) {
				e.preventDefault();
			}

			$('.summary-inner > .wd-compare-btn a').trigger('click');
		});

		woodmartThemeModule.$document.on('added_to_compare', function() {
			$('.wd-sticky-btn .wd-compare-btn a').addClass('added');
		});

		// Quantity.
		$('.wd-sticky-btn-cart .qty').on('change', function() {
			$('.summary-inner .qty').val($(this).val());
		});

		$('.summary-inner .qty').on('change', function() {
			$('.wd-sticky-btn-cart .qty').val($(this).val());
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.stickyAddToCart();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdHeaderBuilderInited', function () {
		woodmartThemeModule.stickyDetails();
	});

	woodmartThemeModule.stickyDetails = function() {
		if (!woodmartThemeModule.$body.hasClass('woodmart-product-sticky-on') || woodmartThemeModule.$window.width() <= 1024) {
			return;
		}

		var details = $('.entry-summary');

		details.each(function() {
			var $column = $(this),
			    offset  = 40,
			    $inner  = $column.find('.summary-inner'),
			    $images = $column.parent().find('.product-images-inner');

			$inner.trigger('sticky_kit:detach');
			$images.trigger('sticky_kit:detach');

			if (woodmartThemeModule.$body.hasClass('enable-sticky-header') || $('.whb-sticky-row').length > 0 || $('.whb-sticky-header').length > 0) {
				offset = parseInt(woodmart_settings.sticky_product_details_offset);
			}

			$images.imagesLoaded(function() {
				var diff = $inner.outerHeight() - $images.outerHeight();

				if (diff < -100) {
					$inner.stick_in_parent({
						offset_top: offset
					});
				} else if (diff > 100) {
					$images.stick_in_parent({
						offset_top: offset
					});
				}

				woodmartThemeModule.$window.on('resize', woodmartThemeModule.debounce(function() {
					if (woodmartThemeModule.$window.width() <= 1024) {
						$inner.trigger('sticky_kit:detach');
						$images.trigger('sticky_kit:detach');
					} else if ($inner.outerHeight() < $images.outerHeight()) {
						$inner.stick_in_parent({
							offset_top: offset
						});
					} else {
						$images.stick_in_parent({
							offset_top: offset
						});
					}
				}, 300));
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.stickyDetails();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.stickySidebarBtn();
	});

	woodmartThemeModule.stickySidebarBtn = function() {
		var $trigger = $('.wd-show-sidebar-btn');
		var $stickyBtn = $('.wd-sidebar-opener.wd-on-shop:not(.toolbar)');

		if ($stickyBtn.length <= 0 || $trigger.length <= 0 || woodmartThemeModule.$window.width() >= 1024) {
			return;
		}

		var stickySidebarBtnToggle = function() {
			var btnOffset = $trigger.offset().top + $trigger.outerHeight();
			var windowScroll = woodmartThemeModule.$window.scrollTop();

			if (btnOffset < windowScroll) {
				$stickyBtn.addClass('wd-sticky');
			} else {
				$stickyBtn.removeClass('wd-sticky');
			}
		};

		stickySidebarBtnToggle();

		woodmartThemeModule.$window.on('scroll', stickySidebarBtnToggle);
		woodmartThemeModule.$window.on('resize', stickySidebarBtnToggle);
	};

	$(document).ready(function() {
		woodmartThemeModule.stickySidebarBtn();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdArrowsLoadProducts wdLoadMoreLoadProducts wdProductsTabsLoaded', function () {
		woodmartThemeModule.swatchesLimit();
	});

	woodmartThemeModule.swatchesLimit = function() {
		$('.wd-swatches-divider').on('click', function() {
			var $this = $(this).parent();

			$this.find('.wd-swatch').removeClass('wd-hidden');
			$this.addClass('wd-all-shown');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.swatchesLimit();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.swatchesOnGrid();
		});
	});

	woodmartThemeModule.swatchesOnGrid = function() {
		woodmartThemeModule.$body.on('click', '.swatch-on-grid', function() {
			var src, srcset, image_sizes;

			var $this       = $(this),
			    imageSrc    = $this.data('image-src'),
			    imageSrcset = $this.data('image-srcset'),
			    imageSizes  = $this.data('image-sizes');

			if (typeof imageSrc == 'undefined' || '' === imageSrc) {
				return;
			}

			var product    = $this.parents('.product-grid-item'),
			    image      = product.find('.product-image-link img'),
			    source     = product.find('.product-image-link picture source'),
			    srcOrig    = image.data('original-src'),
			    srcsetOrig = image.data('original-srcset'),
			    sizesOrig  = image.data('original-sizes');

			if (typeof srcOrig == 'undefined') {
				image.data('original-src', image.attr('src'));
			}

			if (typeof srcsetOrig == 'undefined') {
				image.data('original-srcset', image.attr('srcset'));
			}

			if (typeof sizesOrig == 'undefined') {
				image.data('original-sizes', image.attr('sizes'));
			}

			if ($this.hasClass('active-swatch')) {
				src = srcOrig;
				srcset = srcsetOrig;
				image_sizes = sizesOrig;
				$this.removeClass('active-swatch');
				product.removeClass('product-swatched');
			} else {
				$this.parent().find('.active-swatch').removeClass('active-swatch');
				$this.addClass('active-swatch');
				product.addClass('product-swatched');
				src = imageSrc;
				srcset = imageSrcset;
				image_sizes = imageSizes;
			}

			if (image.attr('src') === src) {
				return;
			}

			product.addClass('wd-loading-image');

			image.attr('src', src).attr('srcset', srcset).attr('image_sizes', image_sizes).one('load', function() {
				product.removeClass('wd-loading-image');
			});

			if (source.length > 0) {
				source.attr('srcset', srcset).attr('image_sizes', image_sizes);
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.swatchesOnGrid();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdQuickShopSuccess wdQuickViewOpen wdUpdateWishlist', function () {
		woodmartThemeModule.swatchesVariations();
	});

	woodmartThemeModule.swatchesVariations = function() {
		var $variation_forms = $('.variations_form');
		var variationGalleryReplace = false;
		var swathesSelected = false;

		// Firefox mobile fix
		$('.variations_form .label').on('click', function(e) {
			if ($(this).siblings('.value').hasClass('with-swatches')) {
				e.preventDefault();
			}
		});

		$variation_forms.each(function() {
			var $variation_form = $(this);

			if ($variation_form.data('swatches')) {
				return;
			}

			$variation_form.data('swatches', true);

			if (!$variation_form.data('product_variations')) {
				$variation_form.find('.swatches-select').find('> div').addClass('swatch-enabled');
			}

			if ($('.swatches-select > div').hasClass('active-swatch')) {
				$variation_form.addClass('variation-swatch-selected');
			}

			$variation_form.on('click', '.swatches-select > div', function() {
					var $this = $(this);
					var value = $this.data('value');
					var id = $this.parent().data('id');
					var title = $this.data('title');

					resetSwatches($variation_form);

					if ($this.hasClass('active-swatch')) {
						return;
					}

					if ($this.hasClass('swatch-disabled')) {
						return;
					}

					$variation_form.find('select#' + id).val(value).trigger('change');
					$this.parent().find('.active-swatch').removeClass('active-swatch');
					$this.addClass('active-swatch');
					resetSwatches($variation_form);

					if ((woodmart_settings.swatches_labels_name === 'yes' && woodmartThemeModule.$window.width() >= 769) || woodmartThemeModule.$window.width() <= 768) {
						$this.parents('tr').find('.wd-attr-selected').html(title);
					}
				})
				.on('woocommerce_update_variation_values', function() {
					resetSwatches($variation_form);
				})
				.on('click', '.reset_variations', function() {
					$variation_form.find('.active-swatch').removeClass('active-swatch');

					if ((woodmart_settings.swatches_labels_name === 'yes' && woodmartThemeModule.$window.width() >= 769) || woodmartThemeModule.$window.width() <= 768) {
						$variation_form.find('.wd-attr-selected').html('');
					}
				})
				.on('reset_data', function() {
					var $this = $(this);
					var all_attributes_chosen = true;
					var some_attributes_chosen = false;

					$variation_form.find('.variations select').each(function() {
						var value = $this.val() || '';

						if (value.length === 0) {
							all_attributes_chosen = false;
						} else {
							some_attributes_chosen = true;
						}

					});

					if (all_attributes_chosen) {
						$this.parent().find('.active-swatch').removeClass('active-swatch');
					}

					$variation_form.removeClass('variation-swatch-selected');

					var $mainOwl = $('.woocommerce-product-gallery__wrapper.owl-carousel');

					resetSwatches($variation_form);

					replaceMainGallery('default', $variation_form);

					if (!$mainOwl.hasClass('owl-carousel')) {
						return;
					}

					if (woodmart_settings.product_slider_auto_height === 'yes') {
						if (!isQuickView() && isVariationGallery('default', $variation_form) && variationGalleryReplace) {
							$mainOwl.trigger('destroy.owl.carousel');
						}

						$('.product-images').imagesLoaded(function() {
							$mainOwl = $mainOwl.owlCarousel(woodmartThemeModule.mainCarouselArg);
							$mainOwl.trigger('refresh.owl.carousel');
						});
					} else {
						$mainOwl = $mainOwl.owlCarousel(woodmartThemeModule.mainCarouselArg);
						$mainOwl.trigger('refresh.owl.carousel');
					}

					var slide_go_to = woodmart_settings.product_gallery.thumbs_slider.position === 'centered' ? woodmart_settings.centered_gallery_start : 0;

					if (isQuickView()) {
						slide_go_to = 0;
					}

					$mainOwl.trigger('to.owl.carousel', slide_go_to);
				})
				.on('reset_image', function() {
					var $thumb = $('.thumbnails .product-image-thumbnail img').first();

					if (!isQuickView() && !isQuickShop($variation_form)) {
						$thumb.wc_reset_variation_attr('src');
					}
				})
				.on('show_variation', function(e, variation) {
					if (((woodmart_settings.swatches_labels_name === 'yes' && woodmartThemeModule.$window.width() >= 769) || woodmartThemeModule.$window.width() <= 768) && !swathesSelected) {
						$variation_form.find('.active-swatch').each(function() {
							var $this = $(this);
							var title = $this.data('title');
							$this.parents('tr').find('.wd-attr-selected').html(title);
						});

						swathesSelected = true;
					}

					if (!variation.image.src) {
						return;
					}

					var galleryHasImage = $variation_form.parents('.single-product-content').find('.thumbnails .product-image-thumbnail img[data-o_src="' + variation.image.thumb_src + '"]').length > 0;
					var $firstThumb = $variation_form.parents('.single-product-content').find('.thumbnails .product-image-thumbnail img').first();
					var originalImageUrl = $variation_form.parents('.single-product-content').find('.woocommerce-product-gallery .woocommerce-product-gallery__image > a').first().data('o_href');

					if (galleryHasImage) {
						$firstThumb.wc_reset_variation_attr('src');
					}

					if (!isQuickShop($variation_form) && !replaceMainGallery(variation.variation_id, $variation_form)) {
						if ($firstThumb.attr('src') !== variation.image.thumb_src) {
							$firstThumb.wc_set_variation_attr('src', variation.image.src);
						}

						woodmartThemeModule.$document.trigger('wdShowVariationNotQuickView');
					}

					$variation_form.addClass('variation-swatch-selected');

					if (!isQuickShop($variation_form) && !isQuickView() && originalImageUrl !== variation.image.full_src) {
						scrollToTop();
					}

					var $mainOwl = $('.woocommerce-product-gallery__wrapper.owl-carousel');

					if (!$mainOwl.hasClass('owl-carousel')) {
						return;
					}

					if (woodmart_settings.product_slider_auto_height === 'yes') {
						if (!isQuickView() && isVariationGallery(variation.variation_id, $variation_form) && variationGalleryReplace) {
							$mainOwl.trigger('destroy.owl.carousel');
						}

						$('.product-images').imagesLoaded(function() {
							$mainOwl = $mainOwl.owlCarousel(woodmartThemeModule.mainCarouselArg);
							$mainOwl.trigger('refresh.owl.carousel');
						});
					} else {
						$mainOwl = $mainOwl.owlCarousel(woodmartThemeModule.mainCarouselArg);
						$mainOwl.trigger('refresh.owl.carousel');
					}

					var $thumbs = $('.images .thumbnails');

					$mainOwl.trigger('to.owl.carousel', 0);

					if ($thumbs.hasClass('owl-carousel')) {
						$thumbs.owlCarousel().trigger('to.owl.carousel', 0);
						$thumbs.find('.active-thumb').removeClass('active-thumb');
						$thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
					} else if ($thumbs.hasClass('slick-slider')) {
						$thumbs.slick('slickGoTo', 0);
						if (!$thumbs.find('.product-image-thumbnail').eq(0).hasClass('active-thumb')) {
							$thumbs.find('.active-thumb').removeClass('active-thumb');
							$thumbs.find('.product-image-thumbnail').eq(0).addClass('active-thumb');
						}
					}
				});
		});

		var resetSwatches = function($variation_form) {
			// If using AJAX
			if (!$variation_form.data('product_variations')) {
				return;
			}

			$variation_form.find('.variations select').each(function() {
				var select = $(this);
				var swatch = select.parent().find('.swatches-select');
				var options = select.html();
				options = $(options);

				swatch.find('> div').removeClass('swatch-enabled').addClass('swatch-disabled');

				options.each(function() {
					var value = $(this).val();

					if ($(this).hasClass('enabled')) {
						swatch.find('div[data-value="' + value + '"]').removeClass('swatch-disabled').addClass('swatch-enabled');
					} else {
						swatch.find('div[data-value="' + value + '"]').addClass('swatch-disabled').removeClass('swatch-enabled');
					}
				});
			});
		};

		var isQuickView = function() {
			return $('.single-product-content').hasClass('product-quick-view');
		};

		var isQuickShop = function($form) {
			return $form.parent().hasClass('quick-shop-form');
		};

		var isVariationGallery = function(key, $variationForm) {
			if ('old' === woodmart_settings.variation_gallery_storage_method) {
				return isVariationGalleryOld(key);
			} else {
				return isVariationGalleryNew(key, $variationForm);
			}
		};

		var isVariationGalleryOld = function(key) {
			if (typeof woodmart_variation_gallery_data === 'undefined' && typeof woodmart_qv_variation_gallery_data === 'undefined') {
				return;
			}

			var variation_gallery_data = isQuickView() ? woodmart_qv_variation_gallery_data : woodmart_variation_gallery_data;

			return typeof variation_gallery_data !== 'undefined' && variation_gallery_data && variation_gallery_data[key];
		};

		var isVariationGalleryNew = function(key, $variationForm) {
			var data = getAdditionalVariationsImagesData($variationForm);

			return typeof data !== 'undefined' && data && data[key] && data[key].length > 1;
		};

		var scrollToTop = function() {
			if ((woodmart_settings.swatches_scroll_top_desktop === 'yes' && woodmartThemeModule.$window.width() >= 1024) || (woodmart_settings.swatches_scroll_top_mobile === 'yes' && woodmartThemeModule.$window.width() <= 1024)) {
				var $page = $('html, body');

				$page.stop(true);

				woodmartThemeModule.$window.on('mousedown wheel DOMMouseScroll mousewheel keyup touchmove', function() {
					$page.stop(true);
				});

				$page.animate({
					scrollTop: $('.product-image-summary').offset().top - 150
				}, 800);

				$('.wd-swatch').tooltip('hide');
			}
		};

		var getAdditionalVariationsImagesData = function($variationForm) {
			var rawData = $variationForm.data('product_variations');
			var data = [];

			if (!rawData) {
				return data;
			}

			rawData.forEach(function(value) {
				data[value.variation_id] = value.additional_variation_images;
				data['default'] = value.additional_variation_images_default;
			});

			return data;
		};

		var replaceMainGallery = function(key, $variationForm) {
			if ('old' === woodmart_settings.variation_gallery_storage_method) {
				if (!isVariationGallery(key, $variationForm) || isQuickShop($variationForm) || ('default' === key && !variationGalleryReplace)) {
					return false;
				}

				replaceMainGalleryOld(key, $variationForm);
			} else {
				if (!isVariationGallery(key, $variationForm) || isQuickShop($variationForm) || ('default' === key && !variationGalleryReplace)) {
					return false;
				}

				var data = getAdditionalVariationsImagesData($variationForm);
				replaceMainGalleryNew(data[key], $variationForm);
			}

			woodmartThemeModule.quickViewCarousel();
			$('.woocommerce-product-gallery__image').trigger('zoom.destroy');
			woodmartThemeModule.$document.trigger('wdReplaceMainGallery');
			if (!isQuickView()) {
				woodmartThemeModule.$document.trigger('wdReplaceMainGalleryNotQuickView');
			}

			variationGalleryReplace = 'default' !== key;

			woodmartThemeModule.$window.trigger('resize');

			return true;
		};

		var replaceMainGalleryOld = function(key, $variationForm) {
			var variation_gallery_data = isQuickView() ? woodmart_qv_variation_gallery_data : woodmart_variation_gallery_data;

			var imagesData = variation_gallery_data[key];
			var $mainGallery = $variationForm.parents('.single-product-content').find('.woocommerce-product-gallery__wrapper');

			$mainGallery.empty();

			for (var index = 0; index < imagesData.length; index++) {
				var $html = '<div class="product-image-wrap"><figure data-thumb="' + imagesData[index].data_thumb + '" class="woocommerce-product-gallery__image">';

				if (!isQuickView()) {
					$html += '<a href="' + imagesData[index].href + '">';
				}

				$html += imagesData[index].image;

				if (!isQuickView()) {
					$html += '</a>';
				}

				$html += '</figure></div>';

				$mainGallery.append($html);
			}
		};

		var replaceMainGalleryNew = function(imagesData, $variationForm) {
			var $mainGallery = $variationForm.parents('.single-product-content').find('.woocommerce-product-gallery__wrapper');
			$mainGallery.empty();

			for (var key in imagesData) {
				if (imagesData.hasOwnProperty(key)) {
					var $html = '<div class="product-image-wrap"><figure class="woocommerce-product-gallery__image" data-thumb="' + imagesData[key].thumbnail_src + '">';

					if (!isQuickView()) {
						$html += '<a href="' + imagesData[key].full_src + '" data-elementor-open-lightbox="no">';
					}

					var srcset = imagesData[key].srcset ? 'srcset="' + imagesData[key].srcset + '"' : '';

					$html += '<img width="' + imagesData[key].width + '" height="' + imagesData[key].height + '" src="' + imagesData[key].src + '" class="' + imagesData[key].class + '" alt="' + imagesData[key].alt + '" title="' + imagesData[key].title + '" data-caption="' + imagesData[key].data_caption + '" data-src="' + imagesData[key].data_src + '"  data-large_image="' + imagesData[key].data_large_image + '" data-large_image_width="' + imagesData[key].data_large_image_width + '" data-large_image_height="' + imagesData[key].data_large_image_height + '" ' + srcset + ' sizes="' + imagesData[key].sizes + '" />';

					if (!isQuickView()) {
						$html += '</a>';
					}

					$html += '</figure></div>';

					$mainGallery.append($html);
				}
			}
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.swatchesVariations();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.variationsPrice = function() {
		if ('no' === woodmart_settings.single_product_variations_price) {
			return;
		}

		$('.variations_form').each(function() {
			var $form = $(this);
			var $price = $form.parent().find('> .price').first();
			var priceOriginalHtml = $price.html();

			$form.on('show_variation', function(e, variation) {
				if (variation.price_html.length > 1) {
					$price.html(variation.price_html);
				}
			});

			$form.on('hide_variation', function() {
				$price.html(priceOriginalHtml);
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.variationsPrice();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.wishlist = function() {
		var cookiesName = 'woodmart_wishlist_count';

		if (woodmartThemeModule.$body.hasClass('logged-in')) {
			cookiesName += '_logged';
		}

		if (woodmart_settings.is_multisite) {
			cookiesName += '_' + woodmart_settings.current_blog_id;
		}

		var $widget = $('.wd-header-wishlist');
		var cookie = Cookies.get(cookiesName);

		if ($widget.length > 0 && 'undefined' !== typeof cookie) {
			try {
				var count = JSON.parse(cookie);
				$widget.find('.wd-tools-count').text(count);
			}
			catch (e) {
				console.log('cant parse cookies json');
			}
		}

		// Add to wishlist action
		woodmartThemeModule.$body.on('click', '.wd-wishlist-btn a', function(e) {
			var $this = $(this);

			if (!$this.hasClass('added')) {
				e.preventDefault();
			}

			var productId = $this.data('product-id');
			var addedText = $this.data('added-text');
			var key = $this.data('key');

			if ($this.hasClass('added')) {
				return true;
			}

			$this.addClass('loading');

			$.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : {
					action    : 'woodmart_add_to_wishlist',
					product_id: productId,
					key       : key
				},
				dataType: 'json',
				method  : 'GET',
				success : function(response) {
					if (response) {
						$this.addClass('added');
						woodmartThemeModule.$document.trigger('added_to_wishlist');

						if (response.wishlist_content) {
							updateWishlist(response);
						}

						if ($this.find('span').length > 0) {
							$this.find('span').text(addedText);
						} else {
							$this.text(addedText);
						}
					} else {
						console.log('something wrong loading wishlist data ', response);
					}
				},
				error   : function() {
					console.log(
						'We cant add to wishlist. Something wrong with AJAX response. Probably some PHP conflict.');
				},
				complete: function() {
					$this.removeClass('loading');
				}
			});
		});

		woodmartThemeModule.$body.on('click', '.wd-wishlist-remove', function(e) {
			e.preventDefault();
			var $this = $(this);
			var productId = $this.data('product-id');
			var key = $this.data('key');

			if ($this.hasClass('added')) {
				return true;
			}

			$this.addClass('loading');

			$.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : {
					action    : 'woodmart_remove_from_wishlist',
					product_id: productId,
					key       : key
				},
				dataType: 'json',
				method  : 'GET',
				success : function(response) {
					if (response.wishlist_content) {
						updateWishlist(response);
					} else {
						console.log('something wrong loading wishlist data ', response);
					}
				},
				error   : function() {
					console.log('We cant remove from wishlist. Something wrong with AJAX response. Probably some PHP conflict.');
				},
				complete: function() {
					$this.removeClass('loading');
				}
			});
		});

		// Elements update after ajax
		function updateWishlist(data) {
			var $wishlistContent = $('.wd-wishlist-content');
			var $widget = $('.wd-header-wishlist');

			if ($widget.length > 0) {
				$widget.find('.wd-tools-count').text(data.count);
			}

			if ($wishlistContent.length > 0 && !$wishlistContent.hasClass('wd-wishlist-preview')) {
				woodmartThemeModule.removeDuplicatedStylesFromHTML(data.wishlist_content, function(html) {
					$wishlistContent.replaceWith(html);
				});
			}

			woodmartThemeModule.$document.trigger('wdUpdateWishlist');
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.wishlist();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.woocommerceComments = function() {
		var hash = window.location.hash;
		var url = window.location.href;

		if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews' || url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {
			setTimeout(function() {
				window.scrollTo(0, 0);
			}, 1);

			setTimeout(function() {
				if ($(hash).length > 0) {
					$('html, body').stop().animate({
						scrollTop: $(hash).offset().top - 100
					}, 400);
				}
			}, 10);
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.woocommerceComments();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.woocommerceNotices = function() {
		var notices = '.woocommerce-error, .woocommerce-info, .woocommerce-message, div.wpcf7-response-output, #yith-wcwl-popup-message, .mc4wp-alert, .dokan-store-contact .alert-success, .yith_ywraq_add_item_product_message';

		woodmartThemeModule.$body.on('click', notices, function() {
			hideMessage($(this));
		});

		var hideMessage = function($msg) {
			$msg.removeClass('shown-notice').addClass('hidden-notice');
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.woocommerceNotices();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdBackHistory wdShopPageInit', function () {
		woodmartThemeModule.woocommercePriceSlider();
	});

	woodmartThemeModule.woocommercePriceSlider = function() {
		var $min_price = $('.price_slider_amount #min_price');
		var $max_price = $('.price_slider_amount #max_price');
		var $products = $('.products');

		if (typeof woocommerce_price_slider_params === 'undefined' || $min_price.length < 1 || !$.fn.slider) {
			return false;
		}

		var $slider = $('.price_slider');

		if ($slider.slider('instance') !== undefined) {
			return;
		}

		// Get markup ready for slider
		$('input#min_price, input#max_price').hide();
		$('.price_slider, .price_label').show();

		// Price slider uses $ ui
		var min_price         = $min_price.data('min'),
		    max_price         = $max_price.data('max'),
		    current_min_price = parseInt(min_price, 10),
		    current_max_price = parseInt(max_price, 10);

		if ($products.attr('data-min_price') && $products.attr('data-min_price').length > 0) {
			current_min_price = parseInt($products.attr('data-min_price'), 10);
		}

		if ($products.attr('data-max_price') && $products.attr('data-max_price').length > 0) {
			current_max_price = parseInt($products.attr('data-max_price'), 10);
		}

		$slider.slider({
			range  : true,
			animate: true,
			min    : min_price,
			max    : max_price,
			values : [
				current_min_price,
				current_max_price
			],
			create : function() {
				$min_price.val(current_min_price);
				$max_price.val(current_max_price);

				woodmartThemeModule.$body.trigger('price_slider_create', [
					current_min_price,
					current_max_price
				]);
			},
			slide  : function(event, ui) {
				$('input#min_price').val(ui.values[0]);
				$('input#max_price').val(ui.values[1]);

				woodmartThemeModule.$body.trigger('price_slider_slide', [
					ui.values[0],
					ui.values[1]
				]);
			},
			change : function(event, ui) {
				woodmartThemeModule.$body.trigger('price_slider_change', [
					ui.values[0],
					ui.values[1]
				]);
			}
		});

		setTimeout(function() {
			woodmartThemeModule.$body.trigger('price_slider_create', [
				current_min_price,
				current_max_price
			]);

			if ($slider.find('.ui-slider-range').length > 1) {
				$slider.find('.ui-slider-range').first().remove();
			}
		}, 10);
	};

	$(document).ready(function() {
		woodmartThemeModule.woocommercePriceSlider();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.woocommerceQuantity = function() {
		if (!String.prototype.getDecimals) {
			String.prototype.getDecimals = function() {
				var num   = this,
				    match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

				if (!match) {
					return 0;
				}

				return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
			};
		}

		woodmartThemeModule.$document.on('click', '.plus, .minus', function() {
			var $this      = $(this),
			    $qty       = $this.closest('.quantity').find('.qty'),
			    currentVal = parseFloat($qty.val()),
			    max        = parseFloat($qty.attr('max')),
			    min        = parseFloat($qty.attr('min')),
			    step       = $qty.attr('step');

			if (!currentVal || currentVal === '' || currentVal === 'NaN') {
				currentVal = 0;
			}
			if (max === '' || max === 'NaN') {
				max = '';
			}
			if (min === '' || min === 'NaN') {
				min = 0;
			}
			if (step === 'any' || step === '' || step === undefined || parseFloat(step) == 'NaN') {
				step = '1';
			}

			if ($this.is('.plus')) {
				if (max && (currentVal >= max)) {
					$qty.val(max);
				} else {
					$qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
				}
			} else {
				if (min && (currentVal <= min)) {
					$qty.val(min);
				} else if (currentVal > 0) {
					$qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
				}
			}

			$qty.trigger('change');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.woocommerceQuantity();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.woocommerceWrappTable = function() {
		$('.shop_table:not(.shop_table_responsive):not(.woocommerce-checkout-review-order-table)').wrap('<div class=\'responsive-table\'></div>');
	};

	$(document).ready(function() {
		woodmartThemeModule.woocommerceWrappTable();
	});
})(jQuery);

/* global woodmart_settings */
(function($) {
	woodmartThemeModule.woodmartCompare = function() {
		var cookiesName = 'woodmart_compare_list';

		if (woodmart_settings.is_multisite) {
			cookiesName += '_' + woodmart_settings.current_blog_id;
		}

		var $body         = woodmartThemeModule.$body,
		    $widget       = $('.wd-header-compare'),
		    compareCookie = Cookies.get(cookiesName);

		if ($widget.length > 0 && 'undefined' !== typeof compareCookie) {
			try {
				var ids = JSON.parse(compareCookie);
				$widget.find('.wd-tools-count').text(ids.length);
			}
			catch (e) {
				console.log('cant parse cookies json');
			}
		}

		$body.on('click', '.wd-compare-btn a', function(e) {
			var $this     = $(this),
			    id        = $this.data('id'),
			    addedText = $this.data('added-text');

			if ($this.hasClass('added')) {
				return true;
			}

			e.preventDefault();

			$this.addClass('loading');

			jQuery.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : {
					action: 'woodmart_add_to_compare',
					id    : id
				},
				dataType: 'json',
				method  : 'GET',
				success : function(response) {
					if (response.table) {
						updateCompare(response);
						woodmartThemeModule.$document.trigger('added_to_compare');
					} else {
						console.log('something wrong loading compare data ', response);
					}
				},
				error   : function() {
					console.log('We cant add to compare. Something wrong with AJAX response. Probably some PHP conflict.');
				},
				complete: function() {
					$this.removeClass('loading').addClass('added');

					if ($this.find('span').length > 0) {
						$this.find('span').text(addedText);
					} else {
						$this.text(addedText);
					}
				}
			});
		});

		$body.on('click', '.wd-compare-remove', function(e) {
			e.preventDefault();
			var $this = $(this),
			    id    = $this.data('id');

			$this.addClass('loading');

			jQuery.ajax({
				url     : woodmart_settings.ajaxurl,
				data    : {
					action: 'woodmart_remove_from_compare',
					id    : id
				},
				dataType: 'json',
				method  : 'GET',
				success : function(response) {
					if (response.table) {
						updateCompare(response);
					} else {
						console.log('something wrong loading compare data ', response);
					}
				},
				error   : function() {
					console.log('We cant remove product compare. Something wrong with AJAX response. Probably some PHP conflict.');
				},
				complete: function() {
					$this.addClass('loading');
				}
			});
		});

		function updateCompare(data) {
			var $widget = $('.wd-header-compare');

			if ($widget.length > 0) {
				$widget.find('.wd-tools-count').text(data.count);
			}

			var $wcCompareTable = $('.wd-compare-table');
			if ($wcCompareTable.length > 0) {
				$wcCompareTable.replaceWith(data.table);
			}
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.woodmartCompare();
	});
})(jQuery);
