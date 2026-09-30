// Blogsy Metabox Media Uploader
jQuery(document).ready(function($){
    // Single image
    $(document).on('click', '.blogsy-upload-image', function(e){
        e.preventDefault();
        var button = $(this);
        var target = $('#' + button.data('target'));
        var preview = button.siblings('.blogsy-image-preview');
        var frame = wp.media({
            title: 'Select Image',
            multiple: false,
            library: { type: 'image' }
        });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            target.val(attachment.id);
            preview.html('<img src="' + attachment.sizes.thumbnail.url + '" style="max-width:100px;max-height:100px;" />');
        });
        frame.open();
    });

    // Gallery (multiple images)
    $(document).on('click', '.blogsy-upload-gallery', function(e){
        e.preventDefault();
        var button = $(this);
        var target = $('#' + button.data('target'));
        var preview = button.siblings('.blogsy-gallery-preview');
        var frame = wp.media({
            title: 'Select Images',
            multiple: true,
            library: { type: 'image' }
        });
        frame.on('select', function(){
            var selection = frame.state().get('selection');
            var ids = [];
            var html = '';
            selection.each(function(attachment){
                attachment = attachment.toJSON();
                ids.push(attachment.id);
                html += '<img src="' + attachment.sizes.thumbnail.url + '" style="max-width:100px;max-height:100px;margin-right:5px;" />';
            });
            target.val(ids.join(','));
            preview.html(html);
        });
        frame.open();
    });

});

(function($){
    let lastFormat = null;

    function updateBlogsyMetaboxFieldsByFormat(selectedFormat) {
        selectedFormat = selectedFormat || 'standard';
        console.log('Post format changed to:', selectedFormat);

        $('.blogsy-metabox-field').each(function(){
            const $field = $(this);
            const requireFormat = $field.data('require-post_format');

            if (!requireFormat || requireFormat === selectedFormat) {
                $field.show();
            } else {
                $field.hide();
            }
        });
    }

    function initForGutenberg() {
        wp.domReady(function() {
            // Run once on load
            updateBlogsyMetaboxFieldsByFormat(
                wp.data.select('core/editor').getEditedPostAttribute('format')
            );

            // Subscribe to format changes only
            wp.data.subscribe(function() {
                const currentFormat = wp.data.select('core/editor').getEditedPostAttribute('format') || 'standard';
                if (currentFormat !== lastFormat) {
                    lastFormat = currentFormat;
                    updateBlogsyMetaboxFieldsByFormat(currentFormat);
                }
            });
        });
    }

    function initForClassic() {
        function getFormatFromDOM() {
            return $("input[name^='post-format-']:checked").val() || 'standard';
        }

        // Run once on load
        $(document).ready(function() {
            updateBlogsyMetaboxFieldsByFormat(getFormatFromDOM());
        });

        // Watch for format change
        $(document).on('change', "input[name^='post-format-']", function(){
            updateBlogsyMetaboxFieldsByFormat(getFormatFromDOM());
        });
    }

    // Decide which editor is active
    if (typeof wp !== 'undefined' && wp.data && wp.data.select('core/editor')) {
        initForGutenberg();
    } else {
        initForClassic();
    }

})(jQuery);
