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
  {foreach $linkBlocks as $linkBlock name=linkBlockItem}
    <div class="offset-lg-1 col-lg-3 col-md-4 col-12 mb-lg-4">
      {assign var=_expand_id value=10|mt_rand:100000}
      <div class="footer-collapse d-flex align-items-center mb-3 justify-content-between position-relative">
        <span class="h6 mb-md-5 mb-0 text-uppercase text-white fw-regular">{$linkBlock.title}</span>
        <a href="#footer_sub_menu_{$_expand_id}" class="icon-collapse stretched-link d-block d-md-none" data-toggle="collapse">
          <i class="material-icons d-block"></i>
        </a>
      </div>
      <div id="footer_sub_menu_{$_expand_id}" class="collapse d-md-block{if $smarty.foreach.linkBlockItem.index == 1} mb-md-5 pb-md-1{/if}">
        <ul class="links-list">
          {foreach $linkBlock.links as $link}
            <li class="links-list__elem">
              <a
                  id="{$link.id}-{$linkBlock.id}"
                  class="{$link.class} links-list__link"
                  href="{$link.url}"
                  title="{$link.description}"
                  {if !empty($link.target)} target="{$link.target}" {/if}
              >
                {$link.title}
              </a>
            </li>
          {/foreach}
        </ul>
      </div>
      {if $smarty.foreach.linkBlockItem.index == 1}
        <div class="row d-md-block d-none mb-md-3 pb-md-1">
        {widget name='ps_socialfollow'}
        </div>
      {/if}
    </div>
  {/foreach}
*}

{foreach $linkBlocks as $linkBlock key=key name=linkBlockItem}
{assign var=_expand_id value=10|mt_rand:100000}
{assign var=linkItemUrl value={$link->getCMSLink(17)}}
{if $key == 1}
  {assign var=linkItemUrl value=$link->getPageLink('contact',true, $language.id)}
{/if}
<div class="footer-card">
  <div class="footer-card__header" id="footer_{$_expand_id}">
    <h2 class="footer-card__title collapsed" type="button" data-toggle="collapse" data-target="#footer_collapse_{$_expand_id}" aria-expanded="false" aria-controls="footer_collapse_{$_expand_id}">
      <a href="{$linkItemUrl}">
        {$linkBlock.title}
      </a>
      <i class="material-icons"></i>
    </h2>
  </div>
  <div id="footer_collapse_{$_expand_id}" class="footer-card__content collapse" aria-labelledby="footer_{$_expand_id}" data-parent="#accordionFooter">
    <div class="footer-card__body">
      <ul class="links-list">
        {foreach $linkBlock.links as $link}
          <li class="links-list__elem">
            <a
                id="{$link.id}-{$linkBlock.id}"
                class="{$link.class} links-list__link"
                href="{$link.url}"
                title="{$link.description}"
                {if !empty($link.target)} target="{$link.target}" {/if}
            >
              {$link.title}
            </a>
          </li>
        {/foreach}
      </ul>
    </div>
  </div>
</div>
{/foreach}