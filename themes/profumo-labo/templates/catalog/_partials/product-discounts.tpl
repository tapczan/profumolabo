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
<section class="product-discounts js-product-discounts">
  {if $product.quantity_discounts}

    <span class="product-stock-info">
      {foreach from=$product.quantity_discounts item='quantity_discount' name='quantity_discounts'}
          {if $pslanguage == 'pl'}
            Kup <span class="product-stock-info__num"> {$quantity_discount.quantity}</span> sztuk po obniżonej cenie  
          {else if $pslanguage == 'en'}
            Buy <span class="product-stock-info__num"> {$quantity_discount.quantity}</span> pieces for a discounted price
          {/if}
      {/foreach}
    </span>
    
    {*
    <p class="h6 mb-2">{l s='Volume discounts' d='Shop.Theme.Catalog'}</p>
    {block name='product_discount_table'}
      <table class="table mb-4">
        <thead>
        <tr>
          <th>{l s='Quantity' d='Shop.Theme.Catalog'}</th>
          <th>{$configuration.quantity_discount.label}</th>
          <th>{l s='You Save' d='Shop.Theme.Catalog'}</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$product.quantity_discounts item='quantity_discount' name='quantity_discounts'}
          <tr data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value}" data-discount-quantity="{$quantity_discount.quantity}">
            <td><strong>{$quantity_discount.quantity}</strong></td>
            <td><span class="price">{$quantity_discount.discount}<span></td>
            <td><span class="price">{$quantity_discount.save}<span></td>
          </tr>
        {/foreach}
        </tbody>
      </table>

    {/block}
    *}

  {/if}
</section>
