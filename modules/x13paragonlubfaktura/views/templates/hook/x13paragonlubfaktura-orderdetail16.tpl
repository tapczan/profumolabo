<table class="table table-bordered">
{if $recieptorinvoice}
	<tr><th style="width:200px;color:#333;">{l s='Proof of purchase' mod='x13paragonlubfaktura'}:</th><td><img src="{$x13recieptorinvoice_path}/img/{$recieptorinvoice}.png" alt="{$recieptorinvoice}"> {$recieptorinvoice_translate}</td></tr>
{else}
	<tr><th style="width:200px;color:#333;" width=200>{l s='Proof of purchase' mod='x13paragonlubfaktura'}:</th><td>{l s='unknown' mod='x13paragonlubfaktura'}</td></tr>
{/if}
</table>
