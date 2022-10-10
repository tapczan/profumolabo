
<div class="search-result">
  {assign var='countProductsShow' value=$products|count}
  {if $newProducts}
    {assign var='newProductsCount' value=$newProducts|count}
  {else}
    {assign var='newProductsCount' value='0'}
  {/if}
  {assign var='countProductsMore' value=$moreResultsCount}
  {assign var='countProductsTotal' value=$countProductsShow + $newProductsCount}

  {if $countProductsTotal}
    <h2 class="header__search-title">
      {l s='FOUND PRODUCTS' d='Shop.Theme.Global'}: <span class="header__search-counter">{$countProductsTotal}</span> {if $countProductsTotal == 1}{l s='pc' d='Shop.Theme.Global'}{else}{l s='pcs' d='Shop.Theme.Global'}{/if}.
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
            s='Show all suggestions'
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
