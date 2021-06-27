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
