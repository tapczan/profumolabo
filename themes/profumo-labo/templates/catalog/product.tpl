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
          <div class="col-md-7 col-lg-8">
            {block name='page_content_container'}
              {block name='page_content'}
                {block name='product_cover_thumbnails'}
                  {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                {/block}
              {/block}
            {/block}
          </div>
          <div class="col-md-5 col-lg-4">
            <div class="product-single__info">
              <div class="product-rating">
                <ul class="star-rating">
                  <li class="star-rating__list">
                    <span class="star-rating__icon star-rating__icon--active"></span>
                  </li>
                  <li class="star-rating__list">
                    <span class="star-rating__icon star-rating__icon--active"></span>
                  </li>
                  <li class="star-rating__list">
                    <span class="star-rating__icon star-rating__icon--active"></span>
                  </li>
                  <li class="star-rating__list">
                    <span class="star-rating__icon"></span>
                  </li>
                  <li class="star-rating__list">
                    <span class="star-rating__icon"></span>
                  </li>
                </ul>
                <span class="star-review">
                  (21 opinii)
                </span>
              </div>

              {block name='page_header_container'}
                {block name='page_header'}
                  <h1 class="product-title">
                    {block name='page_title'}{$product.name}{/block}

                    <a class="product-wishlist" href="/">
                      <img src="{$urls.img_url}heart-icon.svg">
                    </a>
                  </h1>
                {/block}
              {/block}

              <div class="product-reference">
                <span class="product-inspired">
                  Inspiracja no29
                </span>
                <span class="product-brand">
                  Tom Ford Noir Noir
                </span>
              </div>
              
              {block name='product_prices'}
                <div class="product-price">
                  {include file='catalog/_partials/product-prices.tpl'}
                  <span class="product-stock-info">
                    Ostatnie <span class="product-stock-info__num">5</span> sztuk w tej cenie
                  </span>
                </div>
              {/block}

              {if $product.is_customizable && count($product.customizations.fields)}
                {block name='product_customization'}
                  <div class="product-variation">
                    {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
                  </div>
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
  
                    {block name='product_discounts'}
                      {include file='catalog/_partials/product-discounts.tpl'}
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
                SYMBOL: P026US100
              </div>

              {block name='product_description_short'}
                <div class="product-description">{$product.description_short nofilter}</div>
              {/block}

              <div class="product-accordion" id="productSingleAccordion">
                <div class="product-accordion__item">
                  <div class="product-accordion__header" id="productAccordionHeader1" data-toggle="collapse" data-target="#productAccordionContent1" aria-expanded="true" aria-controls="productAccordionContent1">
                    OPIS
                  </div>
                  <div class="product-accordion__body collapse show" id="productAccordionContent1" aria-labelledby="productAccordionHeader1" data-parent="#productSingleAccordion">
                    <p>Świeżo-korzenno-drzewna kompozycja ukazuje styl i podkreśla aparycję mężczyzny, który po nią sięga. Świeżo-korzenno-drzewna kompozycja jest wzorem równowagi i harmonii. Żywiołowa świeżość nut głowy - mandarynki i kolendry, podkreślona nieco</p>
                  </div>
                </div>

                <div class="product-accordion__item">
                  <div class="product-accordion__header" id="productAccordionHeader2" data-toggle="collapse" data-target="#productAccordionContent2" aria-expanded="false" aria-controls="productAccordionContent2">
                    DODAJ AKCESORIA
                  </div>
                  <div class="product-accordion__body collapse" id="productAccordionContent2" aria-labelledby="productAccordionHeader2" data-parent="#productSingleAccordion">
                    <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
                </div>
                
                <div class="product-accordion__item">
                  <div class="product-accordion__header" id="productAccordionHeader3" data-toggle="collapse" data-target="#productAccordionContent3" aria-expanded="false" aria-controls="productAccordionContent3">
                    NUTY ZAPACHOWE I SKŁAD
                  </div>
                  <div class="product-accordion__body collapse" id="productAccordionContent3" aria-labelledby="productAccordionHeader3" data-parent="#productSingleAccordion">
                    <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
                </div>
                
                <div class="product-accordion__item">
                  <div class="product-accordion__header" id="productAccordionHeader4" data-toggle="collapse" data-target="#productAccordionContent4" aria-expanded="false" aria-controls="productAccordionContent4">
                    CECHY CHARAKTERYSTYCZNE
                  </div>
                  <div class="product-accordion__body collapse" id="productAccordionContent4" aria-labelledby="productAccordionHeader4" data-parent="#productSingleAccordion">
                    <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
                </div>
                
                <div class="product-accordion__item">
                  <div class="product-accordion__header" id="productAccordionHeader5" data-toggle="collapse" data-target="#productAccordionContent5" aria-expanded="false" aria-controls="productAccordionContent5">
                    INFORMACJE DODATKOWE
                  </div>
                  <div class="product-accordion__body collapse" id="productAccordionContent5" aria-labelledby="productAccordionHeader5" data-parent="#productSingleAccordion">
                    <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
                </div>
                
                <div class="product-accordion__item">
                  <div class="product-accordion__header" id="productAccordionHeader6" data-toggle="collapse" data-target="#productAccordionContent6" aria-expanded="false" aria-controls="productAccordionContent6">
                    DOSTAWA I ZWROT
                  </div>
                  <div class="product-accordion__body collapse" id="productAccordionContent6" aria-labelledby="productAccordionHeader6" data-parent="#productSingleAccordion">
                    <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                  </div>
                </div>
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

  {block name='page_footer_container'}
    <footer class="page-footer">
      {block name='page_footer'}
        <!-- Footer content -->
      {/block}
    </footer>
  {/block}

  {*
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
  *}

{/block}
