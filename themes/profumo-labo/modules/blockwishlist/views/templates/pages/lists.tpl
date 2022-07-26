{**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{extends file='customer/page.tpl'}

{block name='page_header_container'}
  <header class="page-header mb-4 text-center">
    <h1>{$wishlistsTitlePage}</h1>
  </header>
{/block}

{block name='page_content_container'}
  <div class="row my-6 personal-information">
      <div class="col-lg-8 mx-auto">
      <div class="row">
        <div class="col-lg-5 col-12">
        {include file="themes/profumo-labo/templates/customer/customer-links.tpl"}
        </div>
        <div class="col-lg-7 col-12">
          <div
            class="wishlist-container"
            data-url="{$url}"
            data-title=""
            data-empty-text="{l s='No wishlist found.' d='Modules.Blockwishlist.Shop'}"
            data-rename-text="{l s='Rename' d='Modules.Blockwishlist.Shop'}"
            data-share-text="{l s='Share' d='Modules.Blockwishlist.Shop'}"
            data-add-text="{$newWishlistCTA}"
          ></div>
          {include file="module:blockwishlist/views/templates/components/modals/share.tpl" url=$shareUrl}
          {include file="module:blockwishlist/views/templates/components/modals/rename.tpl" url=$renameUrl}   
        </div>
      </div>

      </div>
  </div>
{/block}


{block name='page_footer_container'}

  <div class="wishlist-footer-links">
    <a href="{$link->getPageLink('my-account', true)|escape:'html'}"><i class="material-icons">chevron_left</i>{l s='Return to your account' d='Modules.Blockwishlist.Shop'}</a>
    <a href="{$urls.base_url}"><i class="material-icons">home</i>{l s='Home' d='Shop.Theme.Global'}</a>
  </div>
{/block}