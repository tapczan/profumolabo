{block name='content'}
<section id="main">
    <div class="product-single">
        <div class="product-single__info">

            <div class="blockfeaturedproduct__info">

                <div class="product-rating">
                    {hook h='displayProductListReviews' product=$product}
                </div>

                {block name='page_header_container'}
                    {block name='page_header'}
                        <h1 class="product-title">
                            <a href="{$product.url|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a>
                            <div class="js-product-add-to-cart blockfeaturedproduct__wishlist">
                                <span
                                class="wishlist-button"
                                data-url="{$url|default:FALSE}"
                                data-product-id="{$product.id}"
                                data-product-attribute-id="{$product.id_product_attribute}"
                                data-is-logged="{$customer.is_logged}"
                                data-list-id="1"
                                data-checked="true"
                                data-is-product="true"
                                ></span>
                                {hook h='displayProductActions' product=$product}
                            </div>
                        </h1>
                    {/block}
                {/block}

                <div class="product-reference">
                    <span class="product-inspired">
                        {$product.reference}
                    </span>
                    <span class="product-brand">
                    {if isset($product.manufacturer_name)}<span class="product_manufacturer_name">{$product.manufacturer_name}</span>{/if}
                    </span>
                </div>

                {block name='product_unit_price'}
                    {if $product.unit_price_full}
                        <p class="product-unit-price small">{l s='%unit_price%' d='Shop.Theme.Catalog' sprintf=['%unit_price%' => $product.unit_price_full]}</p>
                    {/if}
                {/block}
                
                {block name='product_prices'}
                    <div class="product-prices">
                        <div>
                            <span class="price price--lg">{$product.regular_price}</span>
                        </div>
                    </div>
                {/block}

                <span class="product-stock-info">
                    <section class="product-discounts js-product-discounts">
                        {assign var='fromQuantity' value=SpecificPrice::getSpecificPriceByID($product.id)}
                        {foreach $fromQuantity item='quantity' name='quantity'}
                            {l s='Buy <span class="product-stock-info__num">%quantity%</span> pieces for a discounted price' d='Shop.Theme.Global' sprintf=['%quantity%' => $quantity.from_quantity]}<br/>
                        {/foreach}   
                    </section>
                </span>
            
                <div class="product-actions js-product-actions">
                    {block name='product_buy'}
                        <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                            <input type="hidden" name="token" value="{$static_token}">
                            <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                            <input type="hidden" name="id_customization" value="{isset($product.id_customization)}" id="product_customization_id" class="js-product-customization-id">
                
                            <div class="product-variants js-product-variants home-custom-variants mb-3">
                                {if isset($product.attributes) && !empty($product.attributes)}
                                    {foreach from=$product.attributes item=attribute}
                                        <div class="product-variants-item">
                                        <p class="control-label h6 mb-2" style="font-weight:500">{$attribute.group}</p>

                                        {if isset($product.attribute_combinations) && !empty($product.attribute_combinations)}
                                            <ul class="variants-selection">
                                                {foreach from=$product.attribute_combinations item=combination}
                                                    <li class="input-container attribute-radio col-auto px-1 mb-2">
                                                        <label class="attribute-radio__label">
                                                        <input style="display: none" class="input-radio attribute-radio__input" type="radio" data-product-attribute="{$combination.id_attribute_group}" name="group[{$combination.id_attribute_group}]" value="{$combination.id_attribute}" title="{$combination.attribute_name}" {if $combination.default_on} checked="checked" {/if}>
                                                            <span class="attribute-radio__text">{$combination.attribute_name}</span>
                                                        </label>
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                        </div>
                                    {/foreach}
                                {/if}
                            </div>
                            

                            {block name='product_pack'}
                                {if isset($packItems)}
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
                    <p id="product_ean13"{if empty($product->ean13) || !$product->ean13} style="display: none;"{/if}>
                        <label>{l s='Ean13:'} </label>
                        <span {if !empty($product->ean13) && $product->ean13} content="{$product->ean13}"{/if}>{$product->ean13|escape:'html':'UTF-8'}</span>
                    </p>
                </div>

                <div class="product-accordion" id="productSingleAccordion">
                    <div class="product-accordion__item">
                        <div class="product-accordion__header" id="productAccordionHeader1" data-toggle="collapse" data-target="#productAccordionContent1" aria-expanded="true" aria-controls="productAccordionContent1">
                            {l s='Description' d='Shop.Theme.Global'}
                        </div>
                        <div class="product-accordion__body collapse show" id="productAccordionContent1" aria-labelledby="productAccordionHeader1" data-parent="#productSingleAccordion">
                            <p>{$product.description_short nofilter}</p>
                            <a href="{$product.url|escape:'htmlall':'UTF-8'}">
                                {l s='Product Details' d='Shop.Theme.Global'}
                            </a> 
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
{/block}