{block name='product_price_and_shipping'}
    {if $product.show_price}
        <div class="product-miniature__pricing text-center">
            {*------Display default attributes in product list-----*}
            {if isset($product.attribute_combinations) && !empty($product.attribute_combinations)}
                  {foreach from=$product.attribute_combinations item=combination}
                    <span class="price price-attribute{if $combination.default_on} price-attribute--default default-attributes {/if}">
                        {if $product.has_discount}
                            <span class="price price--discounted" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">{$combination.discount_price}</span>
                            <span class="price price--regular" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$combination.price}</span>
                        {else}
                            {$combination.price}
                        {/if}

                        / {$combination.attribute_name}
                    </span>
                  {/foreach}
            {/if}
        </div>
    {/if}
{/block}


