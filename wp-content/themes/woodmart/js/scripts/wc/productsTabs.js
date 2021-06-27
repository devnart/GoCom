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
