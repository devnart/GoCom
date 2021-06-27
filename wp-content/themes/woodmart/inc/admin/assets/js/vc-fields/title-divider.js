(function ($) {

    var $panel = $('#vc_ui-panel-edit-element');

    $panel.on('vcPanel.shown', function () {
        if (typeof tinyMCE !== 'undefined') {
            if (tinyMCE.get('wpb_tinymce_content')) {
                var _formated_content = tinyMCE.get('wpb_tinymce_content').getContent();
                _formated_content = _formated_content.replace(/<\/p><p>\s<\/p>/g, '</p>');
            }
            tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'wpb_tinymce_content');
        }

        $('.vc_wrapper-param-type-woodmart_title_divider').each(function () {
            var $divider = $(this);
            var $fields = $divider.nextUntil('.vc_wrapper-param-type-woodmart_title_divider, .vc_shortcode-param.woodmart-vc-no-wrap');
            var $wrapper = $('<div class="woodmart-td-wrapper"></div>');
            var $content = $('<div class="woodmart-td-content"></div>');

            $divider.before($wrapper);
            $wrapper.append($divider);

            if ($fields.length) {
                $content.append($fields);
                $wrapper.append($content);
            }
        });

        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'wpb_tinymce_content');
            if (typeof _formated_content !== typeof undefined) {
                tinyMCE.get('wpb_tinymce_content').setContent(_formated_content);
            }
        }

        $panel.trigger('woodDivider.added');
    });

    function hideDividerWrapper($divider) {
        var $wrapper = $divider.parent('.woodmart-td-wrapper');
        if ($divider.hasClass('vc_dependent-hidden')) {
            $wrapper.addClass('vc_dependent-hidden');
        } else {
            $wrapper.removeClass('vc_dependent-hidden');
        }
    }

    $panel.on('change', '.wpb_el_type_woodmart_title_divider', function () {
        hideDividerWrapper($(this))
    });

    $panel.on('woodDivider.added', function () {
        $('.wpb_el_type_woodmart_title_divider').each(function () {
            hideDividerWrapper($(this))
        });
    });

})(jQuery);
