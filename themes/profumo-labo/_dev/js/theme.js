/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

import $ from 'jquery';
import './boostrap/boostrap-imports';
import 'bootstrap-touchspin';
import 'jquery-hoverintent';
import 'slick-carousel';
import './components/dynamic-bootstrap-components';

import './components/selectors';
import './components/sliders';
import './components/responsive';
import './components/customer';
import './components/quickview';
import './components/product';
import './components/cart/cart';
import './components/cart/block-cart';
import './components/jquery.matchHeight';

import prestashop from 'prestashop';
import EventEmitter from 'events';
import Form from './components/form';
import TopMenu from './components/TopMenu';
import CustomSelect from './components/CustomSelect';

import PageLazyLoad from './components/Lazyload';
import PageLoader from './components/PageLoader';

// import { jarallax, jarallaxVideo } from "jarallax";
import { Fancybox } from "@fancyapps/ui";

// jarallaxVideo();

// jarallax(document.querySelectorAll('.jarallax-section'), {
//   speed: 0.2,
// });

/* eslint-disable */
// "inherit" EventEmitter
for (const i in EventEmitter.prototype) {
  prestashop[i] = EventEmitter.prototype[i];
}
/* eslint-enable */

prestashop.customSelect = new CustomSelect({
  selector: 'select',
  excludeSelector: '.normal-select',
});

prestashop.pageLazyLoad = new PageLazyLoad({
  selector: '.lazyload',
});

prestashop.pageLoader = new PageLoader();

$(document).ready(() => {
  prestashop.customSelect.init();
  accLinksTriggerActive();
  Form.init();
  const topMenu = new TopMenu('#_desktop_top_menu .js-main-menu');

  prestashop.on('updatedAddressForm', () => {
    prestashop.customSelect.init();
  });

  topMenu.init();

  $('.js-select-link').on('change', ({target}) => {
    window.location.href = $(target).val();
  });

  // Navigation script
  var stickyNavTop = $('.header__nav').offset().top;
 
  function stickyNav(){
    var removalStickyNavPoint = $('#blockEmailSubscription_displayFooterBefore').offset().top;
    var removalStickyNavPintHeight = $('#blockEmailSubscription_displayFooterBefore').innerHeight();
    var stickytNavHeigh = $('.header__nav').innerHeight();
    var scrollTop = $(window).scrollTop();
    if (scrollTop > stickyNavTop && scrollTop < (removalStickyNavPoint + removalStickyNavPintHeight - stickytNavHeigh)) { 
      $('.header__nav').addClass('header__nav--sticky');
      $('.sticky-menu-correction').addClass('correction-padding');
    } else {
      $('.header__nav').removeClass('header__nav--sticky');
      $('.sticky-menu-correction').removeClass('correction-padding');
    }
  };
  
  function footerParalaxEffect() {
    const placeholder = $('.paralax-placeholder');
    const footer = $('.footer-container');

    let placeholderTop
    let ticking
    $(window).on('resize', onResize)

    updateHolderHeight()
    checkFooterHeight()

    function onResize() {
      updateHolderHeight()
      checkFooterHeight()
    }

    function updateHolderHeight() {
      placeholder.css('height', `${footer.outerHeight()}px`)
    }

    function checkFooterHeight() {
      if (footer.outerHeight() > $(window).innerHeight()) { 
        $(window).on('scroll', onScroll);
        footer.css('bottom', '0')
        footer.css('top', 'unset')
      } else {
        $(window).off("scroll", onScroll);
        footer.css('top', 'unset');
        footer.css('bottom', '0');
      }
    }

    function onScroll() {
      placeholderTop = Math.round(placeholder[0].getBoundingClientRect().top) 
      requestTick()
    }

    function requestTick() {
      if (!ticking) requestAnimationFrame(updateBasedOnScroll)
      ticking = true
    }

    function updateBasedOnScroll() {
      ticking = false

      if (placeholderTop <= 0) {
        footer.css('top', `${placeholderTop}px`)
      }
    }
  }
  
  footerParalaxEffect()

  function rolloverImages() {
    $('.product-miniature__thumb').each(function(){
      let newSrc = $(this).find('.rollover-images').data('rollover');
      if(newSrc == 0) return;
      let oldSrc;
      $(this).on("mouseover", function() {
        oldSrc = $(this).find('.rollover-images').attr('src');
        $(this).find('.rollover-images').attr('src', newSrc).stop(true,true);
        $(this).css('background', '#f4f4f4');
      }), 
      $(this).on('mouseout', function() {
        $(this).find('.rollover-images').attr('src', oldSrc).stop(true,true);
        $(this).css('background', 'none');
      });
    });
  }
  rolloverImages();

  function matchHeightScripts(){
    $('.mega-menu-header-kobieta .mm_columns_li').matchHeight();
    $('.mega-menu-header-mezczyzna .mm_columns_li').matchHeight();
    $('.mega-menu-header-kolekje .mm_columns_li').matchHeight();
    $('.mega-menu-header-marki .mm_columns_li').matchHeight();
    $('.mega-menu-header-kontakt .mm_columns_li').matchHeight();
  }

  function resizeAddClassNav(){
    var winW = $(window).outerWidth();

    if( winW >= 992 ){
      $('.ets_mm_megamenu').removeClass('changestatus');
    }

    if( winW <= 991 ){
      $('.ets_mm_megamenu').addClass('changestatus');
    }
  }
  
  stickyNav();
  resizeAddClassNav();
  matchHeightScripts();

  $(window).on('scroll', function() {
    stickyNav();
  });

  $('.jsSearchToggleMobile').on('click', function(){
    $('.jsMobileSearch').toggle();
  });

  $(window).on('load', function(){
    matchHeightScripts();
    resizeAddClassNav();

    $(window).on('resize', function(){
      resizeAddClassNav();
    });
  });

  // product single
  $('.js-product-single-img').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
  });

  // home page banner slider
  $('.js-blockbannerslider').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    arrow: false,
  });

  // home page featured product slider
  $('.js-blockfeaturedproduct').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
  });

  // home page offerta slider
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

  // home page reassurance slider
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

  /*
  * Override slider for product listings
  */
  const sliderOverrideList = $(`#arpl-section-13-40-carousel, 
                                #arpl-section-13-26-carousel, 
                                #arpl-section-12-31-carousel, 
                                #arpl-section-13-27-carousel, 
                                #arpl-section-13-28-carousel, 
                                #arpl-section-13-25-carousel, 
                                #arpl-section-12-27-carousel`);
  const sliderBestsellerHome = $('#arpl-section-5-7-carousel, #arpl-section-5-8-carousel');
  const silderSixOnDesktop = $('#arpl-section-8-16-carousel, #arpl-section-1-15-carousel, #arpl-section-1-34-carousel, #arpl-section-1-17-carousel, #arpl-section-8-19-carousel, #arpl-section-1-57-carousel');

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
      }
      else{
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
  
                                
    
  $('.js-filtermobile-slider').on('click', function(){
    $(this).toggleClass('istoggled');
    $('.js-search-filters').slideToggle();
  });
  /*
  * End override slider for product listings
  */

  /*
  * Add dropdown to tabbed list on mobile
  */
  function mobileDropdown(){
    var winW = $(window).innerWidth();
    var mobileDropdownSection = $('#arpl-group-13');
    var mobileDropdownElem = $('#arpl-group-13 .nav-tabs');
    var mobileDropdownElemActive = $('#arpl-group-13 .nav-link.active').text();

    if( $('.nav-tabs-mobile-label').length == 0 ){
      mobileDropdownSection.prepend('<h3 class="nav-tabs-mobile-label">' + mobileDropdownElemActive + '</h3>');
    }

    $('#arpl-group-13 .nav-link').each(function(){
      $(this).on('click', function(){
        var activeMobileDropdownText = $(this).text();

        $('.nav-tabs-mobile-label').text(activeMobileDropdownText);
        mobileDropdownElem.hide();
      });
    });

    $('.nav-tabs-mobile-label').on('click', function(){
      mobileDropdownElem.toggle();
    });
  }
  
  if( $('#arpl-group-13 .nav-item').length > 1 ){
    mobileDropdown();
  }else{
    $('#arpl-group-13').addClass('no-tab-available')
  }
  /*
  * End add dropdown to tabbed list on mobile
  */

  $('.js-comment-close').on('click', function(){
    $('.js-comment-form').slideToggle();
    $(this).toggleClass('product-comment__close--notactive')
  });

  /*
  * Clear comment input fields and close popup form on submit
  */

  var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutationRecord) {
      setTimeout(() => {
        $('.js-trigger-click-submit')[0].click();
        $('.js-input-comment, .js-textarea-comment').val('');
      }, 100);
    });    
  });

  var targetElement = document.getElementsByClassName('js-comment-alert')[0];

  if( targetElement ){
    observer.observe(targetElement, { attributes : true, attributeFilter : ['style'] });
  }

  /*
  * Reload page on success wishlist
  */
  var currentWindowURL = window.location.href;
  var observerWishlist = new MutationObserver((mutations) => { 
      mutations.forEach((mutation) => {
        const el = mutation.target;
        if ((!mutation.oldValue || !mutation.oldValue.match(/\bisActive\b/)) 
          && mutation.target.classList 
          && mutation.target.classList.contains('isActive')){
            const wishlistToastText = $('.wishlist-toast').text().trim();
            const wishlistCounterTop = $('.js-wishlist-counter-top');
            const wishlistCounterNav = $('.js-wishlist-counter-nav');
            const wishlistButtonAdd = $('.wishlist-button-add');
            let wishlistTopAdd;

            if(wishlistToastText == 'Product added' || wishlistToastText == 'Produkt dodany'){
              wishlistTopAdd = parseInt(wishlistCounterTop.text()) + parseInt(1);
              wishlistCounterTop.text(wishlistTopAdd);
              wishlistCounterNav.text(wishlistTopAdd);
              wishlistButtonAdd.addClass('wishlist-button-wait');

              setTimeout(() => {
                wishlistButtonAdd.removeClass('wishlist-button-wait');
              }, 3000);
            }
            
            if(wishlistToastText == 'Product successfully removed'){
              wishlistTopAdd = parseInt(wishlistCounterTop.text()) - parseInt(1);
              wishlistCounterTop.text(wishlistTopAdd);
              wishlistCounterNav.text(wishlistTopAdd);
              wishlistButtonAdd.addClass('wishlist-button-wait');

              setTimeout(() => {
                wishlistButtonAdd.removeClass('wishlist-button-wait');
              }, 3000);
            }
            
            if(wishlistToastText == 'List has been removed' || wishlistToastText == 'Lista została usunięta'){
              setTimeout(() => {
                $(location).prop('href', currentWindowURL);
              }, 500);  
            }
        }
      });
  });
  
  var targetElementWishlist = document.getElementsByClassName('wishlist-toast')[0];
  var targetElementWishlistJquery = $('.wishlist-toast');
  
  if(targetElementWishlistJquery.length > 0){
    observerWishlist.observe(targetElementWishlist, { 
      attributes: true, 
      attributeOldValue: true, 
      attributeFilter: ['class'] 
    });
  }

  /*
  * Footer dynamic height and spaces
  */
  function dynamicSpaceAndWidth(){
    const winHeight = parseInt($(window).innerHeight());
    const footerItemsHeight = parseInt($('.js-footer-items').innerHeight());
    const footerLogoHeightBase = ((winHeight - footerItemsHeight) / 4) * 3;
    const marginLogoBase = ((winHeight - footerItemsHeight) / 4) / 2;

    $('.js-footer-logo-img').css({
      'height' : footerLogoHeightBase,
      'margin-top' : marginLogoBase,
      'margin-bottom' : marginLogoBase
    });
  }

  dynamicSpaceAndWidth();

  $(window).on('load resize', function(){
    dynamicSpaceAndWidth();
  });

  $('#accordionFooter').on('shown.bs.collapse', function (e) {
    dynamicSpaceAndWidth();
  });

  /**
   * Parallax effect above offerta
   * @param {int} canvasFrameCount - the value of how many sequence of images you will be placing for the effect
   * @param {int} jsCanvasSectionOffset - get canvas section offset top
   * @param {int} jsCanvasStart - get the vertical scroll position of the canvas that will serve as the starting point value of the canvas scroll animation
   * @param {int} jsCanvasMaximum - get the end (or maximum) value that will serve as the end point value of the canvas scroll animation
   * @param {int} jsCanvasProgress - get users scroll progress
   * @param {int} jsCanvasBottomValue - get canvas section wrapper distance to the top of the browser which will give some bottom value for the canvas element
   * @param {int} canvasImgWidth - width of the actual picture for responsive compatibility
   * @param {int} canvasImgHeight - height of the actual picture for responsive compatibility
   */
  const canvas = $("#js-canvas-offerta");
  if(canvas.length){
    const canvasContext = canvas[0].getContext('2d');
    const canvasFrameCount = 40;
    const winLocationOrigin = window.location.origin;

    const canvasCurrentFrame = index => (
      `${winLocationOrigin}/img/cms/parallax/canvas-offerta-${index.toString().padStart(3, '0')}.jpg`
    );

    const preloadCanvasImage = () => {
      for (let i = 1; i < canvasFrameCount; i++) {
        const canvasImgPreload = new Image();
        canvasImgPreload.src = canvasCurrentFrame(i);
      }
    };

    var canvasImg = new Image();
    var canvasImgWidth = 3840;
    var canvasImgHeight = 1718;
          
    function initCanvas() {
      canvasContext.canvas.width = canvasImgWidth;
      canvasContext.canvas.height = canvasImgHeight;

      drawCanvas(); 
    }
          
    function drawCanvas() {
      canvasImg.src = canvasCurrentFrame(1);
    }

    canvasImg.onload=function(){
      canvasContext.drawImage(canvasImg, 0, 0);
    }

    const updateImage = index => {
      canvasImg.src = canvasCurrentFrame(index);
      canvasContext.drawImage(canvasImg, 0, 0);
    }
    
    initCanvas();

    preloadCanvasImage();

    $(window).on('scroll', function() {
      const jsAfterCanvasSection = $(".blockhomesections--profumo");
      const jsAfterCanvasSectionOffset = jsAfterCanvasSection.offset().top;
      const jsCanvasSection = $(".js-canvas-parallax");
      const jsCanvasSectionOffset = jsCanvasSection.offset().top;
      const jsCanvasStart = $(this).scrollTop() + $(this).height();
      const jsCanvasMaximum = jsCanvasStart - jsCanvasSectionOffset;
      const jsCanvasProgress = jsCanvasMaximum / (jsCanvasSection.height() * 2);
      const jsCanvasElement = $('#js-canvas-offerta');  
      const jsCanvasSectionNative = document.getElementsByClassName("js-canvas-parallax")[0];
      const jsCanvasBottomValue = jsCanvasSectionNative.getBoundingClientRect().top; 

      if (jsCanvasStart >= jsCanvasSectionOffset) {
        const frameIndex = Math.min(
          canvasFrameCount - 1,
          Math.ceil(jsCanvasProgress * canvasFrameCount)
        );

        requestAnimationFrame(() => updateImage(frameIndex + 1));
        jsCanvasElement.css('position', 'fixed');
      }

      if (jsCanvasStart >= jsAfterCanvasSectionOffset) {
        jsCanvasElement.css('position', 'absolute');
      }
    });
  }
  /**
   * End parallax effect above offerta
   */

  
  /**
   * Used by collapsed template (FAQ Page)
   * Converts tabs to accordion or collapse style on mobile responsive
   */
  $('.js-trigger-collapsed-mobile').on('click', function(){
    $(this).closest('.collapsed__tab-pane').find('.collapsed__collapse--mobile').toggle();
    $(this).toggleClass('collapsed__collapse--mobile-active');
  });
  
  /**
   * Product category filter show and hide
   */
  $('.js-filter-top-show').on('click', function(){
    $('.js-filter-wrapper').toggleClass('filter-wrapper--show');
    $('.js-listing-wrapper').toggleClass('listing-wrapper--default');
  });
  
  /**
   * Used by collapsed cms template (Information Page)
   */
  const cmsCollapseTitle = $('.js-collapse-no-tab .collapsed__collapse-title');

  cmsCollapseTitle.on('click', function(){
    $(this).toggleClass('collapsed__collapse-title--active');
    $(this).closest('.collapsed__collapse').find('.collapsed__collapse-content').toggle();
  });

});

function accLinksTriggerActive() {
  const url = window.location.pathname;
  $('.js-customer-links a').each((i, el) => {
    const $el = $(el);

    if ($el.attr('href').indexOf(url) !== -1) {
      $el.addClass('active');
    }
  });
}
