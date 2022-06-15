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
{extends file=$layout}

{block name='head' append}
  {if $product.show_price}
    <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
    <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
    <meta property="product:price:amount" content="{$product.price_amount}">
    <meta property="product:price:currency" content="{$currency.iso_code}">
  {/if}
  {if isset($product.weight) && ($product.weight != 0)}
  <meta property="product:weight:value" content="{$product.weight}">
  <meta property="product:weight:units" content="{$product.weight_unit}">
  {/if}
{/block}


{block name='content'}

  <section id="main">
     <div class="product-single">
      <div class="container">
        <div class="row">

          <!-- Product Thumbnails -->
          <div class="col-md-7 col-lg-8">
              {block name='page_content_container'}
                {block name='page_content'}
                  {block name='product_cover_thumbnails'}
                    {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                  {/block}
                {/block}
              {/block}
          </div>
          <!-- End Product Thumbnails -->
 

          <div class="col-md-5 col-lg-4">
            <div class="product-single__info">

              <!-- Ratings -->
              <div class="product-rating">
                {hook h='displayProductListReviews' product=$product }
              </div> 
              <!-- End ratings -->
 
              <!-- Product Title -->
              {block name='page_header_container'}
                {block name='page_header'}
                  <h1 class="product-title">
                    {block name='page_title'}{$product.name}{/block}
                    <div class="js-product-add-to-cart">
                      {hook h='displayProductActions'}
                    </div>
                  </h1>
                {/block}
              {/block}
              <!-- End Product Title -->

              <!-- Product References and Manufacturer -->
              <div class="product-reference">
                <span class="product-inspired">
                  {hook h='displayCreateitInspirationfield' product=$product}
                </span>
                <span class="product-brand">
                  {hook h='displayCreateitProductfield2' product=$product is_linked='true'}
                </span>
              </div>
               <!-- End Product References and Manufacturer -->
              
              
              <!-- Product Prices -->
              {block name='product_prices'}
                {include file='catalog/_partials/product-prices.tpl'}
              {/block}
              <!-- End Product Prices -->

              <!-- Product Discounts -->
              {block name='product_discounts'}
                <span class="product-stock-info">
                  {include file='catalog/_partials/product-discounts.tpl'}
                </span>
              {/block}
              <!-- End Product Discounts -->

              {if $product.is_customizable && count($product.customizations.fields)}
                {block name='product_customization'}
                  {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
                {/block}
              {/if}

          <div class="product-actions js-product-actions">
                {block name='product_buy'}
                  <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                    <input type="hidden" name="token" value="{$static_token}">
                    <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                    <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id" class="js-product-customization-id">

                    {block name='product_variants'}
                      {include file='catalog/_partials/product-variants.tpl'}
                    {/block}
                     
                    {block name='product_pack'}
                      {if $packItems}
                        <section class="product-pack">
                          <p class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</p>
                          <div class="card-group-vertical mb-4">
                            {foreach from=$packItems item="product_pack"}
                              {block name='product_miniature'}
                                {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack showPackProductsPrice=$product.show_price}
                              {/block}
                            {/foreach}
                          </div>
                      </section>
                      {/if}
                    {/block}
   
                    {block name='product_add_to_cart'}
                      {include file='catalog/_partials/product-add-to-cart.tpl'}
                    {/block}

                    {* Input to refresh product HTML removed, block kept for compatibility with themes *}
                    {block name='product_refresh'}{/block}
                  </form>
                {/block}
              </div>
              
              <div class="product-sku">
                {block name='product_references'}
                  {include file='catalog/_partials/product-references.tpl'}
                {/block}
                  
                {*
                <p id="product_ean13"{if empty($product->ean13) || !$product->ean13} style="display: none;"{/if}>
                    <label>{l s='Ean13:'} </label>
                    <span {if !empty($product->ean13) && $product->ean13} content="{$product->ean13}"{/if}>{$product->ean13|escape:'html':'UTF-8'}</span>
                </p>
                *}
              </div>

              <div class="product-accordion" id="productSingleAccordion">     
                  {hook h='displayProductCustomFieldByName' product=$product}
              </div>
        <div class="product-social">
                {block name='product_additional_info'}
                  {include file='catalog/_partials/product-additional-info.tpl'}
                {/block}
              </div>
                </div>
          </div>
        </div>
      </div>
    </div>
  </section>
   {block name='product_footer'}
    {hook h='displayFooterProduct' product=$product category=$category}
  {/block}

  {block name='product_single_after_comment'}
    {hook h='arProductPageHook1' product=$product category=$category}
  {/block}

  
{/block}



{* Original

{block name='content'}
  <section id="main">
    <div class="row product-container js-product-container">
        <div class="col-md-7 mb-4">
          <div class="product-information ">
            {block name='product_description_short'}
              <div id="product-description-short-{$product.id}" class="product-description cms-content">{$product.description_short nofilter}</div>
            {/block}

            <div class="product-actions js-product-actions">
              {block name='product_buy'}
                <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                  <input type="hidden" name="token" value="{$static_token}">
                  <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                  <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id" class="js-product-customization-id">

                  {block name='product_variants'}
                    {include file='catalog/_partials/product-variants.tpl'}
                  {/block}

                  {block name='product_pack'}
                    {if $packItems}
                      <section class="product-pack">
                        <p class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</p>
                        <div class="card-group-vertical mb-4">
                          {foreach from=$packItems item="product_pack"}
                            {block name='product_miniature'}
                              {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack showPackProductsPrice=$product.show_price}
                            {/block}
                          {/foreach}
                        </div>
                    </section>
                    {/if}
                  {/block}

                  {block name='product_discounts'}
                    {include file='catalog/_partials/product-discounts.tpl'}
                  {/block}

                  {block name='product_add_to_cart'}
                    {include file='catalog/_partials/product-add-to-cart.tpl'}
                  {/block}

                  {block name='product_additional_info'}
                    {include file='catalog/_partials/product-additional-info.tpl'}
                  {/block}

                  {block name='product_refresh'}{/block}
                </form>
              {/block}
            </div>

            {block name='hook_display_reassurance'}
              {hook h='displayReassurance'}
            {/block}

        </div>
      </div>
    </div>
    {include file="catalog/_partials/product-tabs.tpl"}

    {block name='product_footer'}
      {hook h='displayFooterProduct' product=$product category=$category}
    {/block}

    {block name='product_accessories'}
      {if $accessories}
        {include file='catalog/_partials/product-accessories.tpl' products=$accessories}
      {/if}
    {/block}

    {block name='product_images_modal'}
      {include file='catalog/_partials/product-images-modal.tpl'}
    {/block}

    {block name='page_footer_container'}
      <footer class="page-footer">
        {block name='page_footer'}
          <!-- Footer content -->
        {/block}
      </footer>
    {/block}
  </section>
{/block}
*}

