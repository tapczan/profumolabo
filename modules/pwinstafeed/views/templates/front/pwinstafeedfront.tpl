{*=============================================================================*
**  PW Instagram feed
**  ---
**  PRESTAWORKS AB (www.prestaworks.se)
**=============================================================================*}
{extends file='page.tpl'}

{block name="page_content"}

<section id="pwip" class="pwip" data-style="{$pwinstafeed_page->pwinstafeed_pagestyle}" data-spacing="{$pwinstafeed_page->pwinstafeed_pagespacing}">
    {if $pwinstafeed_page->pwinstafeed_pagetitle != '' || $pwinstafeed_page->pwinstafeed_pagecontent != ''}
    <div class="pwip__content">
        {if $pwinstafeed_page->pwinstafeed_pagetitle != ''}<h1 class="page-heading">{$pwinstafeed_page->pwinstafeed_pagetitle}</h1>{/if}
        {if $pwinstafeed_page->pwinstafeed_pagecontent != ''}{$pwinstafeed_page->pwinstafeed_pagecontent nofilter}{/if}
    </div>
    {/if}
    {if $pwi_accesstoken == ''}
    <div class="alert alert-info">
        {l s='Please make sure you have entered a valid access token.' mod='pwinstafeed'}
    </div>
    {else}
    <div id="pwip__feed" class="pwip__feed">

    </div>
    <button id="pwip__loadmore" class="pwip__loadmore" style="background-color: {$pwinstafeed_page->pwinstafeed_pagebtnbgcolor}; color: {$pwinstafeed_page->pwinstafeed_pagebtnfgcolor};" onclick="pwipRun(pwip_url); return false;">
        {l s='Load more' mod='pwinstafeed'}
    </button>
    {/if}
</section>
{/block}
