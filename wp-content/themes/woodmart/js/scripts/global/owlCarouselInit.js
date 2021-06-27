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
