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
