{if !empty($product_custom_fields)}
    {foreach $product_custom_fields as $custom_field}
        {$custom_field['content']|unescape: "html" nofilter}
    {/foreach}
{/if}