/**
 * Blogsy Tags Input Control - Vanilla JavaScript
 *
 * A pure JavaScript implementation for creating tags on-the-fly
 * Comma and Enter key trigger tag creation
 * No third-party dependencies required
 *
 * @package Blogsy
 * @since 1.0.0
 * @license GPL-2.0+
 */
;( function ( $ ) {

    'use strict';

    wp.customize.controlConstructor['blogsy-tags-input'] = wp.customize.Control.extend(
        {
            ready: function () {

                'use strict';

                var control = this;

                // Initialize tags input controller
                control.initTagsInput();
            },

            /**
             * Initialize Tags Input functionality
             */
            initTagsInput: function () {

                var control       = this,
                    container     = control.container.find( '.blogsy-tags-container' ),
                    input         = container.find( '.blogsy-tags-input' ),
                    hiddenInput   = control.container.find( '.blogsy-tags-value' ),
                    maxTags       = parseInt( container.data( 'max-tags' ) ) || 0,
                    allowDupes    = container.data( 'allow-duplicates' ) === true || container.data( 'allow-duplicates' ) === 'true';

                // Handle keydown events (comma, enter, backspace)
                input.on(
                    'keydown',
                    function ( e ) {
                        var key   = e.key || e.keyCode,
                            value = $( this ).val().trim();

                        // Enter key (13) or Comma (188 or ',')
                        if ( key === 'Enter' || key === 13 || key === ',' || key === 188 ) {
                            e.preventDefault();

                            if ( value ) {
                                // Remove trailing comma if present
                                value = value.replace( /,$/g, '' );

                                if ( value ) {
                                    control.addTag( container, value, maxTags, allowDupes, hiddenInput );
                                    $( this ).val( '' );
                                }
                            }
                        }

                        // Backspace on empty input - remove last tag
                        if ( ( key === 'Backspace' || key === 8 ) && ! value ) {
                            var tags = container.find( '.blogsy-tag' );
                            if ( tags.length > 0 ) {
                                tags.last().remove();
                                control.updateValue( container, hiddenInput );
                            }
                        }
                    }
                );

                // Handle paste event
                input.on(
                    'paste',
                    function ( e ) {
                        setTimeout(
                            function () {
                                var pastedText = input.val(),
                                    tags       = pastedText.split( /[,\n]+/ ); // Split by comma or newline

                                input.val( '' );

                                $.each(
                                    tags,
                                    function ( index, tag ) {
                                        tag = tag.trim();
                                        if ( tag ) {
                                            control.addTag( container, tag, maxTags, allowDupes, hiddenInput );
                                        }
                                    }
                                );
                            },
                            10
                        );
                    }
                );

                // Handle remove button clicks
                container.on(
                    'click',
                    '.blogsy-tag-remove',
                    function () {
                        $( this ).closest( '.blogsy-tag' ).fadeOut(
                            200,
                            function () {
                                $( this ).remove();
                                control.updateValue( container, hiddenInput );
                            }
                        );
                    }
                );

                // Focus input when clicking on container
                container.on(
                    'click',
                    function ( e ) {
                        if ( e.target === this || $( e.target ).hasClass( 'blogsy-tags-container' ) ) {
                            input.focus();
                        }
                    }
                );
            },

            /**
             * Add a new tag
             */
            addTag: function ( container, value, maxTags, allowDuplicates, hiddenInput ) {

                var control = this;

                // Sanitize value
                value = value.trim();

                if ( ! value ) {
                    return false;
                }

                // Check max tags
                var currentTags = container.find( '.blogsy-tag' ).length;
                if ( maxTags > 0 && currentTags >= maxTags ) {
                    control.showNotice( container, 'Maximum ' + maxTags + ' tags allowed' );
                    return false;
                }

                // Check duplicates
                if ( ! allowDuplicates && control.tagExists( container, value ) ) {
                    control.showNotice( container, 'Tag already exists' );
                    return false;
                }

                // Create tag element
                var tag = $( '<span class="blogsy-tag">' )
                    .append( $( '<span class="blogsy-tag-text">' ).text( value ) )
                    .append( $( '<span class="blogsy-tag-remove" role="button" aria-label="Remove tag">' ).html( '&times;' ) );

                // Insert before input
                tag.hide().insertBefore( container.find( '.blogsy-tags-input' ) ).fadeIn( 200 );

                // Update value
                control.updateValue( container, hiddenInput );

                return true;
            },

            /**
             * Check if tag already exists
             */
            tagExists: function ( container, value ) {

                var exists     = false,
                    valueLower = value.toLowerCase();

                container.find( '.blogsy-tag-text' ).each(
                    function () {
                        if ( $( this ).text().toLowerCase() === valueLower ) {
                            exists = true;
                            return false; // Break loop
                        }
                    }
                );

                return exists;
            },

            /**
             * Update hidden input value and customizer setting
             */
            updateValue: function ( container, hiddenInput ) {

                var control = this,
                    tags    = [];

                container.find( '.blogsy-tag-text' ).each(
                    function () {
                        tags.push( $( this ).text() );
                    }
                );

                var value = tags.join( ',' );
                hiddenInput.val( value ).trigger( 'change' );

                // Update customizer setting
                control.setting.set( value );
            },

            /**
             * Show temporary notice
             */
            showNotice: function ( container, message ) {

                var notice = container.find( '.blogsy-tags-notice' );

                if ( notice.length === 0 ) {
                    notice = $( '<div class="blogsy-tags-notice">' ).appendTo( container );
                }

                notice.text( message ).fadeIn( 200 );

                setTimeout(
                    function () {
                        notice.fadeOut( 200 );
                    },
                    2000
                );
            }

        }
    );

}( jQuery ) );
