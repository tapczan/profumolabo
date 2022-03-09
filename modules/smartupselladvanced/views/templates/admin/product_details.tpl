{*
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   Addons PrestaShop license limitation
 * @see       /LICENSE
 *
 * International Registered Trademark & Property of INVERTUS, UAB
 *}

<div class="panel">
    <h3>
        <i class="icon icon-info"></i>
        {l s='Selected product' mod='smartupselladvanced'}
    </h3>

    <form class="form-wrapper row">
        <table class="table" width="100%">
            <thead>
            <tr class="nodrag nodrop">
                <th class="text-center fixed-width-xs">
                    {l s='Image' mod='smartupselladvanced'}
                </th>
                <th class="text-center">
                    {l s='ID' mod='smartupselladvanced'}
                </th>
                <th class="text-center">
                    {l s='Name' mod='smartupselladvanced'}
                </th>
                <th class="text-center">
                    {l s='Reference' mod='smartupselladvanced'}
                </th>
                <th class="text-center">
                    {l s='Ean13' mod='smartupselladvanced'}
                </th>
                <th class="text-center">
                  {l s='Price' mod='smartupselladvanced'}
                </th>
                <th class="text-center">
                    {l s='Preview' mod='smartupselladvanced'}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">
                    {if isset($current_product.image_url|escape:'htmlall':'UTF-8')}
                        <img src="{$current_product.image_url|escape:'htmlall':'UTF-8'}" />
                    {/if}
                </td>
                <td class="text-center">
                    {$current_product.id|escape:'htmlall':'UTF-8'}
                </td>
                <td class="text-center">
                    {$current_product.name|escape:'htmlall':'UTF-8'}
                </td>
                <td class="text-center">
                    {$current_product.reference|escape:'htmlall':'UTF-8'}
                </td>
                <td class="text-center">
                    {$current_product.ean13|escape:'htmlall':'UTF-8'}
                </td>
                <td class="text-center">
                  {$price|escape:'htmlall':'UTF-8'}
                </td>
                <td class="text-center">
                    <a target="_blank" class="btn btn-default" href="{Context::getContext()->link->getProductLink($current_product.id)|escape:'htmlall':'UTF-8'}" >
                        <i class="process-icon-preview"></i>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <div class="clearfix"></div>

    <br />

    <div class="clearfix"></div>

    <div class="panel-footer text-center" style="height: auto;">

        <a href="{if $link_previous}{$link_previous.link|escape:'htmlall':'UTF-8'}{/if}"
           class="hidden-xs"
           title="{if $link_previous}{$link_previous.name|escape:'htmlall':'UTF-8'}{/if}"
        >
            <button class="btn btn-default pull-left" type="button"{if !$link_previous} disabled="disabled"{/if}>
                <i class="process-icon-back"></i>
                {l s='Previous product' mod='smartupselladvanced'}
            </button>
        </a>

        <div class="search_product_container col-lg-4 col-sm-7 col-lg-offset-3">
          <div class="text-left">
            <input id="product_search_query_input" name="product_search_query" type="text" placeholder="{l s='Search main product (min. 3 symbols)' mod='smartupselladvanced'}" />
            <small class="form-text">{l s='Type at least 3 letters and select product from dropdown list' mod='smartupselladvanced'}</small>
            <input type="hidden" name="id_product_search_query" id="id_product_search_query" value="">
            <div class="search_results" id="product_search_query_search_result"></div>
          </div>
        </div>

        <a href="{if $link_next}{$link_next.link|escape:'htmlall':'UTF-8'}{/if}" class="hidden-xs" title="{if $link_next}{$link_next.name|escape:'htmlall':'UTF-8'}{/if}">
            <button class="btn btn-default pull-right" type="button"{if !$link_next} disabled="disabled"{/if}>
                <i class="process-icon-next"></i>
                {l s='Next product' mod='smartupselladvanced'}
            </button>
        </a>

        <div class="visible-xs">&nbsp;</div>

        <div class="row">

            {if $link_previous}
                <a href="{$link_previous.link|escape:'htmlall':'UTF-8'}" class="visible-xs col-xs-6">
                    <button class="btn btn-default col-xs-12" type="button">
                        <i class="process-icon-back"></i>
                        {l s='Previous product' mod='smartupselladvanced'}
                    </button>
                </a>
            {/if}

            {if $link_next}
                <a href="{$link_next.link|escape:'htmlall':'UTF-8'}" class="visible-xs col-xs-6">
                    <button class="btn btn-default col-xs-12 pull-right" type="button">
                        <i class="process-icon-next"></i>
                        {l s='Next product' mod='smartupselladvanced'}
                    </button>
                </a>
            {/if}

        </div>

        <div class="clearfix"></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
