var woodmartOptions;
/* global jQuery, wp, xtsTypography, WebFont */

(function ($) {
	'use strict';

	woodmartOptions = (function () {

		var woodmartOptionsAdmin = {

			optionsPage: function () {
				var $options = $('.xts-options'),
					$lastTab = $options.find('.xts-last-tab-input');

				$options.on('click', '.xts-sections-nav a', function (e) {
					e.preventDefault();
					var $btn = $(this),
						id = $btn.data('id');

					$lastTab.val(id);

					$options.find('.xts-fields-section.xts-fields-section').
						removeClass('xts-active-section').
						addClass('xts-hidden');

					$options.find('.xts-fields-section[data-id="' + id + '"]').
						addClass('xts-active-section').
						removeClass('xts-hidden');

					$options.find('.xts-active-nav').
						removeClass('xts-active-nav');

					$options.find('a[data-id="' + id + '"]').
						parent().
						addClass('xts-active-nav');

					if ($btn.parent().hasClass('xts-subsection-nav')) {
						$btn.parent().
							parent().
							parent().
							addClass('xts-active-nav');
					}

					woodmartOptionsAdmin.editorControl();

					$(document).trigger('xts_section_changed');
				});
				$(document).trigger('xts_section_changed');

				woodmartOptionsAdmin.editorControl();

				$options.on('click', '.xts-reset-options-btn', function (e) {
					return confirm(
						'Are you sure you want to reset ALL settings (not only this section) to default values? This process cannot be undone. Continue?');
				});

				$('.toplevel_page_xtemos_options').parent().find('li a').on('click', function (e) {
					var $this = $(this),
						href = $this.attr('href'),
						section = false;

					if (href) {
						var hrefParts = href.split('tab=');
						if (hrefParts[1]) {
							section = hrefParts[1];
						}
					}

					if (!section) {
						return true;
					}

					var $sectionLink = $('.xts-sections-nav [data-id="' + section + '"]');

					if ($sectionLink.length == 0) {
						return true;
					}

					e.preventDefault();

					$sectionLink.trigger('click');

					$this.parent().parent().find('.current').removeClass('current');
					$this.parent().addClass('current');

				});
			},

			switcherControl: function () {
				var $switchers = $('.xts-switcher-control');

				$switchers.each(function () {
					var $switcher = $(this),
						$input = $switcher.find('input[type="hidden"]'),
						$on = $switcher.find('.xts-switcher-on'),
						$off = $switcher.find('.xts-switcher-off');

					$on.on('click', function () {
						if ($on.hasClass('xts-switcher-active')) {
							return;
						}
						$switcher.find('.xts-switcher-active').
							removeClass('xts-switcher-active');
						$on.addClass('xts-switcher-active');
						$input.val(1).change();
					});

					$off.on('click', function () {
						if ($off.hasClass('xts-switcher-active')) {
							return;
						}
						$switcher.find('.xts-switcher-active').
							removeClass('xts-switcher-active');
						$off.addClass('xts-switcher-active');
						$input.val(0).change();
					});
				});
			},

			buttonsControl: function () {
				var $sets = $('.xts-buttons-control');

				$sets.each(function () {
					var $set = $(this),
						$input = $set.find('input[type="hidden"]');

					$set.on('click', '.xts-set-item', function () {
						var $btn = $(this);
						if ($btn.hasClass('xts-btns-set-active')) {
							return;
						}
						var val = $btn.data('value');

						$set.find('.xts-btns-set-active').
							removeClass('xts-btns-set-active');

						$btn.addClass('xts-btns-set-active');

						$input.val(val).change();
					});
				});
			},

			colorControl: function () {
				var $colors = $('.xts-active-section .xts-color-control');

				if ($colors.length <= 0) {
					return;
				}

				$colors.each(function () {
					var $color = $(this),
						$input = $color.find('input[type="text"]');

					if ($color.hasClass('xts-field-inited')) {
						return;
					}

					$input.wpColorPicker();

					$color.addClass('xts-field-inited');
				});
			},

			checkboxControl: function () {
				var $checkboxes = $('.xts-checkbox-control');

				$checkboxes.each(function () {
					var $checkbox = $(this).find('input');

					$checkbox.change(function () {
						if ($checkbox.prop('checked')) {
							$checkbox.val('on');
						} else {
							$checkbox.val('off');
						}
					});
				});
			},

			uploadControl: function (force_init) {
				var $uploads = $('.xts-active-section .xts-upload-control');

				if (force_init) {
					$uploads = $('.widget-content .xts-upload_list-control');
				}

				if ($uploads.length <= 0) {
					return;
				}

				$uploads.each(function () {
					var $upload = $(this),
						$removeBtn = $upload.find('.xts-remove-upload-btn'),
						$inputURL = $upload.find('input.xts-upload-input-url'),
						$inputID = $upload.find('input.xts-upload-input-id'),
						$preview = $upload.find('.xts-upload-preview'),
						$previewInput = $upload.find('.xts-upload-preview-input');

					if ($upload.hasClass('xts-field-inited') && !force_init) {
						return;
					}

					$upload.off('click').on('click', '.xts-upload-btn, img', function (e) {
						e.preventDefault();

						var custom_uploader = wp.media({
							title: 'Insert file',
							button: {
								text: 'Use this file', // button label text
							},
							multiple: false, // for multiple image selection set
							// to true
						}).on('select', function () { // it also has "open" and "close" events
							var attachment = custom_uploader.state().
								get('selection').
								first().
								toJSON();
							$inputID.val(attachment.id);
							$inputURL.val(attachment.url);
							$preview.find('img').remove();
							$previewInput.val(attachment.url);
							$preview.prepend(
								'<img src="' + attachment.url + '" />');
							$removeBtn.addClass('xts-active');
						}).open();
					});

					$removeBtn.on('click', function (e) {
						e.preventDefault();

						if ($preview.find('img').length == 1) {
							$preview.find('img').remove();
						}

						$previewInput.val('');
						$inputID.val('');
						$inputURL.val('');
						$removeBtn.removeClass('xts-active');
					});

					$upload.addClass('xts-field-inited');
				});
			},

			uploadListControl: function(force_init) {
				var $uploads = $('.xts-active-section .xts-upload_list-control');

				if (force_init) {
					$uploads = $('.widget-content .xts-upload_list-control');
				}

				if ($uploads.length <= 0) {
					return;
				}

				$uploads.each(function() {
					var $upload = $(this);
					var $inputID = $upload.find('input.xts-upload-input-id');
					var $preview = $upload.find('.xts-upload-preview');
					var $clearBtn = $upload.find('.xts-btn-remove');

					if ($upload.hasClass('xts-field-inited') && !force_init) {
						return;
					}

					$upload.off('click').on('click', '.xts-upload-btn, img', function(e) {
						e.preventDefault();

						console.log($(this));

						var custom_uploader = wp.media({
							title: 'Insert file',
							button: {
								text: 'Use this file', // button label text
							},
							multiple: true, // for multiple image selection set
							// to true
						}).on('select', function() { // it also has "open" and "close" events
							var attachments = custom_uploader.state().
							get('selection');
							var inputIdValue = $inputID.val();

							attachments.map(function(attachment) {
								attachment = attachment.toJSON();

								if (attachment.id) {
									var attachment_image = attachment.sizes &&
									attachment.sizes.thumbnail
										? attachment.sizes.thumbnail.url
										: attachment.url;
									inputIdValue = inputIdValue ? inputIdValue +
										',' + attachment.id : attachment.id;

									$preview.append(
										'<div data-attachment_id="' +
										attachment.id + '"><img src="' +
										attachment_image +
										'"><a href="#" class="xts-remove"><span class="dashicons dashicons-dismiss"></span></a></div>');
								}
							});

							$inputID.val(inputIdValue).trigger('change');
							$clearBtn.addClass('xts-active');
						}).open();
					});

					$preview.on('click', '.xts-remove', function(e) {
						e.preventDefault();
						$(this).parent().remove();

						var attachmentIds = '';

						$preview.find('div').each(function() {
							var attachmentId = $(this).attr('data-attachment_id');
							attachmentIds = attachmentIds + attachmentId + ',';
						});

						$inputID.val(attachmentIds).trigger('change');

						if (!attachmentIds) {
							$clearBtn.removeClass('xts-active');
						}
					});

					$clearBtn.on('click', function(e) {
						e.preventDefault();
						$preview.empty();
						$inputID.val('').trigger('change');
						$clearBtn.removeClass('xts-active');
					});

					$upload.addClass('xts-field-inited');
				});
			},

			selectControl: function (force_init) {
				var $select = $('.xts-active-section .xts-select.xts-select2:not(.xts-autocomplete)');

				if (force_init) {
					$select = $('.widget-content .xts-select.xts-select2:not(.xts-autocomplete)');
				}

				if ($select.length > 0) {
					var select2Defaults = {
						width: '100%',
						allowClear: true,
						theme: 'xts',
						tags: true,
						placeholder: 'Select',
					};

					$select.each(function () {
						var $select2 = $(this);

						if ($select2.hasClass('xts-field-inited')) {
							return;
						}

						if ($select2.attr('multiple')) {
							$select2.on('select2:select', function (e) {
								var $elm = $(e.params.data.element);
								$select2.append($elm);
								$select2.trigger('change.select2');
							});

							$select2.parent().find('.xts-select2-all').on('click', function (e) {
								e.preventDefault();

								$select2.select2('destroy').find('option').prop('selected', 'selected').end().select2(select2Defaults)
							});

							$select2.parent().find('.xts-unselect2-all').on('click', function (e) {
								e.preventDefault();

								$select2.select2('destroy').find('option').prop('selected', false).end().select2(select2Defaults)
							});
						}

						if ($select2.parents('#widget-list').length > 0) {
							return;
						}

						$select2.select2(select2Defaults);

						$select2.addClass('xts-field-inited');
					});
				}

				$('.xts-active-section .xts-select.xts-select2.xts-autocomplete').each(function() {
					var $field = $(this);
					var type = $field.data('type');
					var value = $field.data('value');
					var search = $field.data('search');

					if ($field.hasClass('xts-field-inited')) {
						return;
					}

					$field.select2({
						theme: 'xts',
						allowClear: true,
						placeholder: 'Select',
						dropdownAutoWidth: false,
						width: 'resolve',
						ajax: {
							url: woodmartConfig.ajaxUrl,
							data: function(params) {
								return {
									action: search,
									type: type,
									value: value,
									params: params,
								};
							},
							method: 'POST',
							dataType: 'json',
							delay: 250,
							processResults: function(data) {
								return {
									results: data,
								};
							},
							cache: true,
						},
					}).on('select2:select select2:unselect', function(e) {
						// $(e.currentTarget).find('option').each(function(e) {
						// 	$(this).removeAttr('selected');
						// });
					});

					$field.addClass('xts-field-inited');
				});
			},

			backgroundControl: function () {
				var $bgs = $('.xts-active-section .xts-background-control');

				if ($bgs.length <= 0) {
					return;
				}

				$bgs.each(function () {
					var $bg = $(this),
						$uploadBtn = $bg.find('.xts-upload-btn'),
						$removeBtn = $bg.find('.xts-remove-upload-btn'),
						$inputURL = $bg.find('input.xts-upload-input-url'),
						$inputID = $bg.find('input.xts-upload-input-id'),
						$preview = $bg.find('.xts-upload-preview'),
						$colorInput = $bg.find(
							'.xts-bg-color input[type="text"]'),
						$bgPreview = $bg.find('.xts-bg-preview'),
						$repeatSelect = $bg.find('.xts-bg-repeat'),
						$sizeSelect = $bg.find('.xts-bg-size'),
						$attachmentSelect = $bg.find('.xts-bg-attachment'),
						$positionSelect = $bg.find('.xts-bg-position'),
						data = {};

                    if ($bg.hasClass('xts-field-inited')) {
                        return;
                    }

					$colorInput.wpColorPicker({
						change: function () {
							updatePreview();
						},
					});

					$bg.find('select').select2({
						allowClear: true,
						theme: 'xts',
					});

					$bg.on('click', '.xts-upload-btn, img', function (e) {
						e.preventDefault();

						var custom_uploader = wp.media({
							title: 'Insert image',
							library: {
								// uncomment the next line if you want to
								// attach image to the current post uploadedTo
								// : wp.media.view.settings.post.id,
								type: 'image',
							},
							button: {
								text: 'Use this image', // button label text
							},
							multiple: false, // for multiple image selection set
							// to true
						}).on('select', function () { // it also has "open" and "close" events
							var attachment = custom_uploader.state().
								get('selection').
								first().
								toJSON();
							$inputID.val(attachment.id);
							$inputURL.val(attachment.url);
							$preview.find('img').remove();
							$preview.prepend(
								'<img src="' + attachment.url + '" />');
							$removeBtn.addClass('xts-active');
							updatePreview();
						}).open();
					});

					$removeBtn.on('click', function (e) {
						e.preventDefault();
						$preview.find('img').remove();
						$inputID.val('');
						$inputURL.val('');
						$removeBtn.removeClass('xts-active');
						updatePreview();
					});

					$bg.on('change', 'select', function () {
						updatePreview();
					});

					function updatePreview() {
						data.backgroundColor = $colorInput.val();
						data.backgroundImage = 'url(' + $inputURL.val() + ')';
						data.backgroundRepeat = $repeatSelect.val();
						data.backgroundSize = $sizeSelect.val();
						data.backgroundAttachment = $attachmentSelect.val();
						data.backgroundPosition = $positionSelect.val();
						data.height = 100;
						$bgPreview.css(data).show();
					}

					$bg.addClass('xts-field-inited');
				});
			},

			customFontsControl: function () {
				$('.xts-custom-fonts').each(function () {
					var $parent = $(this);

					$parent.on('click', '.xts-custom-fonts-btn-add',
						function (e) {
							e.preventDefault();

							var $template = $parent.find(
								'.xts-custom-fonts-template').clone();
							var key = $parent.data('key') + 1;

							$parent.find('.xts-custom-fonts-sections').
								append($template);
							var regex = /{{index}}/gi;
							$template.removeClass(
								'xts-custom-fonts-template hide').
								html($template.html().replace(regex, key)).
								attr('data-id', $template.attr('data-id').
									replace(regex, key));

							$parent.data('key', key);

							woodmartOptionsAdmin.uploadControl();
						});

					$parent.on('click', '.xts-custom-fonts-btn-remove',
						function (e) {
							e.preventDefault();

							$(this).parent().remove();
						});
				});
			},

			typographyControl: function () {
				var $typography = $('.xts-active-section .xts-advanced-typography-field');

				if ($typography.length <= 0) {
					return;
				}

				var isSelecting = false,
					selVals = [],
					select2Defaults = {
						width: '100%',
						allowClear: true,
						theme: 'xts',
					},
					defaultVariants = {
						'100': 'Thin 100',
						'200': 'Light 200',
						'300': 'Regular 300',
						'400': 'Normal 400',
						'500': 'Medium 500',
						'600': 'Semi Bold 600',
						'700': 'Bold 700',
						'800': 'Extra Bold 800',
						'900': 'Black 900',
						'100italic': 'Thin 100 Italic',
						'200italic': 'Light 200 Italic',
						'300italic': 'Regular 300 Italic',
						'400italic': 'Normal 400 Italic',
						'500italic': 'Medium 500 Italic',
						'600italic': 'Semi Bold 600 Italic',
						'700italic': 'Bold 700 Italic',
						'800italic': 'Extra Bold 800 Italic',
						'900italic': 'Black 900 Italic',
					};

				$typography.each(function () {
					var $parent = $(this);

					if ($parent.hasClass('xts-field-inited')) {
						return;
					}

					$parent.find(
						'.xts-typography-section:not(.xts-typography-template)').
						each(function () {
							var $section = $(this),
								id = $section.data('id');

							initTypographySection($parent, id);
						});

					$parent.on('click', '.xts-typography-btn-add', function (e) {
						e.preventDefault();

						var $template = $parent.find(
							'.xts-typography-template').clone(),
							key = $parent.data('key') + 1;

						$parent.find('.xts-typography-sections').
							append($template);
						var regex = /{{index}}/gi;

						$template.removeClass('xts-typography-template hide').
							html($template.html().replace(regex, key)).
							attr('data-id',
								$template.attr('data-id').replace(regex, key));

						$parent.data('key', key);

						initTypographySection($parent,
							$template.attr('data-id'));
					});

					$parent.on('click', '.xts-typography-btn-remove',
						function (e) {
							e.preventDefault();

							$(this).parent().remove();
						});

					$parent.addClass('xts-field-inited');
				});

				function initTypographySection($parent, id) {
					var $section = $parent.find('[data-id="' + id + '"]'),
						$family = $section.find('.xts-typography-family'),
						$familyInput = $section.find(
							'.xts-typography-family-input'),
						$googleInput = $section.find(
							'.xts-typography-google-input'),
						$customInput = $section.find(
							'.xts-typography-custom-input'),
						$customSelector = $section.find(
							'.xts-typography-custom-selector'),
						$selector = $section.find('.xts-typography-selector'),
						$transform = $section.find('.xts-typography-transform'),
						$color = $section.find('.xts-typography-color'),
						$colorHover = $section.find(
							'.xts-typography-color-hover'),
						$responsiveControls = $section.find(
							'.xts-typography-responsive-controls');

					if ($family.data('value') !== '') {
						$family.val($family.data('value'));
					}

					syncronizeFontVariants($section, true, false);

					//init when value is changed
					$section.find(
						'.xts-typography-family, .xts-typography-style, .xts-typography-subset').
						on(
							'change',
							function () {
								syncronizeFontVariants($section, false, false);
							},
						);

					var fontFamilies = [
						{
							id: '',
							text: '',
						}],
						customFonts = {
							text: 'Custom fonts',
							children: [],
						},
						stdFonts = {
							text: 'Standard fonts',
							children: [],
						},
						googleFonts = {
							text: 'Google fonts',
							children: [],
						};

					$.map(xtsTypography.stdfonts, function (val, i) {
						stdFonts.children.push({
							id: i,
							text: val,
							selected: (i == $family.data('value')),
						});

					});

					$.map(xtsTypography.googlefonts, function (val, i) {
						googleFonts.children.push({
							id: i,
							text: i,
							google: true,
							selected: (i == $family.data('value')),
						});
					});

					$.map(xtsTypography.customFonts, function (val, i) {
						customFonts.children.push({
							id: i,
							text: i,
							selected: (i == $family.data('value')),
						});
					});

					if (customFonts.children.length > 0) {
						fontFamilies.push(customFonts);
					}

					fontFamilies.push(stdFonts);
					fontFamilies.push(googleFonts);
					$family.on('select change click', function() {
						if ($family.hasClass('xts-field-inited')) {
							return;
						}

						$family.addClass('xts-field-inited');

						$family.empty();

						$family.select2({
							data: fontFamilies,
							allowClear: true,
							theme: 'xts',
							dropdownAutoWidth: false,
							width            : 'resolve'
						}).on(
							'select2:selecting',
							function (e) {
								var data = e.params.args.data;
								var fontName = data.text;

								$familyInput.attr('value', fontName);

								// option values
								selVals = data;
								isSelecting = true;

								syncronizeFontVariants($section, false, true);
							},
						).on(
							'select2:unselecting',
							function (e) {
								$(this).one('select2:opening', function (ev) {
									ev.preventDefault();
								});
							},
						).on(
							'select2:unselect',
							function (e) {
								$familyInput.val('');

								$googleInput.val('false');

								$family.val(null).change();

								syncronizeFontVariants($section, false, true);
							},
						);

						$family.select2('open');
					});

					// CSS selector multi select field
					$selector.select2(select2Defaults).on(
						'select2:select',
						function (e) {
							var val = e.params.data.id;
							if (val != 'custom') {
								return;
							}
							$customInput.val(true);
							$customSelector.removeClass('hide');

						},
					).on(
						'select2:unselect',
						function (e) {
							var val = e.params.data.id;
							if (val != 'custom') {
								return;
							}
							$customInput.val('');
							$customSelector.val('').addClass('hide');
						},
					);

					$transform.select2(select2Defaults);

					// Color picker fields
					$color.wpColorPicker({
						change: function (event, ui) {
							// needed for palette click
							setTimeout(function () {
								updatePreview($section);
							}, 5);
						},
					});
					$colorHover.wpColorPicker();

					// Responsive font size and line height
					$responsiveControls.on('click',
						'.xts-typography-responsive-opener', function () {
							var $this = $(this);
							$this.parent().
								find(
									'.xts-typography-control-tablet, .xts-typography-control-mobile').
								toggleClass('show hide');
						}).on('change', 'input', function () {
							updatePreview($section);
						});
				}

				function updatePreview($section) {
					var sectionFields = {
						familyInput: $section.find(
							'.xts-typography-family-input'),
						weightInput: $section.find(
							'.xts-typography-weight-input'),
						preview: $section.find('.xts-typography-preview'),
						sizeInput: $section.find(
							'.xts-typography-size-container .xts-typography-control-desktop input'),
						heightInput: $section.find(
							'.xts-typography-height-container .xts-typography-control-desktop input'),
						colorInput: $section.find('.xts-typography-color'),
					};

					var size = sectionFields.sizeInput.val(),
						height = sectionFields.heightInput.val(),
						weight = sectionFields.weightInput.val(),
						color = sectionFields.colorInput.val(),
						family = sectionFields.familyInput.val();

					if (!height) {
						height = size;
					}

					//show in the preview box the font
					sectionFields.preview.css('font-weight', weight).
						css('font-family', family + ', sans-serif').
						css('font-size', size + 'px').
						css('line-height', height + 'px');

					if (family === 'none' && family === '') {
						//if selected is not a font remove style "font-family"
						// at preview box
						sectionFields.preview.css('font-family', 'inherit');
					}

					if (color) {
						var bgVal = '#444444';
						if (color !== '') {
							// Replace the hash with a blank.
							color = color.replace('#', '');

							var r = parseInt(color.substr(0, 2), 16);
							var g = parseInt(color.substr(2, 2), 16);
							var b = parseInt(color.substr(4, 2), 16);
							var res = ((r * 299) + (g * 587) + (b * 114)) /
								1000;
							bgVal = (res >= 128) ? '#444444' : '#ffffff';
						}
						sectionFields.preview.css('color', '#' + color).
							css('background-color', bgVal);
					}

					sectionFields.preview.slideDown();
				}

				function loadGoogleFont(family, style, script) {

					if (family == null || family == 'inherit') {
						return;
					}

					//add reference to google font family
					//replace spaces with "+" sign
					var link = family.replace(/\s+/g, '+');

					if (style && style !== '') {
						link += ':' + style.replace(/\-/g, ' ');
					}

					if (script && script !== '') {
						link += '&subset=' + script;
					}

					if (typeof (WebFont) !== 'undefined' && WebFont) {
						WebFont.load({
							google: {
								families: [link],
							},
						});
					}
				}

				function syncronizeFontVariants($section, init, changeFamily) {

					var sectionFields = {
						family: $section.find('.xts-typography-family'),
						familyInput: $section.find(
							'.xts-typography-family-input'),
						style: $section.find('select.xts-typography-style'),
						styleInput: $section.find(
							'.xts-typography-style-input'),
						weightInput: $section.find(
							'.xts-typography-weight-input'),
						subsetInput: $section.find(
							'.xts-typography-subset-input'),
						subset: $section.find('select.xts-typography-subset'),
						googleInput: $section.find(
							'.xts-typography-google-input'),
						preview: $section.find('.xts-typography-preview'),
						sizeInput: $section.find(
							'.xts-typography-size-container .xts-typography-control-desktop input'),
						heightInput: $section.find(
							'.xts-typography-height-container .xts-typography-control-desktop input'),
						colorInput: $section.find('.xts-typography-color'),
					};

					// Set all the variables to be checked against
					var family = sectionFields.familyInput.val();

					if (!family) {
						family = null; //"inherit";
					}

					var style = sectionFields.style.val();
					var script = sectionFields.subset.val();

					// Is selected font a google font?
					var google;
					if (isSelecting === true) {
						google = selVals.google;
						sectionFields.googleInput.val(google);
					}
					else {
						google = woodmartOptionsAdmin.makeBool(
							sectionFields.googleInput.val(),
						); // Check if font is a google font
					}

					// Page load. Speeds things up memory wise to offload to
					// client
					if (init) {
						style = sectionFields.style.data('value');
						script = sectionFields.subset.data('value');

						if (style !== '') {
							style = String(style);
						}

						if (typeof (script) !== undefined) {
							script = String(script);
						}
					}

					// Something went wrong trying to read google fonts, so
					// turn google off
					if (xtsTypography.googlefonts === undefined) {
						google = false;
					}

					// Get font details
					var details = '';
					if (google === true &&
						(family in xtsTypography.googlefonts)) {
						details = xtsTypography.googlefonts[family];
					}
					else {
						details = defaultVariants;
					}

					sectionFields.subsetInput.val(script);

					// If we changed the font. Selecting variable is set to
					// true only when family field is opened
					if (isSelecting || init || changeFamily) {
						var html = '<option value=""></option>';

						// Google specific stuff
						if (google === true) {

							// STYLES
							var selected = '';
							$.each(
								details.variants,
								function (index, variant) {
									if (variant.id === style ||
										woodmartOptionsAdmin.size(
											details.variants) === 1) {
										selected = ' selected="selected"';
										style = variant.id;
									}
									else {
										selected = '';
									}

									html += '<option value="' + variant.id +
										'"' + selected + '>' +
										variant.name.replace(
											/\+/g, ' ',
										) + '</option>';
								},
							);

							// destroy select2
							if (sectionFields.subset.data('select2')) {
								sectionFields.style.select2('destroy');
							}

							// Instert new HTML
							sectionFields.style.html(html);

							// Init select2
							sectionFields.style.select2(select2Defaults);

							// SUBSETS
							selected = '';
							html = '<option value=""></option>';

							$.each(
								details.subsets,
								function (index, subset) {
									if (subset.id === script ||
										woodmartOptionsAdmin.size(
											details.subsets) === 1) {
										selected = ' selected="selected"';
										script = subset.id;
										sectionFields.subset.val(script);
									}
									else {
										selected = '';
									}
									html += '<option value="' + subset.id +
										'"' + selected + '>' +
										subset.name.replace(
											/\+/g, ' ',
										) + '</option>';
								},
							);

							// Destroy select2
							if (sectionFields.subset.data('select2')) {
								sectionFields.subset.select2('destroy');
							}

							// Inset new HTML
							sectionFields.subset.html(html);

							// Init select2
							sectionFields.subset.select2(select2Defaults);

							sectionFields.subset.parent().fadeIn('fast');
							// $( '#' + mainID + ' .typography-family-backup'
							// ).fadeIn( 'fast' );
						}
						else {
							if (details) {
								$.each(
									details,
									function (index, value) {
										if (index === style || index ===
											'normal') {
											selected = ' selected="selected"';
											sectionFields.style.find(
												'.select2-chosen').text(value);
										}
										else {
											selected = '';
										}

										html += '<option value="' + index +
											'"' + selected + '>' +
											value.replace(
												'+', ' ',
											) + '</option>';
									},
								);

								// Destory select2
								if (sectionFields.subset.data('select2')) {
									sectionFields.style.select2('destroy');
								}

								// Insert new HTML
								sectionFields.style.html(html);

								// Init select2
								sectionFields.style.select2(select2Defaults);

								// Prettify things
								sectionFields.subset.parent().fadeOut('fast');
							}
						}

						sectionFields.familyInput.val(family);
					}

					// Check if the selected value exists. If not, empty it.
					// Else, apply it.
					if (sectionFields.style.find(
						'option[value=\'' + style + '\']').length === 0) {
						style = '';
						sectionFields.style.val('');
					}
					else if (style === '400') {
						sectionFields.style.val(style);
					}

					// Weight and italic
					if (style.indexOf('italic') !== -1) {
						sectionFields.preview.css('font-style', 'italic');
						sectionFields.styleInput.val('italic');
						style = style.replace('italic', '');
					}
					else {
						sectionFields.preview.css('font-style', 'normal');
						sectionFields.styleInput.val('');
					}

					sectionFields.weightInput.val(style);

					// Handle empty subset select
					if (sectionFields.subset.find(
						'option[value=\'' + script + '\']').length === 0) {
						script = '';
						sectionFields.subset.val('');
						sectionFields.subsetInput.val(script);
					}

					if (google) {
						loadGoogleFont(family, style, script);
					}

					if (!init) {
						updatePreview($section);
					}

					isSelecting = false;
				}
			},

			makeBool: function (val) {
				if (val == 'false' || val == '0' || val === false || val ===
					0) {
					return false;
				}
				else if (val == 'true' || val == '1' || val === true || val ==
					1) {
					return true;
				}
			},

			size: function (obj) {
				var size = 0,
					key;

				for (key in obj) {
					if (obj.hasOwnProperty(key)) {
						size++;
					}
				}

				return size;
			},

			rangeControl: function () {
				var $ranges = $('.xts-active-section .xts-range-control');

				if ($ranges.length <= 0) {
					return;
				}

				$ranges.each(function () {
					var $range = $(this),
						$input = $range.find('.xts-range-value'),
						$slider = $range.find('.xts-range-slider'),
						$text = $range.find('.xts-range-field-value-text'),
						data = $input.data();

					$slider.slider({
						range: 'min',
						value: data.start,
						min: data.min,
						max: data.max,
						step: data.step,
						slide: function (event, ui) {
							$input.val(ui.value).trigger('change');
							$text.text(ui.value);
						},
					});

					// Initiate the display
					$input.val($slider.slider('value')).trigger('change');
					$text.text($slider.slider('value'));

					$range.addClass('xts-field-inited');
				});

			},

			editorControl: function () {
				var $editors = $('.xts-active-section .xts-editor-control');

				$editors.each(function () {
					var $editor = $(this),
						$field = $editor.find('textarea'),
						language = $field.data('language');

					if ($editor.hasClass('xts-editor-initiated')) {
						return;
					}

					var editorSettings = wp.codeEditor.defaultSettings
						? _.clone(wp.codeEditor.defaultSettings)
						: {};

					editorSettings.codemirror = _.extend(
						{},
						editorSettings.codemirror,
						{
							indentUnit: 2,
							tabSize: 2,
							mode: language,
						},
					);

					var editor = wp.codeEditor.initialize($field,
						editorSettings);

					$editor.addClass('xts-editor-initiated');

				});

			},

			fieldsDependencies: function () {
				var $fields = $('.xts-field[data-dependency]');

				$fields.each(function () {
					var $field = $(this),
						dependencies = $field.data('dependency').split(';');

					dependencies.forEach(function (dependency) {
						if (dependency.length == 0) {
							return;
						}
						var data = dependency.split(':');

						var $parentField = $('.xts-' + data[0] + '-field');

						$parentField.on('change', 'input, select', function (e) {
							testFieldDependency($field, dependencies);
						});

						$parentField.find('input, select').change();
						// console.log($parentField);
					});

				});

				function testFieldDependency($field, dependencies) {
					var show = true;
					dependencies.forEach(function (dependency) {
						if (dependency.length == 0 || show == false) {
							return;
						}
						var data = dependency.split(':'),
							$parentField = $('.xts-' + data[0] + '-field'),
							value = $parentField.find('input, select').val();

						switch (data[1]) {
							case 'equals':
								var values = data[2].split(',');
								show = false;
								for (let i = 0; i < values.length; i++) {
									const element = values[i];
									if (value == element) {
										show = true;
									}
								}
								break;
							case 'not_equals':
								var values = data[2].split(',');
								show = true;
								for (let i = 0; i < values.length; i++) {
									const element = values[i];
									if (value == element) {
										show = false;
									}
								}
								break;
						}

					});


					if (show) {
						$field.addClass('xts-shown').
							removeClass('xts-hidden');
					}
					else {
						$field.addClass('xts-hidden').
							removeClass('xts-shown');
					}
				}

			},

			settingsSearch: function () {

				var $searchForm = $('.xts-options-search'),
					$searchInput = $searchForm.find('input');

				if ($searchForm.length == 0) return;

				$searchForm.find('form').submit(function (e) {
					e.preventDefault();
				});

				var $autocomplete = $searchInput.autocomplete({
					source: function (request, response) {
						response(woodmartConfig.xtsOptions.filter(function (value) {
							return value.text.search(new RegExp(request.term, "i")) != -1
						}));
					},

					select: function (event, ui) {
						var $field = $('.xts-' + ui.item.id + '-field');

						$('.xts-sections-nav a[data-id="' + ui.item.section_id + '"]').click();

						$('.highlight-field').removeClass('highlight-field');
						$field.addClass('highlight-field');

						setTimeout(function () {
							if (!isInViewport($field)) {
								$('html, body').animate({
									scrollTop: $field.offset().top - 200
								}, 400);
							}
						}, 300);
					}

				}).data("ui-autocomplete");

				$autocomplete._renderItem = function (ul, item) {
					var $itemContent = '<i class="el ' + item.icon + '"></i><span class="setting-title">' + item.title + '</span><br><span class="settting-path">' + item.path + '</span>'
					return $("<li>")
						.append($itemContent)
						.appendTo(ul);
				};

				$autocomplete._renderMenu = function (ul, items) {
					var that = this;

					$.each(items, function (index, item) {
						that._renderItemData(ul, item);
					});

					$(ul).addClass("xtemos-settings-result");
				};

				var isInViewport = function ($el) {
					var elementTop = $el.offset().top;
					var elementBottom = elementTop + $el.outerHeight();
					var viewportTop = $(window).scrollTop();
					var viewportBottom = viewportTop + $(window).height();
					return elementBottom > viewportTop && elementTop < viewportBottom;
				};
			},

			instagramAPI: function () {
				$('.xts-instagram_api-control button').on('click', function(e){
					e.preventDefault();

					var redirect_uri = encodeURIComponent($('input[name="redirect_uri"]').val());
					var app_id = $('input[name="xts-woodmart-options[insta_token][app_id]"]').val();
					var app_secret = $('input[name="xts-woodmart-options[insta_token][app_secret]"]').val();

					if ( ! app_id || ! app_secret || ! redirect_uri ) {
						$('.xts-insta-message-section').text('Make sure all fields are filled').removeClass('xts-error').removeClass('xts-success').addClass('xts-warning');
						return;
					}

					$.ajax({
						url: woodmartConfig.ajaxUrl,
						method: 'POST',
						data: {
							action: 'woodmart_save_insta_credentials',
							data: {
								app_id: app_id,
								app_secret: app_secret,
							},
						},
						dataType: 'json',
						success: function (r) {
							window.location.href = 'https://api.instagram.com/oauth/authorize?app_id=' + app_id + '&redirect_uri=' + redirect_uri + '&response_type=code&scope=user_profile,user_media';
						},
						error: function (r) {
							console.log('ajax error', r);
						},
					});
				});
			},

			presetsActive: function() {
				function checkAll() {
					$('.xts-sections-nav li').each(function() {
						var $li = $(this);
						var sectionId = $li.find('a').data('id');

						$('.xts-fields-section[data-id="' + sectionId + '"]').find('.xts-inherit-checkbox-wrapper input').each(function() {
							if (!$(this).prop('checked')) {
								$li.addClass('xts-not-inherit');
							}
						});
					});
				}

				function checkChild() {
					$('.xts-sections-nav .xts-has-child').each(function() {
						var $child = $(this).find('.xts-not-inherit');

						var checkedParent = false;

						if ($child.length > 0) {
							checkedParent = true;
						}

						if (checkedParent) {
							$(this).addClass('xts-not-inherit');
						} else {
							$(this).removeClass('xts-not-inherit');
						}
					});
				}

				checkAll();
				checkChild();

				$('.xts-inherit-checkbox-wrapper input').on('change', function() {
					var sectionId = $(this).parents('.xts-fields-section').data('id');

					var checked = false;
					$(this).parents('.xts-fields-section').find('.xts-inherit-checkbox-wrapper input').each(function() {
						if (!$(this).prop('checked')) {
							checked = true;
						}
					});

					if (checked) {
						$('.xts-sections-nav li a[data-id="' + sectionId + '"]').parent().addClass('xts-not-inherit');
					} else {
						$('.xts-sections-nav li a[data-id="' + sectionId + '"]').parent().removeClass('xts-not-inherit');
					}

					checkChild();
					checkAll();
				});
			},

			optionsPresetsCheckbox: function($checkbox) {
				var $options = $('.xts-options');
				var $fieldsToSave = $options.find('.xts-fields-to-save');

				var $field = $checkbox.parents('.xts-field');
				var checked = $checkbox.prop('checked');
				var name = $checkbox.data('name');

				if (!checked) {
					$field.removeClass('xts-field-disabled');
					addField(name);
				} else {
					$field.addClass('xts-field-disabled');
					removeField(name);
				}

				function addField(name) {
					var current     = $fieldsToSave.val(),
					    fieldsArray = current.split(','),
					    index       = fieldsArray.indexOf(name);

					if (index > -1) {
						return;
					}

					if (current.length == 0) {
						fieldsArray = [name];
					} else {
						fieldsArray.push(name);
					}

					$fieldsToSave.val(fieldsArray.join(','));
				}

				function removeField(name) {
					var current     = $fieldsToSave.val(),
					    fieldsArray = current.split(','),
					    index       = fieldsArray.indexOf(name);

					if (index > -1) {
						fieldsArray.splice(index, 1);
						$fieldsToSave.val(fieldsArray.join(','));
					}
				}
			},

			optionsPresets: function() {
				var $options        = $('.xts-options'),
				    $checkboxes     = $options.find(
					    '.xts-inherit-checkbox-wrapper input'),
				    $presetsWrapper = $options.find('.xts-presets-wrapper'),
				    currentID       = $presetsWrapper.data('current-id'),
				    nonceValue      = $presetsWrapper.find('[name="_wpnonce"]').val(),
				    baseUrl         = $presetsWrapper.data('base-url'),
				    presetUrl       = $presetsWrapper.data('preset-url');

				initSelect2();

				$presetsWrapper.on('click', '.xts-add-new-preset', function(e) {
					e.preventDefault();

					if (isInAction()) {
						return;
					}

					var name = prompt('Enter new preset name', 'New preset');

					if (!name || name.length == 0) {
						return;
					}

					startLoading();

					$.ajax({
						url     : woodmartConfig.ajaxUrl,
						method  : 'POST',
						data    : {
							action  : 'xts_new_preset_action',
							name    : name,
							preset  : currentID,
							security: nonceValue
						},
						dataType: 'json',
						success : function(r) {
							if (r.ui && r.ui.length > 10) {
								// updateUI(r.ui);
								window.location = presetUrl + r.id;
							}
						},
						error   : function(r) {
							window.location = baseUrl;
							console.log('ajax error', r);
						},
						complete: function() {
							stopLoading();
						}
					});

				}).on('click', '.xts-remove-preset-btn', function(e) {
					e.preventDefault();

					if (isInAction() || !confirm(
						'Are you sure you want to remove this preset?')) {
						return;
					}

					var id = $(this).data('id');

					startLoading();

					$.ajax({
						url     : woodmartConfig.ajaxUrl,
						method  : 'POST',
						data    : {
							action  : 'xts_remove_preset_action',
							id      : id,
							preset  : currentID,
							security: nonceValue
						},
						dataType: 'json',
						success : function(r) {
							if (r.ui && r.ui.length > 10) {
								if (id == currentID) {
									window.location = baseUrl;
								} else {
									updateUI(r.ui);
								}
							}
						},
						error   : function(r) {
							window.location = baseUrl;
							console.log('ajax error', r);
						},
						complete: function() {
							stopLoading();
						}
					});
				}).on('submit', 'form', function(e) {
					e.preventDefault();
					var data = [];

					$presetsWrapper.find('form').find('.xts-rule').each(function() {
						data.push({
							type      : $(this).find('.xts-rule-type').val(),
							comparison: $(this).find('.xts-rule-comparison').val(),
							post_type : $(this).find('.xts-rule-post-type').val(),
							taxonomy  : $(this).find('.xts-rule-taxonomy').val(),
							custom    : $(this).find('.xts-rule-custom').val(),
							value_id  : $(this).find('.xts-rule-value-id').val()
						});
					});

					startLoading();

					$.ajax({
						url     : woodmartConfig.ajaxUrl,
						method  : 'POST',
						data    : {
							action  : 'xts_save_preset_conditions_action',
							data    : data,
							preset  : currentID,
							security: nonceValue
						},
						dataType: 'json',
						success : function(r) {
							if (r.ui && r.ui.length > 10) {
								updateUI(r.ui);
								$('.xts-presets-wrapper .xts-presets-response').html('<div class="xts-options-message">' + r.success_msg + '</div>');
							}
						},
						error   : function(r) {
							$('.xts-presets-wrapper .xts-presets-response').html('<div class="xts-notice xts-error">' + r.error_msg + '</div>');
						},
						complete: function() {
							stopLoading();
						}
					});
				}).on('click', '.xts-add-preset-rule', function(e) {
					e.preventDefault();
					var $template = $presetsWrapper.find('.xts-rule-template').clone();
					$template.find('.xts-rule').removeClass('xts-hidden');
					$presetsWrapper.find('.xts-condition-rules').append($template.html());
					initSelect2();
				}).on('click', '.xts-remove-preset-rule', function(e) {
					e.preventDefault();
					$(this).parent().remove();
				}).on('change', '.xts-rule-type', function(e) {
					var $type     = $(this),
					    $rule     = $type.parents('.xts-rule'),
					    $postType = $rule.find('.xts-rule-post-type'),
					    $taxonomy = $rule.find('.xts-rule-taxonomy'),
					    $custom   = $rule.find('.xts-rule-custom'),
					    $valueID  = $rule.find('.xts-rule-value-wrapper'),
					    type      = $type.val();

					switch (type) {
						case 'post_type':
							$postType.removeClass('xts-hidden');
							$taxonomy.addClass('xts-hidden');
							$custom.addClass('xts-hidden');
							$valueID.addClass('xts-hidden');
							break;
						case 'taxonomy':
							$postType.addClass('xts-hidden');
							$taxonomy.removeClass('xts-hidden');
							$custom.addClass('xts-hidden');
							$valueID.addClass('xts-hidden');
							break;
						case 'post_id':
						case 'term_id':
							$postType.addClass('xts-hidden');
							$taxonomy.addClass('xts-hidden');
							$custom.addClass('xts-hidden');
							$valueID.removeClass('xts-hidden');
							break;
						case 'custom':
							$postType.addClass('xts-hidden');
							$taxonomy.addClass('xts-hidden');
							$custom.removeClass('xts-hidden');
							$valueID.addClass('xts-hidden');
							break;
					}
				});

				$checkboxes.on('change', function() {
					woodmartOptionsAdmin.optionsPresetsCheckbox($(this));
				});

				function updateUI(html) {
					$presetsWrapper.html($(html).html());
					initSelect2();
				}

				function initSelect2() {
					$presetsWrapper.find('.xts-condition-rules .xts-rule').each(function() {
						var $rule  = $(this),
						    $field = $rule.find('.xts-rule-value-id');

						$field.select2({
							ajax             : {
								url     : woodmartConfig.ajaxUrl,
								data    : function(params) {
									var query = {
										action  : 'xts_get_entity_ids_action',
										type    : $rule.find('.xts-rule-type').val(),
										security: nonceValue,
										name    : params.term
									};

									return query;
								},
								method  : 'POST',
								dataType: 'json'
								// Additional AJAX parameters go here; see
								// the end of this chapter for the full
								// code of this example
							},
							theme            : 'xts',
							dropdownAutoWidth: false,
							width            : 'resolve'
						});
					});
				}

				function isInAction() {
					return $presetsWrapper.hasClass('xtemos-loading');
				}

				function startLoading() {
					$presetsWrapper.addClass('xtemos-loading');
				}

				function stopLoading() {
					$presetsWrapper.removeClass('xtemos-loading');
				}

			},
		};

		return {
			init: function () {
				$(document).ready(function () {
					woodmartOptionsAdmin.optionsPage();
					woodmartOptionsAdmin.optionsPresets();
					woodmartOptionsAdmin.presetsActive();
					woodmartOptionsAdmin.switcherControl();
					woodmartOptionsAdmin.buttonsControl();
					woodmartOptionsAdmin.checkboxControl();
					woodmartOptionsAdmin.fieldsDependencies();
					woodmartOptionsAdmin.customFontsControl();
					woodmartOptionsAdmin.settingsSearch();
					woodmartOptionsAdmin.instagramAPI();

					woodmartOptionsAdmin.selectControl(true);
					woodmartOptionsAdmin.uploadControl(true);
					woodmartOptionsAdmin.uploadListControl(true);
				});

				$(document).on('widget-updated widget-added', function(e, widget) {
					woodmartOptionsAdmin.selectControl(true);
					woodmartOptionsAdmin.uploadControl(true);
					woodmartOptionsAdmin.uploadListControl(true);
				});

				$(document).on('xts_section_changed', function() {
					setTimeout(function() {
						woodmartOptionsAdmin.typographyControl();
					});
					woodmartOptionsAdmin.selectControl(false);
					woodmartOptionsAdmin.uploadControl(false);
					woodmartOptionsAdmin.uploadListControl(false);
					woodmartOptionsAdmin.colorControl();
					woodmartOptionsAdmin.backgroundControl();
					woodmartOptionsAdmin.rangeControl();
				});
			},
		};

	}());

})(jQuery);

jQuery(document).ready(function () {
	woodmartOptions.init();
});
