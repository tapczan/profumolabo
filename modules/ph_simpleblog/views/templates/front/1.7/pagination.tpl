<!-- Pagination -->
<nav class="simpleblog__listing__pagination pagination">
    <div class="col-md-4">
    {block name='pagination_summary'}
      {l s='Showing %from%-%to% of %total% item(s)' d='Shop.Theme.Catalog' sprintf=['%from%' => $pagination.items_shown_from ,'%to%' => $pagination.items_shown_to, '%total%' => $pagination.total_items]}
    {/block}
  </div>

  <div class="col-md-6 offset-md-2 pr-0">
    {block name='pagination_page_list'}
     {if $pagination.should_be_displayed}
        <ul class="page-list clearfix text-sm-center">
          {foreach from=$pagination.pages item="page"}
            <li {if $page.current} class="current" {/if}>
              {if $page.type === 'spacer'}
                <span class="spacer">&hellip;</span>
              {else}
                <a
                  rel="{if $page.type === 'previous'}prev{elseif $page.type === 'next'}next{else}nofollow{/if}"
                  href="{$page.url}"
                  class="{if $page.type === 'previous'}previous {elseif $page.type === 'next'}next {/if}{['disabled' => !$page.clickable, 'js-search-link' => false]|classnames}"
                >
                  {if $page.type === 'previous'}
                    {if $isWarehouse|default:false}
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    {else}
                    <i class="material-icons">&#xE314;</i>
                    {/if}
                    {l s='Previous' d='Shop.Theme.Actions'}
                  {elseif $page.type === 'next'}
                    {l s='Next' d='Shop.Theme.Actions'}
                    {if $isWarehouse|default:false}
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                    {else}
                    <i class="material-icons">&#xE315;</i>
                    {/if}
                  {else}
                    {$page.page}
                  {/if}
                </a>
              {/if}
            </li>
          {/foreach}
        </ul>
      {/if}
    {/block}
  </div>
</nav>