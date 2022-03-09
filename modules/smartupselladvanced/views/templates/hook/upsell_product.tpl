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

<div class="js-upsell-products mt-1">
  <div class="row">
    {foreach from=$upsells key=currentIteration item=upsell}
      {if $currentIteration % 2 eq 0  and $currentIteration % 4 ne 0 }<div class="clearfix hidden-lg-up"></div>{/if}
      {if $currentIteration % 4 eq 0  and $currentIteration ne 0}<div class="clearfix hidden-sm-down"></div>{/if}
      <div class="col-sm-6 col-lg-3 so-display-div js-upsell-product" data-product-id="{$upsell["id_product"]|escape:'htmlall':'UTF-8'}">
        <div class="product-line-grid sua-module-link" data-module-link="{$upsell["module_link"]|escape:'htmlall':'UTF-8'}">
          <div class="card col-xs-12 sua-card">
            <img class="w-100 mt-1" src="{$upsell['image']|escape:'htmlall':'UTF-8'}" alt="{$upsell['name']|escape:'htmlall':'UTF-8'}">

            <a href="{$upsell['product_link']|escape:'htmlall':'UTF-8'}" target=”_blank”>
              <p class="label text-xs-center sua-product-name">{$upsell['name']|escape:'htmlall':'UTF-8'}</p>
            </a>

              <div class="product-line-info product-price h5 has-discount sua-product-price">
                {if $upsell["discount"] ne 0}
                  <div class="current-price">
                    <span class="regular-price pb-0 pt-0"><del>{$upsell["price"]|escape:'htmlall':'UTF-8'}</del></span>
                    <span class="discount discount-percentage pb-0 pt-0">-{$upsell["discount"]['discount_percent']|escape:'htmlall':'UTF-8'}</span>
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

            <form method="post" action="{$upsell['cart_link']|escape:'htmlall':'UTF-8'}">
              <input type="hidden" name="id_product" value="{$upsell["id_product"]|escape:'htmlall':'UTF-8'}">
              <input type="hidden" name="qty" value="1">
              <input type="hidden" name="id_customization" value="0">
              <input type="hidden" name="token" value="{$static_token|escape:'htmlall':'UTF-8'}">

              <div class="sua-upsell-attributes">
                {if !empty($upsell['combinations']|escape:'htmlall':'UTF-8')}
                  <div class="clearfix so-float-left">
                    <span class="control-label">{l s='Attributes' mod='smartupselladvanced'}</span>
                    <span class="value">
                        <select name="id_product_attribute" class="form-control form-control-select so-select-small sua-input sua-select-container"
                                id="group_{$currentIteration|escape:'htmlall':'UTF-8'}"
                                name="group[{$currentIteration|escape:'htmlall':'UTF-8'}]">
                          {foreach from=$upsell['combinations'] item=attribute key=key name=iter}
                            <option name="id_product_attribute" value="{$key|escape:'htmlall':'UTF-8'}" title="{$attribute['name']|escape:'htmlall':'UTF-8'}"
                                    {if $smarty.foreach.iter.index eq 0}selected="selected"{/if}>{$attribute['name']|escape:'htmlall':'UTF-8'}
                            </option>
                          {/foreach}
                        </select>
                      </span>
                  </div>
                {/if}
              </div>
              <div class="js-sua-alert-box sua-alert-box">
                <p class="js-sua-alert-text sua-alert-text"></p>
              </div>
              <button class="btn btn-primary add-to-cart-related add-to-cart-hook btn-block mb-1 sua-add-to-cart-upsell" data-button-action="add-to-cart" type="submit">
                <div class="so-button-text"><i
                    class="material-icons shopping-cart"></i>{l s='Add to cart' mod='smartupselladvanced'}</div>
              </button>
            </form>
          </div>
        </div>
      </div>
    {/foreach}
  </div>
  {if $show_modal eq true}
    {include file= './upsell_modal.tpl'}
  {/if}
</div>
