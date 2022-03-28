<div class="panel">
    <h3>Image converters</h3>
    List of supported image converters with status on your server.
    You must enable at least one converter.
    <br><br>
    <ul>
        {foreach from=$converters item='converter'}
            {if $converter['enabled']}
                <li style="color: green">
                    {$converter['name']} - enabled
                </li>
            {else}
                <li style="color: gray">
                    {$converter['name']} - disabled
                </li>
            {/if}
        {/foreach}
    </ul>
</div>