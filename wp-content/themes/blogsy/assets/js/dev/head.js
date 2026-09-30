(function() {

    'use strict';

    // Dark Mode (Fix cache plugins)
    const htmlElement = document.documentElement;
    if(htmlElement.hasAttribute('scheme') && !htmlElement.hasAttribute('dark-theme') && navigator.cookieEnabled) {

        let cookies = document.cookie;
        if( cookies.includes('blogsyDarkMode=enabled') ) {
            htmlElement.setAttribute('scheme', 'dark');
        }
        else if( cookies.includes('blogsyDarkMode=disabled') ) {
            htmlElement.setAttribute('scheme', 'light');
        }
        else if(htmlElement.getAttribute('scheme') === 'device') {
            if(window.matchMedia('(prefers-color-scheme: dark)').matches) {
                htmlElement.setAttribute('scheme', 'dark');
            }
            else {
                htmlElement.setAttribute('scheme', 'light');
            }
        }
    }

})();
