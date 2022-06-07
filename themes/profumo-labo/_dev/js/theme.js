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

import './components/header/base';
import './components/header/stickyNav';
import './components/header/matchHeight';
import './components/header/resizeNav';
import './components/footer/base';
import './components/footer/dynamicHeight';
import './components/footer/parallaxEffect';
import './components/home/base';
import './components/home/parallax';
import './components/home/rollOverImage';
import './components/shop/mobileTabToDropdown';
import './components/shop/comment';
import './components/shop/base';
import './components/shop/filter';
import './components/shop/wishlist';
import './components/slider/slickSlider';
import './components/collapse/base';

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
  checkAllChkbx();
  Form.init();
  const topMenu = new TopMenu('#_desktop_top_menu .js-main-menu');

  prestashop.on('updatedAddressForm', () => {
    prestashop.customSelect.init();
  });

  prestashop.on('updatedProduct', (event) => {
    $('.js-product-references').first().replaceWith(event.product_references);
  });

  topMenu.init();

  $('.js-select-link').on('change', ({ target }) => {
    window.location.href = $(target).val();
  });

  /**
   * Loads Youtube Videos on Onas page.
   * This is in regards with the YouTube fullscreen issue. Prestashop wysiwyg are removing some iframe attributes that are given by the youtube
   * and this includes the attribute that is needed to make the fullscreen work. As a fix, we will be populating the iframe code via jquery on an empty div
   * that is being placed on the dashboard wysiwyg for Onas page.
   */
  $('.js-onas-yt-video-top').append('<iframe width="560" height="500" src="https://www.youtube.com/embed/Vt1wUkiPvQI" title="Profumolabo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen"></iframe>');
  $('.js-onas-yt-video-bottom').append('<iframe width="560" height="220" src="https://www.youtube.com/embed/qTXmVYffiBw" title="Profumolabo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
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

function checkAllChkbx() {
  const check_all = document.getElementsByName('check_all');
  $(check_all).on('click', function(){
    $("form#customer-form input[type=checkbox]").prop('checked', $(this).prop('checked'));
  });  
}

var chatbox = document.getElementById("fb-customer-chat");

if(chatbox){
  chatbox.setAttribute("page_id", "110559737836074");
  chatbox.setAttribute("attribution", "biz_inbox");
} 