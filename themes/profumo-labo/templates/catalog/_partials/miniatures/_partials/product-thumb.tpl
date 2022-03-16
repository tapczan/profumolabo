{block name='product_thumbnail'}
  <div class="product-miniature__thumb position-relative {$thumbExtraClass|default:''}">

    {$rolloverImage = false}
    {foreach $product.images as $productImage}
      {if $rolloverImage eq false && $productImage.bySize.home_default.url !== $product.cover.bySize.home_default.url}
        {$rolloverImage = $productImage.bySize.home_default.url}
      {/if}
    {/foreach}
    <a href="{$product.url}" class="product-miniature__thumb-link">
      {hook h='displayProductActions' }            
      <img
        {if $product.default_image}
          data-full-size-image-url="{$product.default_image.large.url}"
          src="{$product.default_image.bySize.home_default.url}"
          {*{generateImagesSources image=$product.default_image size='home_default'}*}
          {else}
          src="{$urls.no_picture_image.bySize.home_default.url}"
        {/if}
        alt="{if !empty($product.default_image.legend)}{$product.default_image.legend}{else}{$product.name|truncate:30:'...'}{/if}"
        loading="lazy"
        {if isset($product.images[1])} 
          data-rollover="{$rolloverImage}"
        {/if}
        class="img-fluid rounded lazyload rollover-images"
        width="{$product.default_image.bySize.home_default.width}"
        height="{$product.default_image.bySize.home_default.height}"
        />

      {include file='catalog/_partials/product-flags.tpl'}
    </a>

    {block name='quick_view'}
      <a class="quick-view product-miniature__quick-view" href="#" data-link-action="quickview">
        <i class="material-icons product-miniature__quick-view-icon">visibility</i>
        {l s='Quick view' d='Shop.Istheme'}
      </a>
    {/block}


    {block name='product_reviews'}
      {hook h='displayProductListReviews' product=$product}
    {/block}
  </div>
{/block}
