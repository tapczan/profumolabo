{*=============================================================================*
**  PW Instagram feed
**  ---
**  PRESTAWORKS AB (www.prestaworks.se)
**=============================================================================*}

<section id="pwinstafeed" class="{if $pwi_hook == 1}footer-block col-xs-12{elseif $pwi_hook == 2}no-padding{elseif $pwi_hook == 3 || $pwi_hook == 4}block{/if}" data-spacing="{$pwi_spacing}" data-style="{$pwi_style}">
    {if $pwi_hook == 1 || $pwi_hook == 2}
    <h4 {if $pwi_hook == 2}class="page-subheading"{/if}>{l s='Follow us on Instagram' mod='pwinstafeed'}</h4>
    {else}
    <p class="title_block">{l s='Follow us on Instagram' mod='pwinstafeed'}</p>
    {/if}
    <div id="pwi__feed" class="pwi__feed">

    </div>
</section>
