{
    const $ = jQuery;
    $('.hamburger').on('click', function(e) {
        const $control = $(this);
        const $controlled = $('.mobile-nav');
        const focusOn = $control.data('focus-on');
        const $row = $('.nav-buttons');
        const $quicklinks = $('.nav-buttons .quicklinks');
        $control.toggleClass('hamburger--open');
        $row.toggleClass('bg-gray-700');
        $quicklinks.toggle();
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