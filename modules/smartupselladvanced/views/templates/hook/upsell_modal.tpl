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

<!-- The Modal -->
<div id="blockcart-modal" class="modal js-upsell-modal fade in">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header sua-modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title h6 text-sm-center">
          {l s='Item is out of stock at the moment. Would you be interested in similar products?' mod='smartupselladvanced'}
        </h4>
      </div>

      <div class="modal-body">
        <div class="row">
          {foreach from=$upsells key=currentIteration item=upsell name=soa}
            {if $smarty.foreach.soa.index == 2}
              {break}
            {/if}
            <div class="col-md-6 {if $currentIteration eq 0} divide-right {/if} so-modal-display-div" data-product-id="{$upsell["id_product"]|escape:'htmlall':'UTF-8'}">
              <div class="row">

                <div class="col-sm-6">
                  <img class="product-image" src="{$upsell['image_large']|escape:'htmlall':'UTF-8'}"
                       alt="{$upsell['name']|escape:'htmlall':'UTF-8'}" title="{$upsell['name']|escape:'htmlall':'UTF-8'}" itemprop="image">
                </div>
                <div class="col-sm-6 sua-input-container">



                  <div class="col-xs-1 col-md"></div>
                  <div class="col-xs-11 col-sm-12">
                    <a href="{$upsell['product_link']|escape:'htmlall':'UTF-8'}" target=”_blank”>
                      <h6 class="product-name">{$upsell['name']|escape:'htmlall':'UTF-8'}</h6>
                    </a>
                    <div class="product-line-info product-price h6 has-discount mt-1">
                      {if $upsell["discount"] ne 0}
                        <div class="current-price">
                          <span class="regular-price pb-0 pt-0"><del>{$upsell["price"]|escape:'htmlall':'UTF-8'}</del></span>
                          <span
                            class="discount discount-percentage pb-0 pt-0">-{$upsell["discount"]['discount_percent']|escape:'htmlall':'UTF-8'}</span>
                        </div>
                        <div class="product-discount">
                          <span class="price pb-0 pt-0">{$upsell["discount"]['discounted_price']|escape:'htmlall':'UTF-8'}</span>
                        </div>
                      {else}
                        <div class="product-discount">
                          <span class="price pb-0 pt-0">{$upsell["price"]|escape:'htmlall':'UTF-8'}</span>
                        </div>
                      {/if}
                    </div>
                    <div class="sua-module-link" data-module-link='{$upsell["module_link"]|escape:'htmlall':'UTF-8'}'>
                      <div class="row pt-1">
                        {foreach from=$upsell["group_attributes"] item=group key=main_key}
                          {if $group['group_type'] eq 'select'}
                            <div class="col-md-6 sua-dropdown-box">
                              <div class=" pr-1 sua-title">
                                <span class="control-label">{$group['name']|escape:'htmlall':'UTF-8'}:</span>
                              </div>
                              <div class="pr-1">
                          <span class="value">
                      <select class="form-control form-control-select so-select-small sua-input sua-select-container"
                              id="group_{$main_key|escape:'htmlall':'UTF-8'}"
                              name="group[{$main_key|escape:'htmlall':'UTF-8'}]">
                        {foreach from=$group['attributes'] item=attribute key=key}
                          <option value="{$key|escape:'htmlall':'UTF-8'}" title="{$attribute['name']|escape:'htmlall':'UTF-8'}"
                                  {if $attribute['selected'] eq true}selected="selected"{/if}>{$attribute['name']|escape:'htmlall':'UTF-8'}
                          </option>
                        {/foreach}
                      </select>
                    </span>
                              </div>
                            </div>
                          {/if}
                          {if $group['group_type'] eq 'color'}
                            <div class="clearfix col-md-6">
                              <div class="pr-1 m-0 sua-title">
                                <span class="control-label">{$group['name']|escape:'htmlall':'UTF-8'}:</span>
                              </div>
                              <div class="sua-color-input">
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
                            </div>
                          {/if}
                        {/foreach}
                        <div class="col-md-6 sua-input-quantity">
                          <div class="sua-quantity-container sua-title">
                            <span class="control-label">{l s='Quantity:' mod='smartupselladvanced'}</span>
                          </div>
                          <div class="sua-quantity-container">
                            <input class="sua-quantity-input" type="text" value="1" name="product-quantity-spin" min="1">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div class="container">
                <div class="sua-button-container mt-2">
                  <a href="{$upsell['product_link']|escape:'htmlall':'UTF-8'}" class="btn btn-primary sua-view-button mb-1">
                    <i class="material-icons search">remove_red_eye</i>
                    {l s='View' mod='smartupselladvanced'}
                  </a>
                  <button class="btn btn-primary sua-add-to-cart-upsell-modal mb-1">
                    <i class="material-icons rtl-no-flip"></i>
                    {l s='Add to cart' mod='smartupselladvanced'}
                  </button>
                </div>
                <div class="js-sua-out-of-stock-box">
                  <p class="sua-error-message-text">
                    <i class="material-icons product-unavailable sua-error-message-icon"></i>
                    <strong>{l s='This product variant is out of stock' mod='smartupselladvanced'}</strong>
                  </p>
                </div>

              </div>

            </div>
            {if $currentIteration eq 0}
            <hr class="sua-product-break">
            {/if}
          {/foreach}
        </div>
      </div>
    </div>
  </div>
</div>
