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

{foreach from=$special_offers key=currentIteration item=specialOffer}
  <div class="so-display-div"
       data-product-id="{$specialOffer["id_main_product"]|escape:'htmlall':'UTF-8'}"
       data-special-product-id="{$specialOffer["id_special_product"]|escape:'htmlall':'UTF-8'}"
       data-special-offer-id="{$specialOffer["id_special_offer"]|escape:'htmlall':'UTF-8'}">
  >
    <div class="card cart-container">
      <div class="card-header text-center">
        <p class="mb-0 so-black-title">
{*          @todo join translations to one string*}
          {l s='Special offer only for you for limited time: buy ' mod='smartupselladvanced'}
          <strong>{$specialOffer["special_offer_name"]|escape:'htmlall':'UTF-8'}</strong>
          {if $specialOffer["special_offer_type"] eq 0}
            {l s='instead of ' mod='smartupselladvanced'}
          {else}{l s='with ' mod='smartupselladvanced'}{/if}
          <strong>{$specialOffer["product_name"]|escape:'htmlall':'UTF-8'}</strong>
        </p>
      </div>
      <hr class="separator">
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
                <div class="product-line-info">
                  <a class="label" href="{$specialOffer['product_link']|escape:'htmlall':'UTF-8'}" target=”_blank”>
                    {$specialOffer["special_offer_name"]|escape:'htmlall':'UTF-8'}
                  </a>
                </div>

                <div class="product-description mt-1">
                  {$specialOffer["short_description"]|cleanHtml nofilter}
                </div>
                {*                Product group options*}
                <div class="product-variants sua-module-link" data-module-link='{$specialOffer["module_link"]|escape:'htmlall':'UTF-8'}'>
                  {foreach from=$specialOffer["group_attributes"] item=group key=main_key}
                    {if $group['group_type'] eq 'select'}
                      <div class="clearfix product-variants-item pr-1 m-0 so-float-left">
                        <span class="label">{l s='Size:' mod='smartupselladvanced'}</span>
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
                        <p>{l s='Offer time' mod='smartupselladvanced'}</p>
                      </div>
                      <div>
                        <p class="so-countdown">
                          <strong class="countdown" data-time="{$specialOffer["special_offer_time"]|escape:'htmlall':'UTF-8'}">
                          </strong>
                        </p>
                      </div>
                    </div>
                  {/if}

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

                  <div class="text-xs-center">
                    <button class="btn btn-primary btn-block so-button-smaller sua-add-to-cart" type="button"
                            disabled="true">
                      <i class="material-icons shopping-cart"></i>
                      <div class="so-button-text">
                        {if $specialOffer["special_offer_type"] eq 0}
                          {l s='Accept change' mod='smartupselladvanced'}
                        {else}{l s='Add to cart' mod='smartupselladvanced'}{/if}
                      </div>
                    </button>
                  </div>

                  <div class="pt-1 js-sua-out-of-stock-box">
                    <p style="color: black; text-align: center; padding: 2px">
                      <i class="material-icons product-unavailable sua-out-of-stock-icon"></i>
                      <strong>{l s='This product variant is out of stock' mod='smartupselladvanced'}</strong>
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
