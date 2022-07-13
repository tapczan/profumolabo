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

{if isset($category)}
{assign var='catUrl' value=$page.canonical}
{assign var='catTitle' value=$page.meta.title}
{assign var='catMedia' value=$category.image.bySize.category_default.url}

<ul class="product-social">
    <li class="product-social__item product-social__item--label">
        {l s='Share' d='Shop.Theme.Actions'}
    </li>
    <li class="product-social__item">
        {if $catUrl}
            <a href="https://www.facebook.com/sharer.php?u={$catUrl}" target="_blank">
                <span class="product-social__icon product-social__icon--facebook"></span>
            </a>
        {else}
            <span class="product-social__icon product-social__icon--facebook"></span>
        {/if}
    </li>
    <li class="product-social__item">
        {if $catUrl && $catTitle}
            <a href="https://twitter.com/intent/tweet?text={$catTitle}%20{$catUrl}" target="_blank">
                <span class="product-social__icon product-social__icon--twitter"></span>
            </a>
        {else}
            <span class="product-social__icon product-social__icon--twitter"></span>
        {/if} 
    </li>
    <li class="product-social__item">
        {if $catMedia && $catUrl}
            <a href="https://www.pinterest.com/pin/create/button/?media={$catMedia}&url={$catUrl}" target="_blank">
                <span class="product-social__icon product-social__icon--instagram"></span>
            </a>
        {else}
            <span class="product-social__icon product-social__icon--instagram"></span>
        {/if}        
    </li>
    {*<li class="product-social__item">
        <a href="product-social__link">
            <span class="product-social__icon product-social__icon--whatsapp"></span>
        </a>
    </li>*}
</ul>
{/if}