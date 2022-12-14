{**
 * 2007-2020 PrestaShop SA and Contributors
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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{if !empty($subcategories)}
  {if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories) }
    <div id="subcategories" class="subcategory my-4">
      <ul class="row">
        {foreach from=$subcategories item=subcategory}
          {if $subcategory@index < 6}
            <div class="col-xl-2 col-lg-3 col-4">
              <div class="subcategory-wrapper">
                <a class="subcategory-wrapper__image" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
                  <img height="{$subcategory.image.bySize.category_default.height}" width="{$subcategory.image.bySize.category_default.width}" src="{$subcategory.image.bySize.category_default.url}" alt="{$subcategory.name|escape:'html':'UTF-8'}" loading="lazy">
                </a>
                <h5 class="subcategory-wrapper__title">
                  {$subcategory.name|truncate:45:'...'|escape:'html':'UTF-8'}
                </h5>
              </div>
            </div>
          {/if}
        {/foreach}
      </ul>
    </div>
  {/if}
{/if}
