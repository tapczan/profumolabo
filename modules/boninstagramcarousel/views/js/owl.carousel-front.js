/**
 * 2015-2021 Bonpresta
 *
 * Bonpresta Responsive Carousel Feed Instagram Images
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    Bonpresta
 *  @copyright 2015-2021 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$(document).ready(function () {
    setTimeout(() => {
        setTimeInst();
    }, 400);
    function setTimeInst() {
        $('#boninstagram .instagram-carousel-container').show();
    }
    BonOwl();

    function BonOwl() {
        if (BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL == 1) {
            $('.instagram-list').owlCarousel({
                items: parseInt(BONINSTAGRAMCAROUSEL_NB),
                loop: parseInt(BONINSTAGRAMCAROUSEL_LOOP) ? true : false,
                margin: parseInt(BONINSTAGRAMCAROUSEL_MARGIN),
                responsiveClass: true,
                nav: parseInt(BONINSTAGRAMCAROUSEL_NAV) ? true : false,
                dots: parseInt(BONINSTAGRAMCAROUSEL_DOTS) ? true : false,
                mouseDrag: true,
                autoplay: true,
                lazyLoad: true,
                autoplayTimeout: parseInt(BONINSTAGRAMCAROUSEL_SPEED),
                autoplayHoverPause: true,
                navText: [
                    "❮",
                    "❯"
                ],
                // responsive: {
                //     0: {
                //         items: 1,
                //     },
                //     600: {
                //         items: 3,
                //     },
                //     1000: {
                //         items: parseInt(BONINSTAGRAMCAROUSEL_NB),
                //     }
                // }
            })
        }
    }
});