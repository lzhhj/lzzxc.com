wp.customize.controlConstructor['blogsy-image'] = wp.customize.Control.extend({
    ready: function() {
        var control = this;

        // When the select image button is clicked
        control.container.on('click', '.blogsy-upload-button', function(e) {
            e.preventDefault();

            // If the media frame already exists, reopen it.
            if (control.mediaFrame) {
                control.mediaFrame.open();
                return;
            }

            // Create a new media frame
            control.mediaFrame = wp.media({
                title: control.params.l10n.selectOrUploadImage,
                button: {
                    text: control.params.l10n.useThisImage
                },
                multiple: false
            });

            // When an image is selected in the media frame...
            control.mediaFrame.on('select', function() {
                var attachment = control.mediaFrame.state().get('selection').first().toJSON();
                control.setting.set(attachment.url);
                control.container.find('.blogsy-image-preview img').attr('src', attachment.url).show();
                control.container.find('.blogsy-remove-button').show();
                control.container.find('.blogsy-upload-button').text(control.params.l10n.changeImage);
                control.container.find('.blogsy-upload-button').removeClass('button-add-media');
            });

            // Finally, open the modal on click
            control.mediaFrame.open();
        });

        // When the remove image button is clicked
        control.container.on('click', '.blogsy-remove-button', function(e) {
            e.preventDefault();
            control.setting.set('');
            control.container.find('.blogsy-image-preview img').attr('src', '').hide();
            control.container.find('.blogsy-remove-button').hide();
            control.container.find('.blogsy-upload-button').text(control.params.l10n.selectImage);
            control.container.find('.blogsy-upload-button').addClass('button-add-media');
        });
    }
});
