<div id="order-detail-content" class="table_block">
	<table class="std">
		<tr><th class="first_item">{l s='Proof of purchase' mod='x13paragonlubfaktura'}:</th></tr>
		{if $recieptorinvoice}
			<tr><td><img src="{$x13recieptorinvoice_path}/img/{$recieptorinvoice}.png" alt="{$recieptorinvoice}"> {$recieptorinvoice_translate}</td></tr>
		{else}
			<tr><td>{l s='unknown' mod='x13paragonlubfaktura'}</td></tr>
		{/if}
	</table>
</div>
