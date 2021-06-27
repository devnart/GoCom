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
