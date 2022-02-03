{block name='product_price_and_shipping'}
    {if $product.show_price}
        <div class="product-miniature__pricing text-center">
            {*------Display default attributes in product list-----*}
            {if isset($product.attribute_combinations) && !empty($product.attribute_combinations)}
                  {foreach from=$product.attribute_combinations item=combination}
                     {if floatval($combination.price)}
                        <span class="price {if $combination.default_on} default-attributes {/if}">
                            {$combination.price} / {$combination.attribute_name}
                        </span>
                        </br>
                     {else}
                        {include file='catalog/_partials/miniatures/_partials/product-prices.tpl'}
                        {break}
                     {/if}
                  {/foreach}
            {/if}
        </div>
    {/if}
{/block}


