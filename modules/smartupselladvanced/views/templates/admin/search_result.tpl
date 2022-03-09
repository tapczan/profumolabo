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

{if $products}
  <ul class="list-group">
    {foreach from=$products item=product}
        <li class="list-group-item" id="">
          <div class="row">
            <a href="{$product_url|escape:'htmlall':'UTF-8'}{$product.id_product|intval}" class="product-link" data-product-id="{$product.id_product|intval}">
            <div class="col-sm-4">
              <img class="img-responsive"
                   src="{$smarty.const._THEME_PROD_DIR_|escape:'htmlall':'UTF-8'}{if !Configuration::get('PS_LEGACY_IMAGES')}{Image::getImgFolderStatic($product.id_image)|escape:'htmlall':'UTF-8'}{/if}{$product.id_image|intval}-{$image_type|escape:'htmlall':'UTF-8'}.jpg"/>
            </div>
            <div class="col-sm-7 product-info">
              <p class="product_name">
                {l s='Name:' mod='smartupselladvanced'} {$product.name|escape:'htmlall':'UTF-8'}
              </p>
              <p class="product_id">
                {l s='ID:' mod='smartupselladvanced'} {$product.id_product|intval}
              </p>
              <p class="product_reference">
                {l s='Reference:' mod='smartupselladvanced'} {$product.reference|escape:'htmlall':'UTF-8'}
              </p>
              <p class="product_reference">
                {l s='EAN13:' mod='smartupselladvanced'} {$product.ean13|escape:'htmlall':'UTF-8'}
              </p>
            </div>
            </a>
            <div class="col-sm-1">
              <button type="button" class="close js-sua-close-item" aria-label="Close" hidden>
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
        </li>
    {/foreach}
  </ul>
{else}
  <ul class="list-group">
    <li class="list-group-item">
      <i>
        {l s='No products' mod='smartupselladvanced'}
      </i>
    </li>
  </ul>
{/if}
