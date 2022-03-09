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

<input type="text" class="product-search" name="main_product" id="{$input_name|escape:'htmlall':'UTF-8'}_input" value="{$input_value|escape:'htmlall':'UTF-8'}">
<input type="hidden" class="js-sua-search-field-product-id" name="id_{$input_name|escape:'htmlall':'UTF-8'}" id="id_{$input_name|escape:'htmlall':'UTF-8'}" value="{$hidden_value|escape:'htmlall':'UTF-8'}">
<div class="search_results" id="{$input_name|escape:'htmlall':'UTF-8'}_search_result"></div>
<small class="form-text">{l s='Type at least 3 letters and select product from dropdown list' mod='smartupselladvanced'}</small>
