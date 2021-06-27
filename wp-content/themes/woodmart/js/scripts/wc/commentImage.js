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
