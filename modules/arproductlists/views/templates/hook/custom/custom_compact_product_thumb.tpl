{block name='product_cover'}
  <div class="blockfeaturedproduct__img js-blockfeaturedproduct">
    {if $product.images}
      {foreach from=$product.images item=image}
        <div class="product-img">
          <div class="js-product-add-to-cart">
            {hook h='displayProductActions'}
          </div>

          <a href="{$image.bySize.large_default.url}" data-fancybox="blockfeaturedproduct-featured-img" class="product-img__url">
            <img 
              src="{$image.bySize.medium_default.url}" 
              {if !empty($product.default_image.legend)}
                alt="{$image.legend}" title="{$image.legend}"
              {else}
                alt="{$product.name}"
              {/if}
              class="product-img__asset"
            >
          </a>
        </div>
      {/foreach}
    {else}
      <div class="product-img">
        <img src="{$urls.no_picture_image.bySize.large_default.url}" class="rounded img-fluid" loading="lazy">
      </div>
    {/if}
  </div>
{/block}

{hook h='displayAfterProductThumbs' product=$product}
