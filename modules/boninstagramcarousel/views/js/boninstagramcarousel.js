/**
 * 2015-2017 Bonpresta
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
 *  @copyright 2015-2017 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$(document).ready(function(){
    if (BONINSTAGRAMCAROUSEL_TYPE == 'user') {
        var name = BONINSTAGRAMCAROUSEL_USERID;
        if(typeof(BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL) != 'undefined' && BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL) {
            var bon_class = '';
        } else {
            var bon_class = 'col-xs-12 col-sm-4 col-md-3';
        }
        $.get("https://www.instagram.com/" + name + "/?__a=1", function(json) {
            if (json) {
                edges = json.graphql.user.edge_owner_to_timeline_media.edges;
                var count = 0;
                $.each(edges, function(n, edge) {
                    if (count >= BONINSTAGRAMCAROUSEL_LIMIT) {
                        return false;
                    }
                    $('.instagram-list').append(
                        '<li class="instagram-item '+ bon_class +'"><a href="https://www.instagram.com/p/' + edge.node.shortcode +'" target="_blank" rel="nofollow" title="instagram"><img src="' + edge.node.thumbnail_src +'" alt="instagram" title="instagram"><span class="instagram_cover"></span><span class="instagram_likes">'+ edge.node.edge_liked_by.count +'</span><span class="instagram_comment">'+ edge.node.edge_media_to_comment.count +'</span></a></li>'
                    );
                    count++;
                });
            }
        });
        setTimeout(function () {
            BonOwl();
        }, 1000);
    } else {
        BonOwl();
    }
    function BonOwl() {
        if(typeof(BONINSTAGRAMCAROUSEL_DISPLAY) != 'undefined' && BONINSTAGRAMCAROUSEL_DISPLAY) {
            $('.owl-carousel-instagram').owlCarousel({
                items: BONINSTAGRAMCAROUSEL_NB,
                loop: BONINSTAGRAMCAROUSEL_LOOP,
                margin: BONINSTAGRAMCAROUSEL_MARGIN,
                responsiveClass:true,
                nav: BONINSTAGRAMCAROUSEL_NAV,
                dots: BONINSTAGRAMCAROUSEL_DOTS,
                mouseDrag: true,
                autoplay: true,
                lazyLoad: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                navText: [
                    "❮",
                    "❯"
                ],
                responsive:{
                    0:{
                        items:1,
                    },
                    600:{
                        items:3,
                    },
                    1000:{
                        items: BONINSTAGRAMCAROUSEL_NB,
                    }
                }
            })
        }
    }
});





