{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

{$jsAssetHash = Configuration::get('PS_CCCJS_VERSION')|md5}

{foreach $javascript.external as $js}
  <script
    type="text/javascript"
    src="{$js.uri}?v={$jsAssetHash}"
    {$js.attribute}></script>
{/foreach}


{foreach $javascript.inline as $js}
  <script type="text/javascript">
    {$js.content nofilter}
  </script>
{/foreach}

{if isset($vars) && $vars|@count}
  <script type="text/javascript">
    {foreach from=$vars key=var_name item=var_value}
    var {$var_name} = {$var_value|json_encode nofilter};
    {/foreach}
  </script>
{/if}

{literal}
  <!-- Facebook Pixel Code -->
  <script>
    if(typeof fbq === 'undefined') {
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window,document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      
      fbq('init', '3084462355150264');
      fbq('track', 'PageView');
    }
    else {
      fbq('track', 'PageView');
    }
  </script>
  <noscript>
    <img height="1" width="1"
    src="https://www.facebook.com/tr?id=3084462355150264&ev=PageView
    &noscript=1"/>
  </noscript>
  <!-- End Facebook Pixel Code -->
{/literal}

{literal}
  <script>
    var chatbox = document.getElementById("fb-customer-chat");

    if(chatbox){
      chatbox.setAttribute("page_id", "110559737836074");
      chatbox.setAttribute("attribution", "biz_inbox");
    }
  </script>
{/literal}