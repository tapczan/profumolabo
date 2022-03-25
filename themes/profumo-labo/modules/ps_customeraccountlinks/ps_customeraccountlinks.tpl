{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{*
<div id="block_myaccount_infos" class="col-lg-3 col-md-4 col-12 mb-lg-4">

  <div class="footer-collapse d-flex align-items-center mb-3 justify-content-between position-relative">
    <span class="h6 mb-md-5 mb-0 text-uppercase text-white fw-regular">{l s='Your account' d='Shop.Theme.Customeraccount'}</span>
    <a href="#footer_account_list" class="icon-collapse stretched-link d-block d-md-none" data-toggle="collapse">
      <i class="material-icons d-block"></i>
    </a>
  </div>

  <div class="collapse d-md-block" id="footer_account_list">
    <ul class="links-list">
      {foreach from=$my_account_urls item=my_account_url}
        <li class="links-list__elem">
          <a class="links-list__link" href="{$my_account_url.url}" title="{$my_account_url.title}" rel="nofollow">
            {$my_account_url.title}
          </a>
        </li>
      {/foreach}
    </ul>
  </div>

</div>
*}

<div class="footer-card">
  <div class="footer-card__header" id="headingOne">
    <h2 class="footer-card__title" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <a href="{$link->getPageLink('my-account', true, $language.id)}">
          {l s='Your account' d='Shop.Theme.Customeraccount'}
        </a>
        <i class="material-icons"></i>
    </h2>
  </div>
  <div id="collapseOne" class="footer-card__content collapse show" aria-labelledby="headingOne" data-parent="#accordionFooter">
    <div class="footer-card__body">
      <ul class="links-list">

        {if !$logged}
        <li class="links-list__elem">
            <a class="links-list__link" href="{$link->getPageLink('my-account', true, $language.id)}" title="{l s='Login' d='Shop.Theme.Global'}" rel="nofollow">
              {l s='Login' d='Shop.Theme.Global'}
            </a>
        </li>
        {/if}

        <li class="links-list__elem">
            <a class="links-list__link" href="{$link->getPageLink('cart',true, $language.id)}" title="{l s='My cart' d='Shop.Theme.Global'}" rel="nofollow">
             {l s='My cart' d='Shop.Theme.Global'}
            </a>
        </li>
        <li class="links-list__elem">
            <a class="links-list__link" href="{$link->getPageLink('my-account', true, $language.id)}" title="{l s='My Account' d='Shop.Theme.Global'}" rel="nofollow">
              {l s='Personal data' d='Shop.Theme.Global'}
            </a>
        </li>
        <li class="links-list__elem">
            <a class="links-list__link" href="{$link->getPageLink('addresses', true, $language.id)}" title="{l s='Addresses' d='Shop.Theme.Global'}" rel="nofollow">
              {l s='Addresses' d='Shop.Theme.Global'}
            </a>
        </li>
        <li class="links-list__elem">
            <a class="links-list__link" href="{$link->getPageLink('history', true, $language.id)}" title="{l s='Order History' d='Shop.Theme.Global'}" rel="nofollow">
               {l s='Order History' d='Shop.Theme.Global'}
            </a>
        </li>
        <li class="links-list__elem">
            <a class="links-list__link" href="{$link->getModuleLink('blockwishlist','lists')}" title="{l s='Favourite products' d='Shop.Theme.Global'}" rel="nofollow">
              {l s='Favourite products' d='Shop.Theme.Global'}
            </a>
        </li>
        <li class="links-list__elem">
            <a class="links-list__link" href="{$link->getPageLink('contact', true, $language.id)}" title="{l s='Contact with the store' d='Shop.Theme.Global'}" rel="nofollow">
              {l s='Contact with the store' d='Shop.Theme.Global'}
            </a>
        </li>


        {* // translation is buggy
        {foreach from=$my_account_urls item=my_account_url}
          <li class="links-list__elem">
            <a class="links-list__link" href="{$my_account_url.url}" title="{$my_account_url.title}" rel="nofollow">
              {$my_account_url.title}
            </a>
          </li>
        {/foreach}
        *}

      </ul>
    </div>
  </div>
</div>