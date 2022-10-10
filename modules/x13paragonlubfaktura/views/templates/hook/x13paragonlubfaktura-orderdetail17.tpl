<div class="box">
{if $recieptorinvoice}
	{l s='Proof of purchase' mod='x13paragonlubfaktura'}: <img src="{$x13recieptorinvoice_path}/img/{$recieptorinvoice}.png" alt="{$recieptorinvoice}"> {$recieptorinvoice_translate}
{else}
	{l s='Proof of purchase' mod='x13paragonlubfaktura'}: {l s='unknown' mod='x13paragonlubfaktura'}
{/if}
</div>
