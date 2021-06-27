jQuery(function($) {
	if ('undefined' != typeof elementor && 'undefined' !== elementorCommon) {
		elementor.on('preview:loaded', function() {
			var $modal;

			// Add button.
			var $buttons = $('#tmpl-elementor-add-section');

			var text = $buttons.text().replace(
				'<div class="elementor-add-section-drag-title',
				'<div class="elementor-add-section-area-button xts-library-modal-btn" title="XTemos Templates">XTemos Templates</div><div class="elementor-add-section-drag-title'
			);

			$buttons.text(text);

			// Call modal.
			$(elementor.$previewContents[0].body).on('click', '.xts-library-modal-btn', function() {
				if ($modal) {
					$modal.show();
					return;
				}

				var modalOptions = {
					id           : 'xts-library-modal',
					headerMessage: $('#tmpl-elementor-xts-library-modal-header').html(),
					message      : $('#tmpl-elementor-xts-library-modal').html(),
					className    : 'elementor-templates-modal',
					closeButton  : true,
					draggable    : false,
					hide         : {
						onOutsideClick: true,
						onEscKeyPress : true
					},
					position     : {
						my: 'center',
						at: 'center'
					}
				};
				$modal = elementorCommon.dialogsManager.createWidget('lightbox', modalOptions);
				$modal.show();

				loadTemplates();
			});

			// Load items.
			function loadTemplates() {
				showLoader();

				$.ajax({
					url     : xts_template_library_script.demoAjaxUrl,
					method  : 'GET',
					data    : {
						action : 'woodmart_load_templates',
						builder: 'elementor'
					},
					dataType: 'json',
					success : function(response) {
						if (response && response.elements) {
							var itemTemplate = wp.template('elementor-xts-library-modal-item');
							var itemOrderTemplate = wp.template('elementor-xts-library-modal-order');
							response.elements = response.elements.reverse();
							$(itemTemplate(response)).appendTo($('#xts-library-modal #elementor-template-library-templates-container'));
							$(itemOrderTemplate(response)).appendTo($('#xts-library-modal #elementor-template-library-filter-toolbar-remote'));
							importTemplate();
							hideLoader();
						} else {
							$('<div class="xts-notice xts-error">The library can\'t be loaded from the server.</div>').appendTo($('#xts-library-modal #elementor-template-library-templates-container'));
							hideLoader();
						}
					},
					error   : function() {
						$('<div class="xts-notice xts-error">The library can\'t be loaded from the server.</div>').appendTo($('#xts-library-modal #elementor-template-library-templates-container'));
						hideLoader();
					}
				});
			}

			// Loader
			function showLoader() {
				$('#xts-library-modal #elementor-template-library-templates').hide();
				$('#xts-library-modal .elementor-loader-wrapper').show();
			}

			function hideLoader() {
				$('#xts-library-modal #elementor-template-library-templates').show();
				$('#xts-library-modal .elementor-loader-wrapper').hide();
			}

			function activateUpdateButton() {
				$('#elementor-panel-saver-button-publish').toggleClass('elementor-disabled');
				$('#elementor-panel-saver-button-save-options').toggleClass('elementor-disabled');
			}

			// Import.
			function importTemplate() {
				$('#xts-library-modal .elementor-template-library-template-insert').on('click', function() {
					showLoader();

					var config = {
						data   : {
							source            : 'xts',
							edit_mode         : true,
							display           : true,
							template_id       : $(this).data('id'),
							with_page_settings: false
						},
						success: function success(data) {
							if (data && data.content) {
								elementor.getPreviewView().addChildModel(data.content);
								$modal.hide();
								setTimeout(function() {
									hideLoader();
								}, 2000);
								activateUpdateButton();
							} else {
								$('<div class="xts-notice xts-error">The element can\'t be loaded from the server.</div>').prependTo($('#xts-library-modal #elementor-template-library-templates-container'));
								hideLoader();
							}
						},
						error  : function() {
							$('<div class="xts-notice xts-error">The element can\'t be loaded from the server.</div>').prependTo($('#xts-library-modal #elementor-template-library-templates-container'));
							hideLoader();
						}
					};

					return elementorCommon.ajax.addRequest('get_template_data', config);
				});

				// Close button.
				$('#xts-library-modal .elementor-templates-modal__header__close').on('click', function() {
					$modal.hide();
					hideLoader();
				});

				// Search.
				$('#xts-library-modal #elementor-template-library-filter-text').on('keyup', function() {
					var val = $(this).val().toLowerCase();

					$('#xts-library-modal').find('.elementor-template-library-template-block').each(function() {
						var $this = $(this);
						var title = $this.data('title').toLowerCase();
						var slug = $this.data('slug').toLowerCase();

						if (title.indexOf(val) > -1 || slug.indexOf(val) > -1) {
							$this.show();
						} else {
							$this.hide();
						}
					});
				});

				// Filters.
				$('#xts-library-modal #elementor-template-library-filter-subtype').on('change', function() {
					var val = $(this).val();

					$('#xts-library-modal').find('.elementor-template-library-template-block').each(function() {
						var $this = $(this);
						var tag = $this.data('tag').toLowerCase();

						if (tag.indexOf(val) > -1 || 'all' === val) {
							$this.show();
						} else {
							$this.hide();
						}
					});
				});
			}
		});
	}
});
