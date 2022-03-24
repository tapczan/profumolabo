<div class="row">
    
    {if $products|@count > 0}
        <div class="container my-5 py-5">
            <h2 class="h2 text-center products-section-title text-uppercase">
                {l s='You may also like' d='Shop.Theme.Global'}
            </h2>
        </div>
    {/if} 

    {foreach $products as $singleProduct}
        <div class="col">
            {block name='product_miniature'}
                {include file='catalog/_partials/miniatures/product.tpl' product=$singleProduct}
            {/block}
        </div>
    {/foreach}
    
</div>