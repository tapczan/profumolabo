{if $ancient16}
<script>
	jQuery(document).ready(function($) {
		$('#x13document').prependTo('#tabOrder');
	});
</script>
{/if}
{if isset($admin_order)}
<div class="row" id="x13document">
	<div class="{if $ancient16}col-lg-12{else}col-lg-5{/if} pull-right">
		<div class="{if $isModernLayout}card{else}panel{/if}">
			<div class="{if $isModernLayout}card-header{else}panel-heading{/if}">
				<i class="icon-inbox"></i>{l s='Proof of purchase' mod='x13paragonlubfaktura'}
			</div>
			{if $isModernLayout}<div class="card-body">{/if}
			{if $recieptorinvoice}
				<img src="{$x13recieptorinvoice_path}/img/{$recieptorinvoice}.png" alt="{$recieptorinvoice}"> <strong>{$recieptorinvoice_translate}</strong>
			{else}
				<strong>{l s='unknown' mod='x13paragonlubfaktura'}</strong>
			{/if}
			<hr>
			<p><b>{l s='Change document type' mod='x13paragonlubfaktura'}</b></p>
			<form action="#" method="post">
				<div class="form-group">
					<select name="x13paragonlubfaktura" class="form-control">
						{foreach $choices as $documentName key=documentType}
						<option value="{$documentType}" {if $documentType == $recieptorinvoice}selected{/if}>{$documentName}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<button type="submit" name="submitX13documentType" class="btn btn-default">{l s='Change' mod='x13paragonlubfaktura'}</button>
				</div>
			</form>
			{if $isModernLayout}</div><!-- .card-body -->{/if}
		</div>
	</div>
</div>
{else}
<div style="padding:10px; margin-bottom:6px;background:#EEE; border:1px solid #DDD;">
	{if $recieptorinvoice}
		<img src="{$x13recieptorinvoice_path}/img/{$recieptorinvoice}.png" alt="{$recieptorinvoice}"> &nbsp; {l s='Proof of purchase' mod='x13paragonlubfaktura'}: <strong>{$recieptorinvoice_translate}</strong>
	{else}
		{l s='Proof of purchase' mod='x13paragonlubfaktura'}: <strong>{l s='unknown' mod='x13paragonlubfaktura'}</strong>
	{/if}
</div>
{/if}
