(function ($) {

    //Tooltips
    $('#vc_ui-panel-edit-element').on('vcPanel.shown', function () {
        var $tooltips = $('.woodmart-css-tooltip');

        $tooltips.each(function () {
            var $label = $(this).find('.woodmart-tooltip-label');

            $label.remove();
            $(this).addClass('woodmart-tltp').prepend('<span class="woodmart-tooltip-label">' + $(this).data('text') + '</span>');
            $label.trigger('mouseover');
        })

        .off('mouseover.tooltips')

        .on('mouseover.tooltips', function () {
            var $label = $(this).find('.woodmart-tooltip-label'),
                width = $label.outerWidth();

            if ($('body').hasClass('rtl')) {
                $label.css({
                    marginRight: - parseInt(width / 2)
                })
            } else {
                $label.css({
                    marginLeft: - parseInt(width / 2)
                })
            }
        });
    });

    //Hint
    $('#vc_ui-panel-edit-element').on('vcPanel.shown', function () {
        var $panel = $(this);

        $panel.find('.vc_shortcode-param').each(function () {
            var $this = $(this);
            var settings = $this.data('param_settings');

            if (typeof settings != 'undefined' && typeof settings.hint != 'undefined') {
                $this.find('.wpb_element_label').addClass('woodmart-with-hint').append('<div class="woodmart-hint">?<div class="woodmart-hint-content">' + settings.hint + '</div></div>');
            }
        });

        $panel.find('.woodmart-hint').on('hover mouseover', function () {
            var $hint = $(this);

            $hint.removeClass('woodmart-hint-right woodmart-hint-left');

            var hintPos = $hint.offset().left + $hint.find('.woodmart-hint-content').outerWidth();
            var panelPos = $panel.offset().left + $panel.find('.vc_edit_form_elements').width();

            if (hintPos > panelPos) {
                $hint.addClass('woodmart-hint-right')
            } else {
                $hint.addClass('woodmart-hint-left');
            }
        });

    });

})(jQuery);
