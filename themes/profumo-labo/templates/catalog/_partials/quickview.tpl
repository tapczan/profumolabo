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
<div id="quickview-modal-{$product.id}-{$product.id_product_attribute}" class="modal fade quickview" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
   <div class="modal-content">
    <div class="modal-loader">
      <svg class="modal-loader__logo" width="342" height="312" viewBox="0 0 342 312" xmlns="http://www.w3.org/2000/svg">
        <path d="M82.2749 153.653H77.5001H65.174H0V192.229H3.75245V181.906C3.75245 174.6 4.50759 169.201 6.00625 165.699C7.5049 162.196 10.5603 159.93 15.184 158.913C19.8078 157.884 26.9874 157.376 36.7344 157.376H65.174V229.74C65.174 239.439 64.6512 246.583 63.6289 251.184C62.5949 255.785 60.3295 258.825 56.8094 260.316C53.2893 261.808 47.8523 262.559 40.5217 262.559H39.6969V266.293H102.919V262.559H102.152C94.8101 262.559 89.3848 261.808 85.8647 260.316C82.3446 258.825 80.0676 255.785 79.0452 251.184C78.0113 246.583 77.5001 239.439 77.5001 229.74V157.387H102.919V153.653H89.803H82.2749Z"/>
        <path d="M202.739 20.1373C188.217 6.71629 164.366 0 131.21 0H39.7109V3.73384H40.5358C47.8664 3.73384 53.3034 4.48523 56.8235 5.97646C60.3436 7.46768 62.609 10.5773 63.6429 15.3053C64.6653 20.0333 65.1881 27.1195 65.1881 36.5639V146.256H77.5142V3.72228H131.222C159.975 3.72228 180.666 9.81434 193.294 21.9985C205.91 34.1826 212.219 52.3316 212.219 76.4571C212.219 89.8781 209.582 102.501 204.296 114.316C199.01 126.13 189.774 135.644 176.565 142.846C163.356 150.059 144.872 153.666 121.103 153.666H113.215V157.399H121.544C147.08 157.399 167.387 153.92 182.513 146.961C197.627 140.002 208.408 130.43 214.867 118.246C221.315 106.062 224.545 92.1438 224.545 76.4802C224.522 52.3316 217.261 33.5583 202.739 20.1373Z"/>
        <path d="M259.463 158.334H264.238H276.564H341.738V119.759H337.986V130.082C337.986 137.387 337.231 142.786 335.732 146.289C334.234 149.791 331.178 152.057 326.543 153.074C321.919 154.103 314.739 154.612 304.992 154.612H276.553V82.2467C276.553 72.548 277.064 65.4039 278.098 60.8031C279.132 56.2023 281.397 53.162 284.917 51.6708C288.437 50.1796 293.874 49.4282 301.205 49.4282H302.03V45.6943H238.808V49.4282H239.574C246.917 49.4282 252.342 50.1796 255.862 51.6708C259.382 53.162 261.659 56.2023 262.682 60.8031C263.716 65.4039 264.227 72.548 264.227 82.2467V154.6H238.808V158.334H251.924H259.463Z"/>
        <path d="M138.993 291.862C153.515 305.295 177.366 312 210.522 312H302.021V308.266H301.196C293.866 308.266 288.429 307.515 284.909 306.023C281.389 304.532 279.123 301.422 278.089 296.694C277.067 291.966 276.544 284.88 276.544 275.436V165.744H264.218V308.266H210.511C181.757 308.266 161.067 302.174 148.438 289.99C135.822 277.806 129.514 259.657 129.514 235.531C129.514 222.11 132.151 209.487 137.437 197.673C142.723 185.858 151.959 176.345 165.168 169.143C178.377 161.929 196.86 158.323 220.629 158.323H228.518V154.589H220.188C194.653 154.589 174.345 158.068 159.219 165.027C144.105 171.986 133.324 181.558 126.865 193.742C120.417 205.926 117.188 219.844 117.188 235.508C117.211 259.657 124.472 278.43 138.993 291.862Z"/>
      </svg>
    </div>
     <div class="modal-header">
      <h1 class="modal-title"><a class="modal-title-link" href="{$product.url}">{l s='See the product sheet' d='Shop.Istheme'}</a></h1>
       <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
         <i class="material-icons">close</i>
       </button>
     </div>
     <div class="modal-body">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          {block name='product_cover_thumbnails'}
            {include file='catalog/_partials/product-cover-thumbnails.tpl'}
          {/block}
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 quickview__right-panel">
          <div class='quickview__right-panel--wrapper'>
            <div class="product-rating">
              {hook h='displayProductListReviews' product=$product }
            </div>
            <div class="position-relative">
              <h2 class="modal-product-title">{$product.name}</h2>
              <div class="js-product-add-to-cart">
                {hook h='displayProductActions'}
              </div>
            </div>
            <div class="product-reference">
              <span class="product-inspired">
                {l s='Inspiration' d='Shop.Istheme'}
                {$product.reference}
              </span>
              <span class="product-brand">
              {if isset($product_manufacturer->id)}
                {$product_manufacturer->name}
              {/if}
              </span>
            </div>

            {block name='product_prices'}
              {include file='catalog/_partials/product-prices.tpl'}
            {/block}

            <span class="product-stock-info">
              {include file='catalog/_partials/product-discounts.tpl'}
            </span>
            
            {block name='product_buy'}
              <div class="product-actions js-product-actions">
                <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                  <input type="hidden" name="token" value="{$static_token}">
                  <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                  <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id" class="js-product-customization-id">

                  {block name='product_variants'}
                    {include file='catalog/_partials/product-variants.tpl'}
                  {/block}

                  {block name='product_add_to_cart'}
                    {include file='catalog/_partials/product-add-to-cart.tpl'}
                  {/block}
                  <div class="product-sku">
                    <p id="product_ean13"{if empty($product->ean13) || !$product->ean13} style="display: none;"{/if}>
                      <label>{l s='Symbol:' d='Istheme'} </label>
                      <span {if !empty($product->ean13) && $product->ean13} content="{$product->ean13}"{/if}>{$product->ean13|escape:'html':'UTF-8'}</span>
                    </p>
                  </div>

                  <div class="modal-accordion" id="modalAccordionParent">
                    <div class="modal-accordion__item">
                      <div class="modal-accordion__header" id="modalAccordionHeader1" data-toggle="collapse" data-target="#modalAccordionContent1" aria-expanded="true" aria-controls="modalAccordionContent1">
                        {l s='Description' d='Shop.Theme.Global'}
                      </div>
                      <div class="modal-accordion__body collapse show" id="modalAccordionContent1" aria-labelledby="modalAccordionHeader1" data-parent="#modalAccordionParent">
                        {$product.description_short nofilter}
                      </div>
                    </div>
                  </div>
                  <a class="quickview-product-link" href="{$product.url}">{l s='Product Details' d='Shop.Theme.Catalog'}</a>

                  {* Input to refresh product HTML removed, block kept for compatibility with themes *}
                  {block name='product_refresh'}{/block}
              </form>
            </div>
            {/block}
          </div>
        </div>
      </div>
     </div>
   </div>
 </div>
</div>
