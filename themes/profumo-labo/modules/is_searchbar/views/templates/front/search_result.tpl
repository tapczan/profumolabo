
<div class="search-result">
  {assign var='countProductsShow' value=$products|count}
  {assign var='countProductsMore' value=$moreResultsCount}
  {assign var='countProductsTotal' value=$countProductsShow + $countProductsMore}
  
  {if $countProductsTotal}
    <h2 class="header__search-title">
      {if $countProductsTotal == 1}
        {l s='FOUND PRODUCTS %countTotal% pc.' d='Shop.Theme.Catalog' sprintf=['%countTotal%' => $countProductsTotal]}
      {else}
        {l s='FOUND PRODUCTS %countTotal% pcs.' d='Shop.Theme.Catalog' sprintf=['%countTotal%' => $countProductsTotal]}
      {/if}
    </h2>
  {/if}

  {if $products}
    <div class="search-result__products row">
      {foreach from=$products item=$product}
        {include file="themes/profumo-labo/modules/is_searchbar/views/templates/front/product.tpl"}
      {/foreach}
    </div>

    {if $moreResults}
      <div class="search-result__bottom">
        <a href="{$moreResults}" class="btn btn-block btn-outline-secondary btn-sm">
          {l
            s='Show the remaining %qty% products'
            sprintf=[
              '%qty%' => $moreResultsCount
            ]
            d='Shop.Istheme'
          }
        </a>
      </div>
    {/if}
  {else}
    <div class="search-result__not-result">
      {l s='There are no matching results' d='Shop.Istheme'}
    </div>
  {/if}
</div>
