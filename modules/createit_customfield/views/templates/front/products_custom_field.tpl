{if !empty($product_custom_fields)}
    {foreach $product_custom_fields as $custom_field}
        {$custom_field.label_name}: {$custom_field.content}
    {/foreach}
{/if}