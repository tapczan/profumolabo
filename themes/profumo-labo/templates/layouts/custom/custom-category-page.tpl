<div class="col-md-12 product-category">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class="product-category__title">
          {$category.name}
        </h1>

        {if $category.description}
          <div class="product-category__description">
            {$category.description|strip_tags:'UTF-8'}
          </div>
        {/if}

        {if $category.image}
          <div class="product-category__image">
            <img src="{$category.image.large.url}" alt="{$category.name}">
          </div>
        {/if}

        {block name='subcategory_list'}
          {if isset($subcategories) && $subcategories|@count > 0}
            <div class="product-category__listing">
              {include file='catalog/_partials/subcategories.tpl' subcategories=$subcategories}
            </div>
          {/if}
        {/block}
      </div>
    </div>

    <div class="product-category__main">
      <div class="row">
        <div class="col-md-3">
          <div class="product-filter">
              {hook h='displayLeftColumn'}
          </div>
        </div>
          <div class="col-md-9">
            <div class="product-listing">
            
            {block name='product_list_top'}
              {include file='catalog/_partials/products-top.tpl' listing=$listing}
            {/block}

            <div>
              {block name='product_list'}
                {include file='catalog/_partials/products.tpl' listing=$listing productClass="col-xs-6 col-xl-4"}
              {/block}
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>