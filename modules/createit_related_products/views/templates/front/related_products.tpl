<div class="row createit-related-products">
    
    {if $products|@count > 0}
        <div class="container">
            <h2 class="h2 text-center createit-related-products__title">
                {l s='You may also like' d='Modules.Createitrelatedproducts.Admin'}
            </h2>
        </div>
    {/if} 

    <div class="createit-related-products__slider js-createit-related-products-slider">
        {foreach $products as $singleProduct}
            <div class="col">
                {block name='product_miniature'}
                    {include file='catalog/_partials/miniatures/product.tpl' product=$singleProduct}
                {/block}
            </div>
        {/foreach}
    </div>
    
</div>