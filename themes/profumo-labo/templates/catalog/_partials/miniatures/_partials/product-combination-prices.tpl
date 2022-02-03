{block name='product_price_and_shipping'}
    {if $product.show_price}
        <div class="product-miniature__pricing text-center">
            {*------Display default attributes in product list-----*}
            {if isset($product.attribute_combinations) && !empty($product.attribute_combinations)}
                  {foreach from=$product.attribute_combinations item=combination}
                     {if floatval($combination.price)}
                        <span class="price price-attribute{if $combination.default_on} price-attribute--default default-attributes {/if}">
                            {$combination.price} / {$combination.attribute_name}
                        </span>
                     {else}
                        {include file='catalog/_partials/miniatures/_partials/product-prices.tpl'}
                        {break}
                     {/if}
                  {/foreach}
            {/if}
        </div>
    {/if}
{/block}


