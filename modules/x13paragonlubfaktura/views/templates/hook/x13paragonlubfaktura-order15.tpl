<br>
<fieldset>
	<legend>
		<img src="{$x13recieptorinvoice_path}/logo.gif"></img>
		{l s='Proof of purchase' mod='x13paragonlubfaktura'}
	</legend>
	<ul>
		<li>
			{if $recieptorinvoice}
				<img src="{$x13recieptorinvoice_path}/img/{$recieptorinvoice}.png" alt="{$recieptorinvoice}">
				<strong>{$recieptorinvoice_translate}</strong>
			{else}
				<strong>{l s='unknown' mod='x13paragonlubfaktura'}</strong>
			{/if}
			<hr>
			<p><b>{l s='Change document type' mod='x13paragonlubfaktura'}</b></p>
			<form action="#" method="post" class="std">
				<p class="text">
					<select name="x13paragonlubfaktura" class="form-control">
						{foreach $choices as $documentName key=documentType}
						<option value="{$documentType}" {if $documentType == $recieptorinvoice}selected{/if}>{$documentName}</option>
						{/foreach}
					</select>
				</p>
				<p class="text">
					<button type="submit" name="submitX13documentType" class="button">{l s='Change' mod='x13paragonlubfaktura'}</button>
				</p>
			</form>
		</li>
	</ul>
</fieldset>
