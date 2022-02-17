{**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="header-top__block header-top__block--cart col flex-grow-0 header-cart">
  <div class="blockcart cart-preview dropdown" data-refresh-url="{$refresh_url}">
    <a href="#" role="button" id="cartDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="header__inner-top-link d-lg-block d-none">
      <div class="header-top__icon-container">
        <img class="header-top__icon header__inner-cart-icon" src="{$urls.img_url}bag-icon.svg">
        <span class="header-top__badge header__inner-cart-badge {if $cart.products_count > 9}header-top__badge--smaller{/if}">
          {$cart.products_count}
        </span>
      </div>
    </a>
    <a href="{$cart_url}" class="d-flex d-lg-none header__inner-top-link">
      <div class="header-top__icon-container">
        <img class="header-top__icon header__inner-cart-icon" src="{$urls.img_url}bag-icon.svg">
        <span class="header-top__badge header__inner-cart-badge {if $cart.products_count > 9}header-top__badge--smaller{/if}">
          {$cart.products_count}
        </span>
      </div>
    </a>
    <div class="dropdown-menu blockcart__dropdown cart-dropdown dropdown-menu-right" aria-labelledby="cartDropdown">
      <div class="cart-dropdown__content keep-open js-cart__card-body cart__card-body">
        <div class="cart-loader"><div class="spinner-border text-primary" role="status"><span class="sr-only">{l s='Loading...' d='Shop.Theme.Global'}</span></div></div>
        <div class="cart-dropdown__title">
          <h2 class="cart-dropdown__header">
            {l s='PRODUCT ADDED TO CART' d='Shop.Istheme'}
          </h2>
          {*
          <span class="cart-dropdown__close dropdown-close ml-auto cursor-pointer">
            ×
          </span>
          *}
        </div>
        {if $cart.products_count > 0}
          <div class="cart-dropdown__products">
            {foreach from=$cart.products item=product}
              {include 'module:is_shoppingcart/views/template/front/is_shoppingcart-product-line.tpl' product=$product}
            {/foreach}
          </div>

          {* {foreach from=$cart.subtotals item="subtotal"}
            {if $subtotal.value}
              <div class="cart-summary-line cart-summary-line-{$subtotal.type}">
                <span class="label">{$subtotal.label}</span>
                <span class="value">{if 'discount' == $subtotal.type}-&nbsp;{/if}{$subtotal.value}</span>
              </div>
            {/if}
          {/foreach} *}

          <div class="cart-summary">
            <ul class="cart-summary__list">
              <li class="cart-summary__item">
                <span class="cart-summary__label">
                  Total products:
                </span> 
                <span class="cart-summary__value">
                  46,50zl
                </span> 
              </li>
              <li class="cart-summary__item">
                <span class="cart-summary__label">
                  Shipment:
                </span> 
                <span class="cart-summary__value">
                  Za darmo!
                </span> 
              </li>
              <li class="cart-summary__item">
                <span class="cart-summary__label">
                  Total (gross):
                </span> 
                <span class="cart-summary__value">
                  46,49 zl
                </span> 
              </li>
            </ul>
            {*
            <span class="label">{$cart.totals.total.label}</span>
            <span class="value">{$cart.totals.total.value}</span>
            *}
          </div>

          <div class="cart-action">
            <a href="{$cart_url}" class="cart-action__checkout dropdown-close">
              {l s='PRZEJDŹ DO REALIZACJI ZAMÓWIENIA' d='Shop.Theme.Actions'}
            </a>
            <span class="cart-action__continue dropdown-close">
              Kontynuuj zakupy
            </span>
          </div>
          {*
          <div class="mt-3">
            <a href="{$cart_url}" class="btn btn-sm btn-primary btn-block dropdown-close">
              {l s='Proceed to checkout' d='Shop.Theme.Actions'}
            </a>
          </div>
          *}
        {else}
          <div class="alert alert-warning">
            {l s='Unfortunately your basket is empty' d='Shop.Istheme'}
          </div>
        {/if}
      </div>
    </div>
  </div>
</div>
