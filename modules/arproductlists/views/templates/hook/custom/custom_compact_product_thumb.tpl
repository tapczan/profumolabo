{block name='product_cover'}
  <div class="blockfeaturedproduct__img js-blockfeaturedproduct">
    {if $product.images}
      {foreach from=$product.images item=image}
        <div class="product-img">
          <span
            class="wishlist-button product-wishlist--mobile"
            data-url="{$url}"
            data-product-id="{$product.id}"
            data-product-attribute-id="{$product.id_product_attribute}"
            data-is-logged="{$customer.is_logged}"
            data-list-id="1"
            data-checked="true"
            data-is-product="true"
          ></span>

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
