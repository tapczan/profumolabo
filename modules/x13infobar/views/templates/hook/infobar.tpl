<div
  class="x13-counter-container x13-counter-container-1 counter-{$infobar.displayStyle} x13-counter-theme-{$infobar.counterTheme} {if !$infobar.mobile}x13-counter-hide-mobile{/if} {if $infobar.animate_bg}x13-counter-container-animate{/if} {if $infobar.closeable}x13-counter-closeable{/if}"
  id="x13-counter-container"
  data-theme="{$infobar.counterTheme}"
  data-text-sec="{$infobar.seconds|default:false}"
  data-text-min="{$infobar.minutes|default:false}"
  data-text-hour="{$infobar.hours|default:false}"
  data-text-days="{$infobar.days|default:false}"
  data-section-interval="{$infobar.section_interval|default:0}"
>
  <div class="container">
    <div class="{if $infobar.multitext}counter-multiple-text{else}counter-text{/if}">
      {if $infobar.multitext}
        {foreach $infobar.text as $section}
        <div class="counter-text">
          {$section nofilter}
        </div>
        {/foreach}
      {else}
        {$infobar.text nofilter}
      {/if}
      {if $infobar.closeable}
      <a
        href="#"
        data-url="{Context::getContext()->link->getModuleLink('x13infobar', 'ajax')}"
        data-infobar="{$infobar.id_information_bar}"
        data-token="{Tools::getToken(false)}"
        class="counter-close-btn"
      >
      {if $is17}<i class="material-icons">close</i>{else}<i class="icon icon-times"></i>{/if}
      </a>
      {/if}
    </div>
  </div>
</div>
<script data-keepinline="true">
var x13InfoBar_displayStyle = '{$infobar.displayStyle}'; // fixedTop, fixedBottom
var x13InfoBar_afterEnd = {$infobar.after_end};
var x13InfoBar_dateTo = '{$infobar.date_to}';
var x13InfoBar_counterTheme = '{$infobar.counterTheme}';
</script>
<style type="text/css">
{$infobar.css nofilter}
{if $infobar.custom_css}{strip}{$infobar.custom_css nofilter}{/strip}{/if}
</style>
