{block name='product_price_and_shipping'}
  {if $product.show_price}
    <div class="product-miniature__pricing text-right">

      {hook h='displayProductPriceBlock' product=$product type="before_price"}

      <span class="price {if $product.has_discount}price--discounted{/if}" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">{$product.price}</span>

      {if $product.has_discount}
        {hook h='displayProductPriceBlock' product=$product type="old_price"}
        <span class="price price--regular" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price}</span>
      {/if}

      {hook h='displayProductPriceBlock' product=$product type='unit_price'}

      {hook h='displayProductPriceBlock' product=$product type='weight'}
    </div>
  {/if}
{/block}
