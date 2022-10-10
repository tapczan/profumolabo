{*2007-2021 PrestaShop*}

{*NOTICE OF LICENSE*}

{*This source file is subject to the Academic Free License (AFL 3.0)*}
{*that is bundled with this package in the file LICENSE.txt.*}
{*It is also available through the world-wide-web at this URL:*}
{*http://opensource.org/licenses/afl-3.0.php*}
{*If you did not receive a copy of the license and are unable to*}
{*obtain it through the world-wide-web, please send an email*}
{*to license@prestashop.com so we can send you a copy immediately.*}

{*DISCLAIMER*}

{*Do not edit or add to this file if you wish to upgrade PrestaShop to newer*}
{*versions in the future. If you wish to customize PrestaShop for your*}
{*needs please refer to http://www.prestashop.com for more information.*}

{*@author    PrestaShop SA <contact@prestashop.com>*}
{*@copyright 2007-2021 PrestaShop SA*}
{*@license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)*}
{*International Registered Trademark & Property of PrestaShop SA*}

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<script src="https://sf16-scmcdn-va.ibytedtos.com/obj/static-us/ads/ecommerce-demo/tbp_external_platform-v2.2.0.js"></script>
<div id="root"></div>
<script type="application/javascript">
    async function sleep(seconds) {
        return new Promise(resolve => {
            setTimeout(resolve, seconds * 1000);
        })
    }

    async function checkConnectionStatus() {
        return {
            isConnected: "{$is_connected|escape:'javascript':'UTF-8'}",// when accessToken exists
            externalData: {
                business_platform: "{$business_platform|escape:'javascript':'UTF-8'}",
                external_business_id: "{$external_business_id|escape:'javascript':'UTF-8'}"
            },
        };
    }

    async function loadProfile() {
        return {
            bcId: "{$bc_id|escape:'javascript':'UTF-8'}",
            bc: {
                id: "{$bc_id|escape:'javascript':'UTF-8'}",
                name: ""
            },
            advId: "{$advertiser_id|escape:'javascript':'UTF-8'}",
            advertiser: {
                id: "{$advertiser_id|escape:'javascript':'UTF-8'}",
                name: "{$store_name|escape:'javascript':'UTF-8'}"
            },
            pixelCode: "{$pixel_code|escape:'javascript':'UTF-8'}",
            pixel: {
                pixelCode: "{$pixel_code|escape:'javascript':'UTF-8'}",
                pixelName: "",
                // advancedMatching is true if both fields below are true and vice versa,
                // partial true/false cases should be deemed as dirty data.
                advancedMatchingFields: {
                    email: "{$advanced_matching|escape:'javascript':'UTF-8'}",
                    phoneNumber: "{$advanced_matching|escape:'javascript':'UTF-8'}",
                },
            },
            catalogId: "{$catalog_id|escape:'javascript':'UTF-8'}",
        };
    }

    async function loadCatalog() {
        return {
            approved: "{$approved|escape:'javascript':'UTF-8'}",
            processing: "{$processing|escape:'javascript':'UTF-8'}",
            rejected: "{$rejected|escape:'javascript':'UTF-8'}",
        }
    }


    let external_data = "{$external_data|escape:'javascript':'UTF-8'}";
    window.external_data = external_data
    tbp.render({
        baseName: "{$request_uri|escape:'javascript':'UTF-8'}",
        checkConnectionStatus,
        loadProfile,
        loadCatalog,
    });
</script>
</body>
</html>