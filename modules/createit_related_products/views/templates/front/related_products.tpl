<div class="row">
    {foreach $products as $singleProduct}
        <div class="col">
            {block name='product_miniature'}
                {include file='catalog/_partials/miniatures/product.tpl' product=$singleProduct}
            {/block}
        </div>
    {/foreach}
</div>