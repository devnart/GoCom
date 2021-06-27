(function ($) {

    $('#vc_ui-panel-edit-element').on('vcPanel.shown', function () {
        var shortcode = $(this).data('vc-shortcode');

        if (shortcode != 'woodmart_image_hotspot' && shortcode != 'woodmart_hotspot') return;

        var _background_id = vc.shortcodes.findWhere({ id: vc.active_panel.model.attributes.parent_id }).attributes.params.img;

        $('.woodmart-image-hotspot-preview').each(function () {
            var $preview = $(this);
            var $overlay = $preview.find('.woodmart-image-hotspot-overlay');
            var $positionField = $preview.siblings('.woodmart-image-hotspot-position');
            var isDragging = false;
            var timer;

            $preview.addClass('xtemos-loading');

            $.ajax({
                url: woodmartConfig.ajax,
                dataType: 'json',
                data: {
                    image_id: _background_id,
					action: 'woodmart_get_hotspot_image',
					security: woodmartConfig.get_hotspot_image_nonce,
                },
                success: function (response) {
                    $preview.removeClass('xtemos-loading');

                    if (response.status == 'success') {
                        $preview.find('.woodmart-image-hotspot-image').append(response.html).fadeIn(500);
                        $preview.css('min-width', $preview.find('.woodmart-hotspot-img').outerWidth());
                    } else if (response.status == 'warning') {
                        $preview.remove();
                        $positionField.after(response.html);
                    }
                },
                error: function (response) {
                    console.log('ajax error');
                },
            });

            $overlay.on('mousedown', function (event) {
                isDragging = true;
                event.preventDefault();
            }).on('mouseup', function () {
                isDragging = false;
            }).on('mouseleave', function () {
                timer = setTimeout(function () {
                    $overlay.trigger('mouseup');
                }, 500);
            }).on('mouseenter', function () {
                clearTimeout(timer);
            }).on('mousemove', function (event) {
                if (!isDragging) return;
                setPosition(event);
            }).on('click', function (event) {
                setPosition(event);
            }).on('dragstart', function (event) {
                event.preventDefault();
            });

            function setPosition(event) {
                var position = {
                    x: (event.offsetX / $preview.width() * 100).toFixed(3),
                    y: (event.offsetY / $preview.height() * 100).toFixed(3)
                };

                $preview.find('.woodmart-image-hotspot').css({
                    left: position.x + '%',
                    top: position.y + '%'
                });

                $positionField.attr('value', position.x + '||' + position.y).trigger('change');
            }
        });

    });

})(jQuery);
