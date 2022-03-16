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
{block name='header_top'}
<div class="header header--loggedin">
  <div class="header__inner">
    <div class="container">
      <div class="row">
        <div class="col-md-4 d-md-flex align-items-center">
          <div class="header__inner-l">
            {widget name='ps_languageselector'}
            {widget name='is_searchbar'}
          </div>
        </div>
        <div class="col-md-4 text-center">
          <a href="{$urls.pages.index}">
            <img class="logo img-fluid" width="272" height="21" src="{$shop.logo}" alt="{$shop.name} {l s='logo' d='Shop.Theme.Global'}" loading="lazy">
          </a>
        </div>
        <div class="col-md-4 d-md-flex align-items-center justify-content-end">
          <div class="header__inner-r">
            {widget name='ps_customersignin'}

            <a href="{$wishlist_url}" style='position: relative'><img class="header__inner-cart-wishlist" src="{$urls.img_url}heart-icon.svg"> 
              {if $wishlist_count > 0}
                <span class="header-top__badge header__inner-wishlist-badge js-wishlist-counter-top">{$wishlist_count}</span> 
              {/if}
            </a> 
            {widget name='is_shoppingcart'}
          </div>
        </div>
      </div>
    </div>
  </div>
      
  <div class="header__nav">
    <div class="container">
      <div class="row">
        <div class="col-md-2 col-sm-7 col-6 header__nav-left">
          <a href="{$urls.pages.index}">
            <img class="logo img-fluid" src="{$shop.logo}" alt="{$shop.name} {l s='logo' d='Shop.Theme.Global'}" loading="lazy">
          </a>
        </div>
        <div class="col-md-8 col-sm-1 col-2 header__nav-mid">
          <div class="header__nav-search-mobile jsMobileSearch">
            {widget name='is_searchbar'}
          </div>
          {hook h='displayMegaMenu'}
        </div>
        <div class="col-md-2 col-sm-4 col-4 header__nav-right">
          {widget name='is_searchbar'}
          {widget name='ps_customersignin'}

          <a href="{$wishlist_url}" style='position: relative'><img class="header__inner-cart-wishlist" src="{$urls.img_url}heart-icon.svg"> 
            {if $wishlist_count > 0}
              <span class="header-top__badge header__inner-wishlist-badge js-wishlist-counter-nav">{$wishlist_count}</span> 
            {/if}
          </a> 
          {widget name='is_shoppingcart'}
        </div>
      </div>
    </div>
  </div>
</div>
{/block}
