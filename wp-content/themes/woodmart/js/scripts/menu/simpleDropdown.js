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
