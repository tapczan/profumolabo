{**
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
 *}
<!doctype html>
<html lang="{$language.locale}">

  <head>
    {block name='head'}
      {include file='_partials/head.tpl'}
    {/block}
  </head>

  <body id="{$page.page_name}" class="{$page.body_classes|classnames} {if $page.page_name == 'index'} page-product {/if} no-js {if $logged}js-header-user-login{/if}">
  <!-- Messenger Chat Plugin Code -->
  <div id="fb-root"></div>

  <!-- Your Chat Plugin code -->
  <div id="fb-customer-chat" class="fb-customerchat"></div>

  <script>
    if(! navigator.userAgent.match(/nsights|ighth/i)) {
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "110559737836074");
      chatbox.setAttribute("attribution", "biz_inbox");

      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v14.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/pl_PL/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    }
  </script>

    {block name='hook_after_body_opening_tag'}
      {hook h='displayAfterBodyOpeningTag'}
    {/block}

    {hook h='displayX13InfoBar'}

    <main class="l-main">
      {block name='product_activation'}
        {include file='catalog/_partials/product-activation.tpl'}
      {/block}

      <header id="header" class="l-header">
        {block name='header'}
          {include file='_partials/header.tpl'}
        {/block}
      </header>
 
      {if $page.page_name == 'index'}
        <div class="row mx-0 sticky-menu-correction homepage-slider-wrapper">
          <div style="width:100%; margin: 0 auto;">
           {widget name='is_imageslider'}
          </div>
        </div>
      {/if}    

      <section id="wrapper" class="{if $page.page_name != 'index'}sticky-menu-correction{/if}">

        {block name='notifications'}
          {include file='_partials/notifications.tpl'}
        {/block}

        {hook h="displayWrapperTop"}
        <div class="container">
          {block name='breadcrumb'}
            {include file='_partials/breadcrumb.tpl'}
          {/block}

          {$bestSalesPageClass = ''}
          {if $page.page_name == 'best-sales'}
            {$bestSalesPageClass = 'bestsales-page js-best-sales-left'}

            <h1 class="bestsales-title">Najczęściej kupowane</h1>
          {/if}

          <div class="row {$bestSalesPageClass}">

            {if $page.page_name == 'category' or $page.page_name == 'prices-drop' }
                {include file='./custom/custom-category-page.tpl' listing=$listing category=$category|default:''}
            {elseif $page.page_name == 'contact'}
                {include file='./custom/custom-contact-page.tpl'}
            {else}
        
            {block name="left_column"}
              {if $page.page_name == 'product'}
                <div id="left-column" class="col-12 col-md-4 col-lg-3">
                  {hook h='displayLeftColumnProduct'}
                </div>
              {else}
                <div id="left-column" class="col-12 col-md-4 col-lg-3">
                  {*{hook h="displayLeftColumn"}*}
                </div>
              {/if}
            {/block}

            {block name="content_wrapper"}
              <div id="content-wrapper" class="js-content-wrapper left-column right-column col-md-4 col-lg-6">
                {hook h="displayContentWrapperTop"}
                {block name="content"}
                  <p>Hello world! This is HTML5 Boilerplate.</p>
                {/block}
                {hook h="displayContentWrapperBottom"}
              </div>
            {/block}

            {block name="right_column"}
              <div id="right-column" class="col-12 col-md-4 col-lg-3">
                {if $page.page_name == 'product'}
                  {hook h='displayRightColumnProduct'}
                {else}
                  {hook h="displayRightColumn"}
                {/if}
              </div>
            {/block}

            {/if}

          </div>

          
        </div>
        {hook h="displayWrapperBottom"}
      </section>

      <footer id="footer" class="l-footer js-footer">
        {block name="footer"}
          {include file="_partials/footer.tpl"}
        {/block}
      </footer>

    </main>

    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}

    {block name='hook_before_body_closing_tag'}
      {hook h='displayBeforeBodyClosingTag'}
    {/block}

    {block name='mobile-modals'}
      {include file="_partials/mobile-modals.tpl"}
    {/block}

    {block name='page-loader'}
      {include file="_partials/page-loader.tpl"}
    {/block}

  </body>

</html>
