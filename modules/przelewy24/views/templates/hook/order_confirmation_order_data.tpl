{*
*
* @author Przelewy24
* @copyright Przelewy24
* @license https://www.gnu.org/licenses/lgpl-3.0.en.html
*
*}
{if $isMultiOrder }
    {*<input id="order-list-data-json" type="hidden" value="{$orderData}">*}
    <p class="alert alert-info">{l s='Multi warehouse order. Cart may contain additional products, not listed below.' mod='przelewy24'}</p>
{/if}
