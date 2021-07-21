/*
 * Image gallery has dependencies on jquery, slick, accessible-modal-window
 */

const fitCaption = ($c) => {
  const $image = $c.find('img');
  const $caption = $c.find('figcaption');
  const w = $image.width();
  if (w == 0) {
    return;
  }
  const h = $image.height();
  const r = w/h;
  const captionWidth = (w < 300) ? "70vw" : w;


  $caption.css({maxWidth: captionWidth});

  if (r <= 1) {
      $c.addClass('mfp-portrait')
      $c.removeClass('mfp-landscape')
  } else {
      $c.addClass('mfp-landscape')
      $c.removeClass('mfp-portrait')
  }
}

const image_gallery = () => {
  const $gallery = jQuery('.image-gallery')
  if ($gallery.length < 1) return;

  // Fire up the masonry layout
  const $grid = $gallery.masonry({
      columnWidth: '.image-gallery__block-sizer',
      itemSelector: '.image-gallery__block',
  });

  // Reflow content in the layout as each image is loaded
  $grid.imagesLoaded().progress( () => { $grid.masonry('layout') });

      
  const $modals = $gallery.magnificPopup({
      delegate: 'a',
      type: 'image',
      mainClass: 'mfp-fade',
      tLoading: 'Loading image #%curr%...',
      gallery: { enabled: true }, 
      navigateByImgClick: true,
      preload: [0, 1],
      image: {
          tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
          verticalFit: false,
          titleSrc: (item) => {
              return jQuery(item.el).find('figcaption').html();
          }     
      }
  });

  $modals.on('mfpResize mfpImageLoadComplete mfpChange mfpOpen', (e) => {
    if (jQuery.magnificPopup.instance) {
      fitCaption(jQuery.magnificPopup.instance.content);
    }
  });               
}
