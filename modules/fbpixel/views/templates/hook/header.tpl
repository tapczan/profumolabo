{*
* PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
*
* @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
* @copyright 2010-2021 VEKIA
* @license   This program is not free software and you can't resell and redistribute it
*
* CONTACT WITH DEVELOPER http://mypresta.eu
* support@mypresta.eu
*}
{if Tools::getValue('ajax','false') == 'false' && Tools::getValue('add','false') == 'false' && Tools::getValue('action') != 'productrefresh' && (Tools::getValue('submitMessage','false') == 'false' && Tools::getValue('msgText','false') == 'false') && (Tools::getValue('add_comment','false')=='false' && Tools::getValue('action') != 'add_comment') && Tools::getValue('criterion','false') == 'false' && Tools::getValue('controller')!='productscomparison'}
{literal}
    <script>
        var prefix = '{/literal}{$prefix|escape:quotes}{literal}';
        var sufix = '{/literal}{$sufix|escape:quotes}{literal}';

        function getURLParameter(url, name) {
            return (RegExp(name + '=' + '(.+?)(&|$)').exec(url) || [, null])[1];
        }

        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        {/literal}
        {foreach $fbpixel_id_array as $pixel_id}
        fbq('init', '{$pixel_id}');
        {/foreach}

        {if Configuration::get('FBPIXEL_PAGEVIEW')==1}
        fbq('track', "PageView");
        {/if}

        {if Configuration::get('FBPIXEL_LEAD')==1}
        {if Tools::getValue('controller')=='product'}
        {if Tools::getValue('id_product','false')!='false'}
        fbq('track', 'Lead');
        {/if}
        {/if}
        {/if}

        {if Configuration::get('FBPIXEL_LEAD_N') == 1 && $track_newsletter == true}
        fbq('track', 'Lead', {
            content_name: 'newsletter subscription'
        });
        {/if}

        {if Configuration::get('FBPIXEL_REG')==1}
        {if isset($account_created)}
        fbq('track', "CompleteRegistration");
        {/if}
        {/if}

        {if Configuration::get('FBPIXEL_INITIATE')==1}
        {if Tools::getValue('controller')=='order' || Tools::getValue('controller')=='orderopc'}
        {if Configuration::get('FBPIXEL_INITIATE_D')==1}
        {literal}fbq('track', 'InitiateCheckout', {value: '{/literal}{if $FBPIXEL_CONCURR}{Tools::convertPriceFull($order_total_paid, $currency_from, $currency_to)}{else}{$order_total_paid}{/if}{literal}', content_type: 'product', content_ids: [{/literal}{$content_ids nofilter}{literal}], currency: '{/literal}{if $FBPIXEL_CONCURR}{$currency_to->iso_code}{else}{$order_currency_iso_code}{/if}{literal}'});{/literal}
        {else}
        fbq('track', 'InitiateCheckout');
        {/if}
        {/if}
        {/if}
        {if Configuration::get('FBPIXEL_SEARCH')==1}
        {if Tools::getValue('controller')=='search'}
        fbq('track', 'Search' {if Tools::getValue('s', 'false') != 'false'}, {literal}{{/literal}search_string: '{Tools::getValue('s')|trim}'{literal}}{/literal}{else}{if Tools::getValue('search_query', 'false') != 'false'}, {literal}{{/literal}search_string: '{Tools::getValue('search_query')|trim}'{literal}}{/literal}{/if}{/if});
        {/if}
        {/if}

        {if Configuration::get('FBPIXEL_WISHLIST')==1}
        document.addEventListener("DOMContentLoaded", function (event) {
            $('#wishlist_button_nopop').click(function () {
                fbq('track', 'AddToWishlist');
            });
        });
        {/if}

        {* ADD TO CART + DPA *}
        {* ADD TO CART + DPA *}
        {* ADD TO CART + DPA *}

        {if Configuration::get('FBPIXEL_DPA')==1}
            {if Configuration::get('FBPIXEL_VCONTENT') == 1}
                {if Tools::getValue('id_product','false') != 'false' && $fbpixel_product != NULL}
                {assign var=product_price value=$fbpixel_product->getPrice(true, $smarty.const.NULL)}
                document.addEventListener("DOMContentLoaded", function (event) {
                    reinitViewContent();
                });


                function reinitViewContent(){
                    var json = ($('#product-details').length != 0 ? $.parseJSON($('#product-details').attr('data-product')):$.parseJSON($('#product-details-fbpixel').attr('data-product')));
                    {if $fbpixel_product->hasAttributes() > 0}
                    var ids = prefix + {if Configuration::get('FBPIXEL_ATTRID') == 1}json['id_product_attribute']{elseif Configuration::get('FBPIXEL_ATTRID') == 2}{Tools::getValue('id_product')}+ '{Configuration::get('FBPIXEL_SEPSIGN','-')}' + json['id_product_attribute']{else}{Tools::getValue('id_product')}{/if}+ sufix;
                    {else}
                    var ids = prefix+{Tools::getValue('id_product')}+sufix;
                    {/if}
                    var productPagePrice = $('body').find('{Configuration::get('FBPIXEL_ATC_PPP')}').html();
                    if (productPagePrice == undefined) {
                        var productPagePrice = 0.000;
                    }

                    {if Configuration::get('FBPIXEL_PREPRICE') == 1}

                        productPagePrice = productPagePrice.replace(/[^\d.\,]/g, '');
                        productPagePrice = productPagePrice.replace(',','');
                    {else}
                        productPagePrice = productPagePrice.replace(/[^\d.\,]/g, '');
                        productPagePrice = productPagePrice.replace(',', '.');
                    {/if}


                    if (productPagePrice[productPagePrice.length - 1] === ".") {
                        productPagePrice = productPagePrice.slice(0, -1);
                    }
                    fbq('track', 'ViewContent', {
                        content_name: '{$fbpixel_product->name|escape:javascript}',
                        content_ids: [ids],
                        content_type: 'product',
                        value: productPagePrice,
                        currency: '{$fbpixel_currency}'
                    });
                }

                {/if}
            {/if}

            function reinitaddtocart() {
                {if Configuration::get('FBPIXEL_ADDTOCART')==1}
                    {if Tools::getValue('controller')=='product'}
                        var json = ($('#product-details').length != 0 ? $.parseJSON($('#product-details').attr('data-product')):$.parseJSON($('#product-details-fbpixel').attr('data-product')));
                        $('{Configuration::get('FBPIXEL_ATC_B')}').click(function () {
                            var productPagePrice = $(this).parents('body').find('{Configuration::get('FBPIXEL_ATC_PPP')}').html();
                            if (productPagePrice == undefined) {
                                var productPagePrice = 0.000;
                            }
                            productPagePrice = productPagePrice.replace(/[^\d.\,]/g, '');
                            productPagePrice = productPagePrice.replace(',', '.');
                            if (productPagePrice[productPagePrice.length - 1] === ".") {
                                productPagePrice = productPagePrice.slice(0, -1);
                            }
                            {if $FBPIXEL_CONCURR}
                                recalculatePrice(productPagePrice, {$currency_from->id}, {$currency_to->id}, {literal}prefix + {/literal}{if Configuration::get('FBPIXEL_ATTRID') == 1}json['id_product_attribute']{elseif Configuration::get('FBPIXEL_ATTRID') == 2}{Tools::getValue('id_product')}+ '{Configuration::get('FBPIXEL_SEPSIGN','-')}' + json['id_product_attribute']{else}{Tools::getValue('id_product')}{/if}{literal}+ sufix{/literal}, 'product', '{$fbpixel_currency}');
                            {else}
                                {if $fbpixel_product->hasAttributes() > 0}
                                    {literal}fbq('track', 'AddToCart', {content_ids: prefix + {/literal}{if Configuration::get('FBPIXEL_ATTRID') == 1}json['id_product_attribute']{elseif Configuration::get('FBPIXEL_ATTRID') == 2}{Tools::getValue('id_product')}+ '{Configuration::get('FBPIXEL_SEPSIGN','-')}' + json['id_product_attribute']{else}{Tools::getValue('id_product')}{/if}{literal}+ sufix, content_type: 'product', value: productPagePrice, currency: '{/literal}{$fbpixel_currency}{literal}'});{/literal}
                                {else}
                                    {literal}fbq('track', 'AddToCart', {content_ids: prefix+{/literal}{Tools::getValue('id_product')}{literal}+sufix, content_type: 'product', value: productPagePrice, currency: '{/literal}{$fbpixel_currency}{literal}'});{/literal}
                                {/if}
                            {/if}
                        });
                    {/if}
                {/if}
            }

            document.addEventListener("DOMContentLoaded", function (event) {
                reinitaddtocart();
            });
        {else}
            {if Configuration::get('FBPIXEL_VCONTENT') == 1}
                {if Tools::getValue('id_product','false') != 'false' && $fbpixel_product != NULL}
                    {assign var=product_price value=$fbpixel_product->getPrice(true, $smarty.const.NULL)}
                    document.addEventListener("DOMContentLoaded", function (event) {
                        var json = ($('#product-details').length != 0 ? $.parseJSON($('#product-details').attr('data-product')):$.parseJSON($('#product-details-fbpixel').attr('data-product')));
                        {if $fbpixel_product->hasAttributes() > 0}
                        var ids = prefix + {if Configuration::get('FBPIXEL_ATTRID') == 1}json['id_product_attribute']{elseif Configuration::get('FBPIXEL_ATTRID') == 2}{Tools::getValue('id_product')}+ '{Configuration::get('FBPIXEL_SEPSIGN','-')}' + json['id_product_attribute']{else}{Tools::getValue('id_product')}{/if}+ sufix;
                        {else}
                        var ids = prefix+{Tools::getValue('id_product')}+
                        sufix;
                        {/if}

                        fbq('track', 'ViewContent', {
                            content_name: '{$fbpixel_product->name|escape:javascript}',
                            value: {if $FBPIXEL_CONCURR}{Tools::convertPriceFull($product_price|string_format:"%.2f", $currency_from, $currency_to)}{else}{$product_price|string_format:"%.2f"}{/if},
                            currency: '{$fbpixel_currency}'
                        });
                    });
                {/if}
            {/if}
            function reinitaddtocart() {
                {if Configuration::get('FBPIXEL_ADDTOCART')==1}
                $('.ajax_add_to_cart_button, {Configuration::get('FBPIXEL_ATC_B')}').click(function () {
                    fbq('track', 'AddToCart');
                });
                {/if}
            }

            document.addEventListener("DOMContentLoaded", function (event) {
                reinitaddtocart();
            });

            function reinitViewContent(){
            }
        {/if}
        {literal}
    </script>
{/literal}
{/if}
