{
    const $ = jQuery;
    // Quicklinks
    $('.toggle').on('click', function(e) {
        const $control = $(this);
        const $controlled = $('.reveal');
        const focusOn = $control.data('focus-on');
        const $nav = $('.nav-buttons');
        const $util = $('.utility-nav');
        $nav.toggleClass('bg-gray-100');
        $util.toggleClass('bg-gray-100');
        const afterToggle = () => {
            const visible = $controlled.is(':visible');
            $control.attr( {'aria-expanded': visible } );
            $controlled.attr( {'hidden' : !visible } );
            if(visible && focusOn) {
                $(focusOn).focus();
            }
        };

        // Respect the operating system accessibility setting for reduced motion
        const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        $controlled.slideToggle((mediaQuery.matches) ? 0 : 250, afterToggle);
    });
}