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

import { Fancybox } from "@fancyapps/ui";

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
    var scrollTop = $(window).scrollTop();
          
    if (scrollTop > stickyNavTop) { 
      $('.header__nav').addClass('header__nav--sticky');
    } else {
      $('.header__nav').removeClass('header__nav--sticky'); 
    }
  };

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

  // Block reassurance slider
  $('.js-block-reassurance-slider').slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: false,
    responsive: [
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 2,
          dots: true
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          dots: true
        }
      }
    ]
  });

  $('.js-see-the-products').slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: false,
    responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
          dots: true
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          dots: true
        }
      }
    ]
  });

  $('.js-product-single-img').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
  });

  $('.js-home-slider').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    arrow: false,
  });

  /*
  * Override slider for product listings
  */
  const sliderOverrideList = $('#arpl-section-12-31-carousel, #arpl-section-13-27-carousel, #arpl-section-13-28-carousel, #arpl-section-13-25-carousel, #arpl-section-13-26-carousel, #arpl-section-12-27-carousel');

  sliderOverrideList.slick({
    infinite: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    dots: false,
    arrow: true,
    responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
        }
      }
    ]
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
  
  mobileDropdown();
  /*
  * End add dropdown to tabbed list on mobile
  */

  $('.js-comment-close').on('click', function(){
    $('.js-comment-form').slideToggle();
    $(this).toggleClass('product-comment__close--notactive')
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
