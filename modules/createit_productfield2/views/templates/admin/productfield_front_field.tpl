{if $product_object['is_linked'] == TRUE}
    <a href="{$product_object['product_url']}">{$product_object['product_name']}</a>
{else}
    {$product_object['product_name']}
{/if}