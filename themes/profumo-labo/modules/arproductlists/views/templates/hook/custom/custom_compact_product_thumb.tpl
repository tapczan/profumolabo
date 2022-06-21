{block name='product_cover'}

    <div class="blockfeaturedproduct__img images-container js-images-container">
        <div class="js-product-add-to-cart blockfeaturedproduct__wishlist">
      <span
              class="wishlist-button"
              data-url="{$url|default:FALSE}"
              data-product-id="{$product.id|default:FALSE}"
              data-product-attribute-id="{$product.id_product_attribute}"
              data-is-logged="{$customer.is_logged}"
              data-list-id="1"
              data-checked="true"
              data-is-product="true"
      ></span>
            {hook h='displayProductActions' product=$product}
        </div>

        <div class="js-blockfeaturedproduct">
            {if $product.images}
                {foreach from=$product.images item=image}
                    <div class="product-img">
                        <a href="{$image.bySize.product_popup.url}" data-fancybox="blockfeaturedproduct-featured-img" class="product-img__url js-fancybox-img">
                            <img
                                    src="{$image.bySize.medium_default_x2.url}"
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
    </div>
{/block}

{hook h='displayAfterProductThumbs' product=$product}
