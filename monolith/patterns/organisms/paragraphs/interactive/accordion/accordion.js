const accordion = () => {
    jQuery('.js-accordion').accordion();
    jQuery('.accordion__header').on('click', function() {
        jQuery(this).toggleClass('active');
    });
}