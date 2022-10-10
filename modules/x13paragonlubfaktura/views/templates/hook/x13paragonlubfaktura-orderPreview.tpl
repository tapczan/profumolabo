<div class="x13paragonlubfaktura-orderPreview" style="padding-left: 75px;">
	<div class="row">
		{l s='Proof of purchase' mod='x13paragonlubfaktura'}:&nbsp;
		{if $recieptorinvoice}
			<img src="{$x13recieptorinvoice_path}/img/{$recieptorinvoice}.png" alt="{$recieptorinvoice}">&nbsp;<strong>{$recieptorinvoice_translate}</strong>
		{else}
			<strong>{l s='unknown' mod='x13paragonlubfaktura'}</strong>
		{/if}
	</div>
</div>