{if $pwaccesstoken == ''}
    <strong class="settings-header">{l s='Generate Access Token' mod='pwinstafeed'}</strong></p>
    {l s='You\'re almost done, you just need to generate an access token via the link below.' mod='pwinstafeed'}<br><br>
    <a class="btn btn-default btn-lg btn-primary" href="http://demo.prestaworks.com/modules/pwinstafeed/" target="_blank">
        {l s='Generate an access token' mod='pwinstafeed'}
    </a>
{else}
        <strong class="settings-header">{l s='Instagram setup' mod='pwinstafeed'}</strong></p>
{/if}

