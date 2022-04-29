<div class="col-md-12 product-category">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class="product-category__title">
          {$category.name}
        </h1>

        {if $category.description}
          <div class="product-category__description">
            {$category.description nofilter}
          </div>
        {/if}
 
        {*
        {if $category.id == 123 || $category.id == 124 || $category.id == 125 }
            <div class="product-category__image">
              <img src="{$category.image.large.url}" alt="{$category.name}">
            </div>
        {/if}      
        *}

        {*
        {if $category.level_depth > 2 }    
          <div class="product-category__image">
            <img src="{$category.image.large.url}" alt="{$category.name}" style="margin: 0 auto;">
          </div>
        {/if}
        *}
  
        {if $category.level_depth <= 2 }  
          {if isset($subcategories) && $subcategories|@count > 0}
              {block name='subcategory_list'}
                <div class="product-category__listing">
                  {include file='catalog/_partials/subcategories.tpl' subcategories=$subcategories}
                </div>
              {/block}
          {/if}
        {/if}  
      </div>
    </div>

    <div class="product-category__main">
      <div class="row">
        <div class="col-md-12 filter-top-wrapper">
          <div class="filter-top-box">
            <span class="js-filter-top-show filter-top-show">
              {l s='Filter By' d='Shop.Theme.Global'}
            </span>
          </div>
          {block name='product_list_top'}
            {include file='catalog/_partials/products-top.tpl' listing=$listing}
          {/block}
        </div>
        <div class="col-md-3 js-filter-wrapper filter-wrapper">
          <div class="product-filter">
            {hook h='displayLeftColumn'}
          </div>
        </div>
        <div class="col-md-9 js-listing-wrapper listing-wrapper--default">
          <div class="product-listing">
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