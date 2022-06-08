{*
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   Addons PrestaShop license limitation
 * @see       /LICENSE
 *
 * International Registered Trademark & Property of INVERTUS, UAB
 *}
{if $page.page_name == 'cart'}

{if $special_offers|@count > 0}
<div class="container my-5 py-5">
  <h2 class="h2 text-center products-section-title text-uppercase">
  {l s='Related Products' d='Shop.Theme.Global'}
  </h2>
</div>
{/if} 

{foreach from=$special_offers key=currentIteration item=specialOffer}
  <div class="so-display-div"
       data-product-id="{$specialOffer["id_main_product"]|escape:'htmlall':'UTF-8'}"
       data-special-product-id="{$specialOffer["id_special_product"]|escape:'htmlall':'UTF-8'}"
       data-special-offer-id="{$specialOffer["id_special_offer"]|escape:'htmlall':'UTF-8'}">
    
    <div class="card cart-container">
      <div class="card-header text-center">
        <p class="mb-0 so-black-title">
{*          @todo join translations to one string*}

          {if $specialOffer["special_offer_type"] eq 0}
          {l s='Special offer only for you for limited time<br/> Buy <b>%special_offer_product%</b> instead of <b>%origin_product%</b><br/> for <b>%price%</b> - <b>%discount%</b><br/>' 
            d='Shop.Theme.Global' 
            sprintf=[
              '%special_offer_product%' => $specialOffer["special_offer_name"], 
              '%origin_product%' => $specialOffer["product_name"],
              '%price%' => $specialOffer["price"],
              '%discount%' => $specialOffer["discount"]['discount_percent']
              ]} 
          {else}
          {l s='Special offer only for you for limited time<br/> Buy <b>%special_offer_product%</b> for <b>%origin_product%</b><br/> for <b>%price%</b> - <b>%discount%</b><br/>' 
            d='Shop.Theme.Global' 
            sprintf=[
              '%special_offer_product%' => $specialOffer["special_offer_name"], 
              '%origin_product%' => $specialOffer["product_name"],
              '%price%' => $specialOffer["price"],
              '%discount%' => $specialOffer["discount"]['discount_percent']
              ]} 
          {/if}

          {*
          {l s='Special offer only for you for limited time' mod='smartupselladvanced'}<br/>
          {l s='Buy' d='Shop.Theme.Global'} <strong>{$specialOffer["special_offer_name"]|escape:'htmlall':'UTF-8'}</strong>
          {if $specialOffer["special_offer_type"] eq 0}
            {l s='instead of ' mod='smartupselladvanced'}
          {else}{l s='with ' mod='smartupselladvanced'}{/if}
          <strong>{$specialOffer["product_name"]|escape:'htmlall':'UTF-8'}</strong><br/>
          {l s='for' d='Shop.Theme.Global'} <strong>{$specialOffer["price"]|escape:'htmlall':'UTF-8'}</strong> - <strong>{$specialOffer["discount"]['discount_percent']|escape:'htmlall':'UTF-8'}</strong>
          *}
        </p>
      </div>
      {*<hr class="separator">*}
      <div class="card-block">
        <ul class="cart-items">
          <li class="cart-item">

            <div class="product-line-grid">
              <!--  product left content: image-->
              <div class="product-line-grid-left col-md-3 col-xs-4 pb-1">
              <span class="product-image media-middle">
                <img src="{$specialOffer["image_link"]|escape:'htmlall':'UTF-8'}" alt="{$specialOffer["special_offer_name"]|escape:'htmlall':'UTF-8'}">
              </span>
              </div>

              <!--  product left body: description -->
              <div class="product-line-grid-body  col-md-5 col-xs-8">
                
                <div class="product-rating">
                  <div class="comments_note">
                      {assign var='specialProductsRatings' value=Product::getProductRatingByID($specialOffer['id_special_product'])} 
                      {if ($specialProductsRatings>0)}
                          <div class="star_content clearfix">
                              {section name="i" start=0 loop=5 step=1}
                                  {if $specialProductsRatings le $smarty.section.i.index}
                                      <div class="star {if $specialProductsRatings == 0 && $SHOW_FIVE_STARS == true}star_on{/if}"></div>
                                  {else}
                                      <div class="star star_on"></div>
                                  {/if}
                              {/section}
                          </div>
                      {else}
                          <div class="star_content clearfix">
                              {section name="i" start=0 loop=5 step=1}
                                      <div class="star"></div>
                              {/section}
                          </div>
                      {/if}
                  </div> 
                </div>

                <div class="product-line-info">
                  <a class="label" href="{$specialOffer['product_link']|escape:'htmlall':'UTF-8'}" target=”_blank”>
                    {$specialOffer["special_offer_name"]|escape:'htmlall':'UTF-8'}
                  </a>
                </div>
                 
                <div class="product-single__info" style="margin-bottom: 10px; margin-top:10px">              
                      {assign var='specialProducts' value=Product::getSpecificProductByID($specialOffer['id_special_product'])}
                      {if $specialProducts}
                        {foreach $specialProducts item='product' name='product'}
                            <span class="product-inspired text-center">
                              {$product['reference']|escape:'htmlall':'UTF-8'}
                            </span>
                            
                            <span class="product-brand text-center">
                              {hook h='displayCreateitProductfield2_id_product' product=$product}
                            </span>
                            {*
                            <span class="product-brand text-center">
                              <span class="product_manufacturer_name"> 
                              {assign var='specialProductsManufacturer' value=Product::getProductBrandByID($product['id_manufacturer'])}
                              {foreach $specialProductsManufacturer item='manufacturer' name='manufacturer'}
                                {$manufacturer['name']}
                              {/foreach}
                              </span>            
                            </span>
                            *}
                        {/foreach}  
                      {/if}
                </div>

                <div class="product-miniature__pricing text-center">

                    {assign var='specialProductsAttributes' value=Product::getProductCombinationByID($specialOffer['id_special_product'])}
                    {foreach $specialProductsAttributes item=attribute}
                      <span class="upsell-crosssell-price-attribute">
                        {$currency.sign}{$attribute}
                      </span><br/>
                    {/foreach}

                </div>

                {*
                <div class="product-description mt-1">
                  {$specialOffer["short_description"]|cleanHtml nofilter}
                </div>
                *}
                {*                Product group options*}
                <div class="product-variants sua-module-link" data-module-link='{$specialOffer["module_link"]|escape:'htmlall':'UTF-8'}'>
                  {foreach from=$specialOffer["group_attributes"] item=group key=main_key}
                    {if $group['group_type'] eq 'select'}
                      <div class="clearfix product-variants-item pr-1 m-0 so-float-left">
                        <span class="label">{*{l s='Size:' mod='smartupselladvanced'}*}{l s='Size:' d='Shop.Theme.Global'}</span>
                        <span class="value">
                      <select class="form-control form-control-select so-select-small sua-input" id="group_{$main_key|escape:'htmlall':'UTF-8'}"
                              name="group[{$main_key|escape:'htmlall':'UTF-8'}]">
                        {foreach from=$group['attributes'] item=attribute key=key}
                          <option value="{$key|escape:'htmlall':'UTF-8'}" title="{$attribute['name']|escape:'htmlall':'UTF-8'}"
                                  {if $attribute['selected'] eq true}selected="selected"{/if}>{$attribute['name']|escape:'htmlall':'UTF-8'}
                          </option>
                        {/foreach}
                      </select>
                    </span>
                      </div>
                    {/if}
                    {if $group['group_type'] eq 'color'}
                      <div class="clearfix product-variants-item pr-1 m-0 so-float-left">
                        <span class="control-label">{$group['name']|escape:'htmlall':'UTF-8'}</span>
                        <ul id="group_2">
                          {foreach from=$group['attributes'] item=attribute key=key}
                            <li class="float-xs-left input-container">
                              <label>
                                <input class="input-color sua-radio-input" type="radio"
                                       name="group[{$currentIteration|escape:'htmlall':'UTF-8'}]" value="{$key|escape:'htmlall':'UTF-8'}"
                                       {if $attribute['selected'] eq true}checked="checked"{/if}>
                                <span class="color" style="background-color: {$attribute['html_color_code']|escape:'htmlall':'UTF-8'}"><span
                                    class="sr-only">{$attribute['name']|escape:'htmlall':'UTF-8'}</span></span>
                              </label>
                            </li>
                          {/foreach}
                        </ul>
                      </div>
                    {/if}
                  {/foreach}
                </div>
              </div>

              <!--  hidden block -->
              <div class="col-md-4 col-xs-4"></div>

              <!--  product left body: description -->
              <div class="product-line-grid-right product-line-actions col-md-4 col-xs-8">
                <div class="row text-xs-left pl-1 pr-1">

                  {if $specialOffer["special_offer_time"] gt -1}
                    <!--  special offer  -->
                    <div class="special-offer text-xs-left">
                      <div class="text-xs-left label">
                        <p>{l s='Offer time' d='Shop.Theme.Global'}</p>
                        {*
                        <p>{l s='Offer time' mod='smartupselladvanced'}</p>
                        *}
                      </div>
                      <div>
                        <p class="so-countdown">
                          <strong class="countdown" data-time="{$specialOffer["special_offer_time"]|escape:'htmlall':'UTF-8'}">
                          </strong>
                        </p>
                      </div>
                    </div>
                  {/if}

                  {*
                  <div class="product-line-info product-price h5 has-discount mt-1">
                    {if $specialOffer["discount"] ne 0 && !$specialOffer['used']}
                      <div class="product-discount">
                        <span class="regular-price pb-0 pt-0">{$specialOffer["price"]|escape:'htmlall':'UTF-8'}</span>
                        <span
                          class="discount discount-percentage pb-0 pt-0">-{$specialOffer["discount"]['discount_percent']|escape:'htmlall':'UTF-8'}</span>
                      </div>
                      <div class="current-price">
                        <span class="price pb-0 pt-0">{$specialOffer["discount"]['discounted_price']|escape:'htmlall':'UTF-8'}</span>
                      </div>
                    {else}
                      <div class="current-price">
                        <span class="price pb-0 pt-0">{$specialOffer["price"]|escape:'htmlall':'UTF-8'}</span>
                      </div>
                    {/if}
                  </div>
                  *}

                  <div class="text-xs-center">
                    <button class="btn btn-primary btn-block so-button-smaller sua-add-to-cart" type="button"
                            disabled="true">
                      <i class="material-icons shopping-cart"></i>
                      <div class="so-button-text">
                        {if $specialOffer["special_offer_type"] eq 0}
                          {l s='Accept change' d='Shop.Theme.Global'}
                        {else}{l s='Add to cart' d='Shop.Theme.Global'}{/if}
                        {*
                        {if $specialOffer["special_offer_type"] eq 0}
                          {l s='Accept change' mod='smartupselladvanced'}
                        {else}{l s='Add to cart' mod='smartupselladvanced'}{/if}
                        *}
                      </div>
                    </button>
                  </div>

                  <div class="pt-1 js-sua-out-of-stock-box">
                    <p style="color: black; text-align: center; padding: 2px">
                      <i class="material-icons product-unavailable sua-out-of-stock-icon"></i>
                      <strong>{l s='This product variant is out of stock' d='Shop.Theme.Global'}</strong>
                      {*<strong>{l s='This product variant is out of stock' mod='smartupselladvanced'}</strong>*}
                    </p>
                  </div>

                </div>
              </div>

              <div class="clearfix"></div>
            </div>

          </li>
        </ul>
      </div>
    </div>
  </div>
{/foreach}

{/if}