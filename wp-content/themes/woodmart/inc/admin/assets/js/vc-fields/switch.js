(function ($) {

    var $panel = $('#vc_ui-panel-edit-element');

    $panel.on('vcPanel.shown', function () {

        $('.woodmart-vc-switch').each(function () {
            var $this = $(this);
            var currentValue = $this.find('.switch-field-value').val();

            $this.find('[data-value="' + currentValue + '"]').addClass('checked');
        });

        $('.switch-controls').on('click', function () {
            var $this = $(this);
            var value = $this.data('value');

            $this.addClass('checked');
            $this.siblings().removeClass('checked');
            $this.parents('.woodmart-vc-switch').find('.switch-field-value').val(value).trigger('change');
        });

    });

})(jQuery);
