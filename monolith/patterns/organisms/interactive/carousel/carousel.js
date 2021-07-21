const carousel_single = {
    cssEase: 'ease-in-out',
    dots: true,
    lazyLoad: 'progressive'
}

const carousel_center = {
    cssEase: 'ease-in-out',
    lazyLoad: 'progressive',
    centerMode: true,
    dots: true,
    centerPadding: '110px',
    sidesToShow: 3,
    arrows: true,
    responsive: [
        {
            breakpoint: 768,
            settings: {
                centerMode: false,
                slidesToShow: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                centerMode: false,
                slidesToShow: 1
            }
        }
    ]        
}

const carousel = () => {
    const $carousels = $('.carousel');
    let config = carousel_single; // default configuration
    if ($carousels.length < 1) return;

    const positionArrowsSingle = (slick) => {
        let buttonTop = slick.slideWidth * .4;
        let buttons = slick.$nextArrow.add(slick.$prevArrow);
        buttons.css({ top: buttonTop });
    }

    const positionArrowsCentered = (slick) => {
        const $currentSlide = $(slick.$slides[slick.currentSlide]);
        const imgHeight = $currentSlide.find('img').height();
        if (imgHeight > 0) {
            const buttonTop = imgHeight * .5;
            const buttons = slick.$nextArrow.add(slick.$prevArrow);
            buttons.css({ top: buttonTop });
        }
    }

    $carousels.on('lazyLoaded', (e, slick,image,imageSource) => {
        
    });
    $carousels.on('init setPosition afterChange', (e,slick) => {
        if ($(e.currentTarget).has('.carousel--single')) {
            positionArrowsSingle(slick);
        }
        if ($(e.currentTarget).has('.carousel--center')) {
            positionArrowsCentered(slick);
        }
    });
    $carousels.map((index,elem) => {
        // override configuration based on class name
        if (elem.classList.contains('carousel--center')) {
            config = carousel_center;
        }
        $(elem).slick(config);
    });
}
