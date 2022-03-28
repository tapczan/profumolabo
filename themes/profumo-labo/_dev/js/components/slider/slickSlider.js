/**
* Home page banner slider
*/
$('.js-blockbannerslider').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    arrow: false,
});

/**
* Home page featured product slider
*/
$('.js-blockfeaturedproduct').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
});

/**
* Home page offerta slider
*/
$('.js-blockofferta').slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: false,
    responsive: [
        {
        breakpoint: 992,
        settings: {
            slidesToShow: 2,
            dots: true
        }
        },
        {
        breakpoint: 768,
        settings: {
            slidesToShow: 1,
            dots: true
        }
        }
    ]
});

/**
* Home page reassurance slider
*/
$('.js-block-reassurance-slider').slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: false,
    responsive: [
        {
        breakpoint: 768,
            settings: {
                slidesToShow: 2,
                dots: true
            }
        },
        {
        breakpoint: 481,
            settings: {
                slidesToShow: 1,
                dots: true
            }
        }
    ]
});

/**
* Product Single
*/
$('.js-product-single-img').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
});

/**
* Override slider for product listings
*/
const sliderOverrideList = $(`#arpl-section-13-40-carousel, 
                            #arpl-section-13-26-carousel, 
                            #arpl-section-12-31-carousel, 
                            #arpl-section-13-27-carousel, 
                            #arpl-section-13-28-carousel, 
                            #arpl-section-13-25-carousel, 
                            #arpl-section-12-27-carousel,
                            .js-createit-related-products-slider`);
const sliderBestsellerHome = $(`#arpl-section-5-7-carousel, 
                            #arpl-section-5-8-carousel,
                            #arpl-section-17-38-carousel,
                            #arpl-section-17-39-carousel,
                            #arpl-section-17-35-carousel,
                            #arpl-section-17-36-carousel,
                            #arpl-section-22-60-carousel,
                            #arpl-section-22-64-carousel`);
const silderSixOnDesktop = $(`#arpl-section-8-16-carousel, 
                            #arpl-section-1-15-carousel, 
                            #arpl-section-1-34-carousel, 
                            #arpl-section-1-17-carousel, 
                            #arpl-section-8-19-carousel, 
                            #arpl-section-1-57-carousel`);

sliderOverrideList.slick({
    infinite: false,
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: false,
    arrow: true,
    responsive: [
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 2,
            }
        }
    ]
});

silderSixOnDesktop.slick({
    infinite: false,
    slidesToShow: 6,
    slidesToScroll: 1,
    dots: false,
    arrow: false,
    responsive: [
        {
        breakpoint: 992,
            settings: {
                slidesToShow: 2,
                arrow: true,
            }
        }
    ]
});

function seeMoreBestsellers(carouselSelector, slideSelector, max, wrapperSelector) {
    if($(carouselSelector).hasClass('slick-initialized')){
        var link = $(`${wrapperSelector} .arpl-more-link`).attr('href');
        var showMoreText = $('#hook_footer_before_wrapper').data('show-translation');

        $(slideSelector).each(function(index, value) {
            if(index > max) {
                $(carouselSelector).slick('slickRemove', $(this).data('slick-index') - 1);
            }
        })

        if(!$(`${carouselSelector} .bestseller-see-more__link`).length) {
            $(carouselSelector).slick('slickAdd',`<div><h3><a class="bestseller-see-more__link" href="${link}">${showMoreText}</a></h3></div>`);
        }
    }
}

/* Slick needs no get Reinitialized on window Resize after it was destroyed */
$(window).on('load resize orientationchange', function() {
    sliderBestsellerHome.each(function(){
        var $carousel = $(this);
        /* Initializes a slick carousel only on mobile screens */
        // slick on mobile
        if ($(window).width() > 991) {
            if ($carousel.hasClass('slick-initialized')) {
                $carousel.slick('unslick');
            }
        }else{
            if (!$carousel.hasClass('slick-initialized')) {
                $carousel.slick({
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    mobileFirst: true,
                    dots: false,
                    arrow: true,
                    infinite: false,
                });
            }
        }
    });
    if ($(window).width() < 991) {
        seeMoreBestsellers('#arpl-section-5-7-carousel', '#arpl-section-5-7-carousel .slick-slide', 5, '#arpl-section-5-7')
        seeMoreBestsellers('#arpl-section-5-8-carousel', '#arpl-section-5-8-carousel .slick-slide', 5, '#arpl-section-5-8')
        seeMoreBestsellers('#arpl-section-17-38-carousel', '#arpl-section-17-38-carousel .slick-slide', 5, '#arpl-section-17-38')
        seeMoreBestsellers('#arpl-section-17-39-carousel', '#arpl-section-17-39-carousel .slick-slide', 5, '#arpl-section-17-39')
        seeMoreBestsellers('#arpl-section-17-35-carousel', '#arpl-section-17-35-carousel .slick-slide', 5, '#arpl-section-17-35')
        seeMoreBestsellers('#arpl-section-17-36-carousel', '#arpl-section-17-36-carousel .slick-slide', 5, '#arpl-section-17-36')
        seeMoreBestsellers('#arpl-section-22-60-carousel', '#arpl-section-22-60-carousel .slick-slide', 5, '#arpl-section-22-60')
        seeMoreBestsellers('#arpl-section-22-64-carousel', '#arpl-section-22-64-carousel .slick-slide', 5, '#arpl-section-22-64')
        seeMoreBestsellers('#arpl-section-1-15-carousel', '#arpl-section-1-15-carousel .slick-slide', 6, '#arpl-section-1-15')
        seeMoreBestsellers('#arpl-section-1-34-carousel', '#arpl-section-1-34-carousel .slick-slide', 6, '#arpl-section-1-34')
        seeMoreBestsellers('#arpl-section-8-16-carousel', '#arpl-section-8-16-carousel .slick-slide', 6, '#arpl-section-8-16')
        seeMoreBestsellers('#arpl-section-13-40-carousel', '#arpl-section-13-40-carousel .slick-slide', 5, '#arpl-section-13-40')
        seeMoreBestsellers('#arpl-section-13-26-carousel', '#arpl-section-13-26-carousel .slick-slide', 5, '#arpl-section-13-26')
        seeMoreBestsellers('#arpl-section-12-31-carousel', '#arpl-section-12-31-carousel .slick-slide', 5, '#arpl-section-12-31')
        seeMoreBestsellers('#arpl-section-13-27-carousel', '#arpl-section-13-27-carousel .slick-slide', 6, '#arpl-section-13-27')
        seeMoreBestsellers('#arpl-section-13-28-carousel', '#arpl-section-13-28-carousel .slick-slide', 5, '#arpl-section-13-28')
        seeMoreBestsellers('#arpl-section-13-25-carousel', '#arpl-section-13-25-carousel .slick-slide', 5, '#arpl-section-13-25')
        seeMoreBestsellers('#arpl-section-12-27-carousel', '#arpl-section-12-27-carousel .slick-slide', 5, '#arpl-section-12-27')
    }
});