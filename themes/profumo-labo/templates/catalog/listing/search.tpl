{*
 * This file allows you to customize your search page.
 * You can safely remove it if you want it to appear exactly like all other product listing pages
 *}
{extends file='catalog/listing/product-list.tpl'}

{block name="error_content"}
  <h4>{l s='No matches were found for your search' d='Shop.Theme.Catalog'}</h4>
  <p>{l s='Please try other keywords to describe what you are looking for.' d='Shop.Theme.Catalog'}</p>
  <p>
    {l s="Haven't found inspiration for yourself?" d="Shop.Theme.Catalog"}
    <a href="{$link->getPageLink('contact', true, $language.id)}" target="_blank">
      {l s="Contact us" d="Shop.Theme.Catalog"}
    </a>
  </p>
{/block}
