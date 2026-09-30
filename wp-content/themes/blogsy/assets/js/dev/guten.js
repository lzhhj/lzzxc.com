(function($){
    let swiperInstances = new WeakMap(); // Track swiper instances per element

    function blogsyGutenSlider(el) {
        const $sliderWrapper = el.find('.blogsy-posts-carousel-wrapper');
        if (!$sliderWrapper.length) return;

        // Destroy existing swiper if any
        destroySlider(el);

        // Avoid initializing the same slider multiple times unnecessarily
        if (el.data('guten-slider-initialized')) return;
        el.data('guten-slider-initialized', true);

        // Function to initialize the slider
        function initSlider() {
            if (!$sliderWrapper.children().find('li').length) return;

            // Add 'swiper-slide' class
            const $slides = $sliderWrapper.children().find('li').addClass('swiper-slide');

            // Add navigation if more than 1 slide
            if ($slides.length > 1 && el.find('.guten-nav-wrapper').length === 0) {
                el.append(`
                    <div class="guten-nav-wrapper guten-hide-mobile-tablet">
                        <a href="javascript:void(0);" class="guten-nav-prev" aria-label="Previous slide">
                            <span class="blogsy-svg-icon" aria-hidden="true">
                                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14.143 3.27c.34-.352.901-.361 1.252-.02l8.337 8.112a.89.89 0 0 1 0 1.276l-8.337 8.112a.884.884 0 0 1-1.252-.02.891.891 0 0 1 .02-1.255l6.77-6.588H.884A.886.886 0 0 1 0 12c0-.49.396-.887.884-.887h20.049l-6.77-6.588a.891.891 0 0 1-.02-1.255Z"></path></svg>
                            </span>
                        </a>
                        <a href="javascript:void(0);" class="guten-nav-next" aria-label="Next slide">
                            <span class="blogsy-svg-icon" aria-hidden="true">
                                <svg width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14.143 3.27c.34-.352.901-.361 1.252-.02l8.337 8.112a.89.89 0 0 1 0 1.276l-8.337 8.112a.884.884 0 0 1-1.252-.02.891.891 0 0 1 .02-1.255l6.77-6.588H.884A.886.886 0 0 1 0 12c0-.49.396-.887.884-.887h20.049l-6.77-6.588a.891.891 0 0 1-.02-1.255Z"></path></svg>
                            </span>
                        </a>
                    </div>
                `);
            }

            // Add pagination if more than 1 slide
            if ($slides.length > 1 && el.find('.guten-pagination-wrapper').length === 0) {
                $sliderWrapper.append(`
                    <div class="guten-pagination-wrapper guten-hide-desktop">
                        <div class="guten-pagination"></div>
                    </div>
                `);
            }

            // Swiper settings
            const sliderSettings = {
                autoplay: { delay: 6000, disableOnInteraction: false },
                loop: $slides.length > 1,
                speed: 2000,
                effect: "fade",
                parallax: true,
                loopedSlides: $slides.length > 1 ? $slides.length : 0,
                touchRatio: 0.2,
                navigation: {
                    nextEl: el.find('.guten-nav-next')[0],
                    prevEl: el.find('.guten-nav-prev')[0]
                },
                pagination: {
                    el: el.find('.guten-pagination')[0],
                    type: "bullets",
                    clickable: true,
                    dynamicBullets: false
                },
                a11y: { enabled: false },
                fadeEffect: { crossFade: true },
                observer: true,
                observeParents: true
            };

            // Initialize Swiper and store instance
            const swiper = new Swiper($sliderWrapper[0], sliderSettings);
            swiperInstances.set(el[0], swiper);
        }

        function destroySlider(el) {
            const swiper = swiperInstances.get(el[0]);
            if (swiper && !swiper.destroyed) {
                swiper.destroy(true, true);
                swiperInstances.delete(el[0]);
            }
        }

        // Enhanced MutationObserver for Query Loop patterns
        const observerConfig = {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'style']
        };

        const observer = new MutationObserver((mutations) => {
            let shouldInit = false;

            mutations.forEach((mutation) => {
                // Detect when Query Loop posts are loaded (li elements appear)
                if (mutation.type === 'childList' &&
                    mutation.addedNodes.length > 0 &&
                    $sliderWrapper.children().find('li').length >= 2) {
                    shouldInit = true;
                }
                // Detect class changes that might indicate content ready
                if (mutation.type === 'attributes' &&
                    mutation.target === $sliderWrapper[0] &&
                    mutation.attributeName === 'class') {
                    shouldInit = true;
                }
            });

            if (shouldInit) {
                // Small delay to ensure Query Loop completes rendering
                setTimeout(() => {
                    initSlider();
                }, 100);
                observer.disconnect();
            }
        });

        // Start observing immediately
        if ($sliderWrapper[0]) {
            observer.observe($sliderWrapper[0], observerConfig);
        }

        // Also try initializing immediately (frontend)
        setTimeout(initSlider, 50);
    }

    // Initialize all sliders on DOM ready
    $(document).ready(function() {
        $('.guten-slider').each(function() {
            blogsyGutenSlider($(this));
        });
    });

    // Re-initialize on window load (Query Loop needs this)
    $(window).on('load', function() {
        $('.guten-slider').each(function() {
            blogsyGutenSlider($(this));
        });
    });

    // Gutenberg: Multiple detection methods for immediate pattern insertion
    if (typeof wp !== 'undefined' && wp.data && wp.data.subscribe) {
        let blockCount = 0;

        // Method 1: Track block insertions
        wp.data.subscribe(() => {
            const blocks = wp.data.select('core/block-editor').getBlocks();
            const currentCount = blocks.length;

            if (currentCount !== blockCount) {
                blockCount = currentCount;
                setTimeout(() => {
                    $('.guten-slider').each(function() {
                        blogsyGutenSlider($(this));
                    });
                }, 50);
            }
        });

        // Method 2: Editor canvas mutations (most reliable for patterns)
        const initEditorObserver = () => {
            const canvas = document.querySelector('iframe[name="editor-canvas"]');
            if (canvas && canvas.contentDocument) {
                const editorDoc = canvas.contentDocument;
                const observer = new MutationObserver(() => {
                    $('.guten-slider', editorDoc).each(function() {
                        blogsyGutenSlider($(this));
                    });
                });
                observer.observe(editorDoc.body, {
                    childList: true,
                    subtree: true
                });
            }
        };

        // Wait for editor iframe to load
        const checkEditorIframe = setInterval(() => {
            if (document.querySelector('iframe[name="editor-canvas"]')) {
                clearInterval(checkEditorIframe);
                setTimeout(initEditorObserver, 500);
            }
        }, 200);
    }

    // Fallback: Periodic check for Gutenberg editor
    if (document.body.classList.contains('block-editor-page')) {
        setInterval(() => {
            $('.guten-slider').each(function() {
                if (!$(this).data('guten-slider-initialized')) {
                    blogsyGutenSlider($(this));
                }
            });
        }, 500);
    }

})(jQuery);
