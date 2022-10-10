{$x13recieptorinvoice_style nofilter}

{if $x13recieptorinvoice_text}
<div class="x13recieptorinvoice-info alert alert-info">
{$x13recieptorinvoice_text nofilter}
</div>
{/if}

 <div class="x13recieptorinvoice clearfix" id="x13recieptorinvoice_wrapper">
	<strong>{l s='Select proof of purchase:' mod='x13paragonlubfaktura'}</strong>

	<span id="x13recieptorinvoice-invoice"{if $is_step != '1' && (!$recieptorinvoice_personal && !$customer_invoice_data)} style="opacity:0.4;"{/if}>
		&nbsp;
		&nbsp;
		<input type="radio" name="recieptorinvoice" value="invoice" id="recieptorinvoice_invoice"{if $recieptorinvoice_default == "invoice"} checked="checked"{/if}{if $is_step != '1' && (!$recieptorinvoice_personal && !$customer_invoice_data)} disabled="disabled"{/if}>
		<label for="recieptorinvoice_invoice">
			{if $recieptorinvoice_icons}
				<img src="{$x13recieptorinvoice_iconsPath}invoice{$x13recieptorinvoice_iconFormat}" alt="{l s="invoice" mod='x13paragonlubfaktura'}">
			{/if}
			{l s="invoice" mod='x13paragonlubfaktura'}
		</label>
	</span>
	<span id="x13recieptorinvoice-reciept">
		&nbsp;
		&nbsp;
		<input type="radio" name="recieptorinvoice" value="reciept" id="recieptorinvoice_reciept"{if $recieptorinvoice_default == "reciept" || ($is_step != '1' && (!$recieptorinvoice_personal && !$customer_invoice_data) && $recieptorinvoice_default != 'none')} checked="checked"{/if}>
		<label for="recieptorinvoice_reciept">
			{if $recieptorinvoice_icons}
				<img src="{$x13recieptorinvoice_iconsPath}reciept{$x13recieptorinvoice_iconFormat}" alt="{l s="receipt" mod='x13paragonlubfaktura'}">
			{/if}
			{l s="receipt" mod='x13paragonlubfaktura'}
		</label>
	</span>
</div>

{if $no_default_choice && $recieptorinvoice_default == 'none'}
<div id="x13noDocumentWarning" class="x13recieptorinvoice-error">
	{$no_document_warning}
</div>
{/if}

{if !$recieptorinvoice_personal}
	{if $is17}
		<div class="x13recieptorinvoice-error" {if $customer_invoice_data} style="display:none;"{/if}>
		{l s='Invoice is available only if VAT number is filled.' mod='x13paragonlubfaktura'}
		
		<a href="{$editAddressUrl}">
			{l s='Edit address.' mod='x13paragonlubfaktura'}
		</a>
		</div>
	{else}
		<div class="x13recieptorinvoice-error" {if $is_step != '1' && (!$recieptorinvoice_personal && $customer_invoice_data)} style="display:none;"{/if}>
			{l s='Invoice is available only if VAT number is filled.' mod='x13paragonlubfaktura'}
		</div>
	{/if}
{/if}

<script type="text/javascript">
	var x13defaultNoDocument = {if $no_default_choice}true{else}false{/if};
	var x13noDocumentWarning = '{$no_document_warning}';
	var x13currentChoice = '{$recieptorinvoice_default}';
	var x13iorHook = '{$roi_hook}';
	var roi_id_cart = {$roi_id_cart};
	{if $is_step == '1' || $is_step == 'no'}
	var config_personal = {if $recieptorinvoice_personal}true{else}false{/if};
	{/if}
	var x13is16opc = {if $recieptorinvoice_16opc}true{else}false{/if};
</script>
