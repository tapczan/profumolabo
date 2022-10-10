{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{extends file="helpers/form/form.tpl"}
{block name="input"}
	{if $input.type == 'paddings'}
        <div class="form-group row">
            <label class="col-md-1 control-label">{l s='top' mod='x13infobar'}</label>
            <div class="col-md-2">
                <input value="{$fields_value[$input.name]['top']}" type="text" class="form-control" name="{$input.name}[top]" placeholder="0">
            </div>
            <label class="col-md-1 control-label">{l s='right' mod='x13infobar'}</label>
            <div class="col-md-2">
                <input value="{$fields_value[$input.name]['right']}" type="text" class="form-control" name="{$input.name}[right]" placeholder="0">
            </div>
            <label class="col-md-1 control-label">{l s='bottom' mod='x13infobar'}</label>
            <div class="col-md-2">
                <input value="{$fields_value[$input.name]['bottom']}" type="text" class="form-control" name="{$input.name}[bottom]" placeholder="0">
            </div>
            <label class="col-md-1 control-label">{l s='left' mod='x13infobar'}</label>
            <div class="col-md-2">
                <input value="{$fields_value[$input.name]['left']}" type="text" class="form-control" name="{$input.name}[left]" placeholder="0">
            </div>
        </div>
    {elseif $input.type == 'border'}
        <div class="form-group row">
            <label class="col-md-1 control-label">{l s='width' mod='x13infobar'}</label>
            <div class="col-md-2">
                <input value="{$fields_value[$input.name]['width']}" type="text" class="form-control" name="{$input.name}[width]" placeholder="0">
            </div>
            <label class="col-md-1 control-label">{l s='style' mod='x13infobar'}</label>
            <div class="col-md-2">
                <select name="{$input.name}[style]" class="form-control">
                    <option value="solid" {if $fields_value[$input.name]['style'] == 'solid'}selected{/if}>{l s='solid' mod='x13infobar'}</option>
                    <option value="dotted" {if $fields_value[$input.name]['style'] == 'dotted'}selected{/if}>{l s='dotted' mod='x13infobar'}</option>
                    <option value="dashed" {if $fields_value[$input.name]['style'] == 'dashed'}selected{/if}>{l s='dashed' mod='x13infobar'}</option>
                    <option value="double" {if $fields_value[$input.name]['style'] == 'double'}selected{/if}>{l s='double' mod='x13infobar'}</option>
                    <option value="none" {if $fields_value[$input.name]['style'] == 'none'}selected{/if}>{l s='none' mod='x13infobar'}</option>
                </select>
            </div>
            <label class="col-md-1 control-label">{l s='color' mod='x13infobar'}</label>
            <div class="col-md-2">
                <input type="color" data-hex="true" class="color mColorPickerInput" name="{$input.name}[color]" value="{$fields_value[$input.name]['color']|default:false}">
            </div>
        </div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}