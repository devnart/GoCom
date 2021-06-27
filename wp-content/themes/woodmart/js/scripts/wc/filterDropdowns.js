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
