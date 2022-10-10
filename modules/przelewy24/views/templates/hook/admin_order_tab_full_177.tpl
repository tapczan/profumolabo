{*
*
* @author Przelewy24
* @copyright Przelewy24
* @license https://www.gnu.org/licenses/lgpl-3.0.en.html
*
*}
<div class="tab-pane" id="p24-admin-tab-pane" role="tabpanel" >
    <table class="table">
        <thead>
        <tr>
            <th><span class="title_box"> </span></th>
            <th><span class="title_box">{l s='Date' mod='przelewy24'}</span></th>
            <th><span class="title_box">{l s='Link' mod='przelewy24'}</span></th>
            <th><span class="title_box">{l s='P24 ID' mod='przelewy24'}</span></th>
            <th><span class="title_box">{l s='Amount' mod='przelewy24'}</span></th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$p24Payments key='index' item='p24Payment'}
            <tr>
                <td><span>{$index+1}</span></td>
                <td><span>{$p24Payment->date_add}</span></td>
                <td><span><a class="" href="{$link}">{$link}</a></span></td>
                <td><span>{$p24_order_id|escape:'html':'UTF-8'}</a></span></td>
                <td><span>{$p24Payment->amount}</span></td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>
