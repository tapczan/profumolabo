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

{block name='product_cover'}
  <div class="product-single__img js-product-single-img">
    {if $product.images}
      {foreach from=$product.images item=image}
        <div class="product-img">
          <span
          class="wishlist-button"
          data-url="{$url}"
          data-product-id="{$product.id}"
          data-product-attribute-id="{$product.id_product_attribute}"
          data-is-logged="{$customer.is_logged}"
          data-list-id="1"
          data-checked="true"
          data-is-product="true"
          ></span>

          <a href="{$image.bySize.large_default.url}" data-fancybox="product-single-featured-img" class="product-img__url">
            <img 
              src="{$image.bySize.medium_default.url}" 
              {if !empty($product.default_image.legend)}
                alt="{$image.legend}" title="{$image.legend}"
              {else}
                alt="{$product.name}"
              {/if}
              class="product-img__asset"
            >
          </a>
        </div>
      {/foreach}
    {else}
      <div class="product-img">
        <img src="{$urls.no_picture_image.bySize.large_default.url}" class="rounded img-fluid" loading="lazy">
      </div>
    {/if}
  </div>
{/block}

{hook h='displayAfterProductThumbs' product=$product}
