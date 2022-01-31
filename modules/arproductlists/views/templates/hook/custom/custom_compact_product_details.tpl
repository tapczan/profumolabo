<div class="blockfeaturedproduct__info">
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
            <a href="{$product.url|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a>
            <span
            class="wishlist-button"
            data-url="{$url}"
            data-product-id="{$product.id}"
            data-product-attribute-id="{$product.id_product_attribute}"
            data-is-logged="{$customer.is_logged}"
            data-list-id="1"
            data-checked="true"
            data-is-product="true"
            ></span>
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
    
    {block name='product_prices'}
        <div class="product-price">
            {include file='./product-prices.tpl'}
        </div>
    {/block}

    <span class="product-stock-info">
        <section class="product-discounts js-product-discounts">
            <span class="product-stock-info"> Ostatnie <span class="product-stock-info__num"> 5</span> sztuk w tej cenie </span>
        </section>
    </span>

    <div class="product-variants js-product-variants mb-3">
        <div class="product-variants-item">
            <p class="control-label h6 mb-2">Rozmiar</p>
            <ul id="group_1" class="variants-selection">
                    <li class="input-container attribute-radio col-auto px-1 mb-2">
                        <label class="attribute-radio__label">
                        <input style="display: none;" class="input-radio attribute-radio__input" type="radio" data-product-attribute="1" name="group[1]" value="1" title="S" checked="checked">
                        <span class="attribute-radio__text">S</span>
                        </label>
                    </li>
                                <li class="input-container attribute-radio col-auto px-1 mb-2">
                        <label class="attribute-radio__label">
                        <input style="display: none;" class="input-radio attribute-radio__input" type="radio" data-product-attribute="1" name="group[1]" value="2" title="M">
                        <span class="attribute-radio__text">M</span>
                        </label>
                    </li>
                                <li class="input-container attribute-radio col-auto px-1 mb-2">
                        <label class="attribute-radio__label">
                        <input style="display: none;" class="input-radio attribute-radio__input" type="radio" data-product-attribute="1" name="group[1]" value="3" title="L">
                        <span class="attribute-radio__text">L</span>
                        </label>
                    </li>
                                <li class="input-container attribute-radio col-auto px-1 mb-2">
                        <label class="attribute-radio__label">
                        <input style="display: none;" class="input-radio attribute-radio__input" type="radio" data-product-attribute="1" name="group[1]" value="4" title="XL">
                        <span class="attribute-radio__text">XL</span>
                        </label>
                    </li>
            </ul>
        </div>
    </div>
    
    {* 
    {block name='product_discounts'}
        <span class="product-stock-info">
            {include file='./product-discounts.tpl'}
        </span>
    {/block}
    

    {if $product.has_discount}
        {hook h='displayProductPriceBlock' product=$product type="old_price"}

        <span class="sr-only">{l s='Regular price' mod='arproductlists'}</span>
        <span class="regular-price">{$product.regular_price|escape:'htmlall':'UTF-8'}</span>
        {if $product.discount_type === 'percentage'}
        <span class="discount-percentage discount-product">{$product.discount_percentage|escape:'htmlall':'UTF-8'}</span>
        {elseif $product.discount_type === 'amount'}
        <span class="discount-amount discount-product">{$product.discount_amount_to_display|escape:'htmlall':'UTF-8'}</span>
        {/if}
    {/if}

    
    {$product.is_customizable}
    {if $product.is_customizable && count($product.customizations.fields)}
        {block name='product_customization'}
            <div class="product-variation">
            {include file="./product-customization.tpl" customizations=$product.customizations}
            </div>
        {/block}
    {/if}
    *}
   

    <div class="product-actions js-product-actions">
        {block name='product_buy'}
            <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
            <input type="hidden" name="token" value="{$static_token}">
            <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
            <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id" class="js-product-customization-id">

            {block name='product_variants'}
                {include file='./product-variants.tpl'}
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
    <p id="product_ean13"{if empty($product->ean13) || !$product->ean13} style="display: none;"{/if}>
        <label>{l s='Ean13:'} </label>
        <span {if !empty($product->ean13) && $product->ean13} content="{$product->ean13}"{/if}>{$product->ean13|escape:'html':'UTF-8'}</span>
    </p>
    </div>

    <div class="product-accordion" id="productSingleAccordion">
        <div class="product-accordion__item">
            <div class="product-accordion__header" id="productAccordionHeader1" data-toggle="collapse" data-target="#productAccordionContent1" aria-expanded="true" aria-controls="productAccordionContent1">
                {if $pslanguage == 'pl'}
                    OPIS     
                {else if $pslanguage == 'en'}
                    DESCRIPTION
                {/if}
            </div>
            <div class="product-accordion__body collapse show" id="productAccordionContent1" aria-labelledby="productAccordionHeader1" data-parent="#productSingleAccordion">
                <p>{$product.description_short nofilter}</p>
                <a href="{$product.url|escape:'htmlall':'UTF-8'}">
                    {if $pslanguage == 'pl'}
                    SZCZEGÓŁY PRODUKTU     
                    {else if $pslanguage == 'en'}
                    PRODUCT DETAILS
                    {/if}
                </a> 
            </div>
        </div>
    </div>

</div>