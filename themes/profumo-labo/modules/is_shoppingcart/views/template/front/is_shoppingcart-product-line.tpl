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

<div class="js-cart" data-refresh-url="{url entity='cart' params=['ajax' => true, 'action' => 'refresh']}">
        
   <div class="cart-products">
       
            <div class="cart-products__thumb">
                <img
                    {generateImagesSources image=$product.default_image size='cart_default' lazyload=false}
                    alt="{$product.name|escape:'quotes'}"
                    class="img-fluid rounded cart-products__img"
                    width="{$product.default_image.bySize.cart_default.width}"
                    height="{$product.default_image.bySize.cart_default.height}">
            </div>

            <div class="cart-products__desc">
                <h6 class="cart-products__name h6 mb-2 font-sm">
                    {$product.name}
                </h6>

                <div class="cart-products__short-desc">
                        {$product.category_name}
                        {if $product.attributes}
                            {foreach $product.attributes item=attribute}
                                {$attribute}
                            {/foreach}
                        {/if}
                </div>
        
                <div class="cart-products__price">
                    {$product.price}
                </div>
                
                {*
                <ul class="mb-2">
                    <li class="text-muted small">
                        <span>{l s='Quantity' d='Shop.Theme.Catalog'}:</span>
                        <span class="font-weight-bold">{$product.quantity}</span>
                    </li>
                    {foreach from=$product.attributes key="attribute" item="value"}
                        <li class="text-muted small">
                            <span>{$attribute}:</span>
                            <span class="font-weight-bold">{$value}</span>
                        </li>
                    {/foreach}
                </ul>
        
                <span class="price price--sm">
                    {$product.price}
                </span>
                *}
            </div>

            <div class="cart-products__quantity">
                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                        <span class="input-group-btn input-group-prepend">
                        <button class="btn btn-touchspin js-touchspin js-increase-product-quantity bootstrap-touchspin-down bootstrap-touchspin-injected js-addon-btn-cart" type="button">-</button>
                        </span>
                        <input
                            class="js-cart-line-product-quantity input-touchspin form-control"
                            data-down-url="{$product.down_quantity_url}"
                            data-up-url="{$product.up_quantity_url}"
                            data-update-url="{$product.update_quantity_url}"
                            data-product-id="{$product.id_product}"
                            type="number"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            value="{$product.quantity}"
                            name="product-quantity-spin"
                            min="{$product.minimal_quantity}"
                            aria-label="{l s='%productName% product quantity field' sprintf=['%productName%' => $product.name] d='Shop.Theme.Checkout'}"
                            />
                        <span class="input-group-btn input-group-append">
                        <button class="btn btn-touchspin js-touchspin js-decrease-product-quantity bootstrap-touchspin-up bootstrap-touchspin-injected js-addon-btn-cart" type="button">+</button>
                        </span>
                    </div> 
            </div>

            <div class="cart-products__remove">
                <a class="cart-products__remove-cart remove-from-cart text-danger" rel="nofollow" href="{$product.remove_from_cart_url}"
                    data-link-action="delete-from-cart" data-id-product="{$product.id_product|escape:'javascript'}"
                    data-id-product-attribute="{$product.id_product_attribute|escape:'javascript'}"
                    data-id-customization="{$product.id_customization|escape:'javascript'}">
                    <span class="material-icons font-reset">delete</span>
                </a>
            </div>
        
    </div>
    

</div>
   