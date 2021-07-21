// pull image source from overlay teaser
// add it as background url in style attr

jQuery(document).ready(function ($) {
  $('.teaser--overlay').each(function() {
      var $image = $(this).find('.teaser__image img');
      var $imageSrc = $image[0].currentSrc || $image.attr('src');
      $(this).find('.teaser__image').css('background-image', 'url(' + $imageSrc + ')');
  });
});