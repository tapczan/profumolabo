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

<div class="images-container js-images-container">
  {block name='product_cover'}
    <div class="product-single__img js-product-single-img">
      {if $product.images}
        {foreach from=$product.images item=image}
          <div class="product-img">
            <div class="js-product-add-to-cart">
              {hook h='displayProductActions'}
            </div>
            {if !Context::getContext()->controller->isQuickView()}
            <a href="{$image.bySize.large_default.url}" data-fancybox="product-single-featured-img" class="product-img__url js-fancybox-img">
            {/if}
              <img 
                src="{$image.bySize.medium_default_x2.url}" 
                {if !empty($product.default_image.legend)}
                  alt="{$image.legend}" title="{$image.legend}"
                {else}
                  alt="{$product.name}"
                {/if}
                class="product-img__asset"
              >
            {if !Context::getContext()->controller->isQuickView()}
            </a>
            {/if}
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

</div>