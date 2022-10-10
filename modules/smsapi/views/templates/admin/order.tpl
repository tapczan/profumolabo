{*
* 2007-2020 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script src="{$module_dir}views/js/back.js" type="text/javascript"></script>
 <div class="panel">
	<div class="panel-heading"><i class="icon icon-tags"></i> {l s='Send sms' mod='smsapi'}</div>
	<fieldset>
    <div class="row">
    <div class="col-xs-12">
            <form action="{$module_dir}lib.php" method="post" id="sendsms" target="_self" class="form-horizontal">
                <div class="form-group">
                <div class="col-xs-12">
                <textarea id="SMSTXT" class="textarea-autosize" name="SMSTXT" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 48px; margin-bottom:15px;">{$SMSORDER}</textarea>
                </div>
                <input type="hidden" name="SMSADMIN" value="1" />
                <input type="hidden" name="SMSID" value="{$SMSID}" />
                <input type="hidden" name="SMSPASS" value="{$SMSPASS}" />
                <input type="hidden" name="SMSSENDER" value="{$SMSSENDER}" />
                <input type="hidden" name="SMSECO" value="{$SMSECO}" />
                <input type="hidden" name="SMSFLASH" value="{$SMSFLASH}" />
                <input type="hidden" name="SMSTEST" value="{$SMSTEST}" />
                <input type="hidden" name="PHONE" value="{$PHONE}" />
                <input type="hidden" name="FIRST" value="{$FIRST}" />
                <input type="hidden" name="LAST" value="{$LAST}" /> 
                <input type="hidden" name="MAIL" value="{$MAIL}" />  
                <input type="hidden" name="ORDERID" value="{$ORDERID}" />
                <input type="hidden" name="REFERENCE" value="{$REFERENCE}" />   
                <input type="hidden" name="VALUE" value="{$VALUE}" />
                 <input type="hidden" name="TRACK" value="{$TRACK}" /> 
                  <input type="hidden" name="CARRIER" value="{$CARRIER}" />                      
                <button type="submit" id="submit_sms" class="btn btn-primary pull-right" name="sendsms"><i class="process-icon-mail-reply"></i> {l s='Send' mod='smsapi'}</button>
                 </div>
                </form>
     </div></div>
     </fieldset>
</div>
