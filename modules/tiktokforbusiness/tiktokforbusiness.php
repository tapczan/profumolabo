<?php
/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2021 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Tiktokforbusiness extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tiktokforbusiness';
        $this->tab = 'social_networks';
        $this->version = '1.0.3';
        $this->author = 'TikTok';
        $this->need_instance = 0;
        $this->module_key = 'aa3d9aaba20ba0670a0ddb931ebe5952';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TikTok for Business');
        $this->description = $this->l('The TikTok module provides merchants with an easy to set up solution that unlocks TikTok’s innovative social commerce features. Merchants can seamlessly sync their product catalog, customize Pixel event tracking, and unlock paid advertising and organic visibility from a single location.');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayOrderConfirmation') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('displayCheckoutSummaryTop') &&
            $this->registerHook('displayProductExtraContent') &&
            $this->registerHook('displayShoppingCartFooter');
    }

    public function uninstall()
    {
        $curl = curl_init();
        $url = "https://business-api.tiktok.com/open_api/v1.2/tbp/business_profile/disconnect/";
        $access_token = Configuration::get('tt4b_access_token');
        $headers = array(
            "Content-Type:application/json",
            "Access-Token:$access_token"
        );
        $external_business_id = Configuration::get('tt4b_external_business_id');
        $app_id = Configuration::get('tt4b_app_id');
        $params = [
            'external_business_id' => $external_business_id,
            'business_platform' => 'PRESTA_SHOP',
            'is_setup_page' => 0,
            'app_id' => $app_id
        ];
        $data = json_encode($params);
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
        );
        curl_setopt_array($curl, $optArray);
        curl_exec($curl);

        //delete tiktok credentials
        Configuration::deleteByName('tt4b_app_id');
        Configuration::deleteByName('tt4b_secret');
        Configuration::deleteByName('tt4b_access_token');
        Configuration::deleteByName('tt4b_external_data_key');
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $external_business_id = Configuration::get('tt4b_external_business_id');
        if ($external_business_id == false) {
            $external_business_id = uniqid($prefix = 'tt4b_prestashop');
            Configuration::updateValue('tt4b_external_business_id', $external_business_id);
        }
        $ssl = Configuration::get('PS_SSL_ENABLED');
        if ($ssl !== "1") {
            return $this->displayWarning("SSL is required for this module. Please enable SSL before proceeding");
        }

        //get shop information: (name, domain, and url)
        $shop = Context::getContext()->shop;
        $shop_name = $shop->name;
        $shop_domain = 'https://' . Context::getContext()->shop->domain_ssl;
        $shop_url = $shop_domain . $shop->getBaseURI();
        $module_url = 'https://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $request_uri = $_SERVER['REQUEST_URI'];
        Configuration::updateValue('tt4b_module_url', $module_url);


        //get eligibility information
        $orders_last_60 = 0;
        $orders_last_120 = 0;
	    $orders_last_365 = 0;
        $total_orders = 0;
        $gmv_last_60 = 0;
        $gmv_last_120 = 0;
        $gmv_last_365 = 0;
        $total_gmv = 0;
        $tenure = 0;
        $d60 = strtotime(date('c', strtotime('-60 days')));
        $d120 = strtotime(date('c', strtotime('-120 days')));
        $d365 = strtotime(date('c', strtotime('-365 days')));

        $orders = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'orders` WHERE `current_state`=5 ORDER BY `delivery_date` ASC');
        if (count($orders) > 0) {
            $oldest_order = $orders[0];
            $oldest_delivery_date = strtotime($oldest_order['delivery_date']);
            $tenure = (int) ( ( time() - $oldest_delivery_date ) / 86400 );
        } else {
	        PrestaShopLogger::addLog('no orders retrieved from DB for eligibility collection', 1, null, __CLASS__, 40);
        }

        foreach ($orders as $order) {
            $delivery_date = $order['delivery_date'];
            $total_paid = $order['total_paid'];
            $date_unix = strtotime($delivery_date);
            if ($date_unix > $d60) {
                $orders_last_60 += 1;
                $gmv_last_60 += $total_paid;
            }
            if ($date_unix > $d120) {
                $orders_last_120 += 1;
                $gmv_last_120 += $total_paid;
            }
            if ($date_unix > $d365) {
                $orders_last_365 += 1;
                $gmv_last_365 += $total_paid;
            }
            $total_orders += 1;
            $total_gmv += $total_paid;
        }

        $net_gmv         = [
	        [
		        'period' =>   60,
		        'interval' => 'DAYS',
		        'min'      => intval($gmv_last_60),
		        'max'      => intval($gmv_last_60),
		        'unit'     => 'CURRENCY',
	        ],
	        [
		        'period' =>   120,
		        'interval' => 'DAYS',
		        'min'      => intval($gmv_last_120),
		        'max'      => intval($gmv_last_120),
		        'unit'     => 'CURRENCY',
	        ],
	        [
		        'period' =>   365,
		        'interval' => 'DAYS',
		        'min'      => intval($gmv_last_365),
		        'max'      => intval($gmv_last_365),
		        'unit'     => 'CURRENCY',
	        ],
	        [
		        'interval' => 'LIFETIME',
		        'min'      => intval($total_gmv),
		        'max'      => intval($total_gmv),
		        'unit'     => 'CURRENCY',
	        ]
        ];

        $net_order_count = [
	        [
		        'period' =>   60,
		        'interval' => 'DAYS',
		        'min'      => $orders_last_60,
		        'max'      => $orders_last_60,
		        'unit'     => 'COUNT',
	        ],
	        [
		        'period' =>   120,
		        'interval' => 'DAYS',
		        'min'      => $orders_last_120,
		        'max'      => $orders_last_120,
		        'unit'     => 'COUNT',
	        ],
	        [
		        'period' =>   365,
		        'interval' => 'DAYS',
		        'min'      => $orders_last_365,
		        'max'      => $orders_last_365,
		        'unit'     => 'COUNT',
	        ],
	        [
		        'interval' => 'LIFETIME',
		        'min'      => $total_orders,
		        'max'      => $total_orders,
		        'unit'     => 'COUNT',
	        ],
        ];

        $tenure = [
	        'min'  => $tenure,
	        'unit' => 'DAYS',
        ];

        //generate merchant app_id, secret, and external_data_key if not already generated
        $app_id = Configuration::get('tt4b_app_id');
        $secret = Configuration::get('tt4b_secret');
        $external_data_key = Configuration::get('tt4b_external_data_key');
        if ($app_id == false || $secret == false || $external_data_key == false) {
            $open_source_app_raw_rsp = $this->createOpenSourceApp($external_business_id, $shop_name, $shop_url);
            $open_source_app_rsp = json_decode($open_source_app_raw_rsp, true);
            $app_id = $open_source_app_rsp["data"]["app_id"];
            $secret = $open_source_app_rsp["data"]["app_secret"];
            $external_data_key = $open_source_app_rsp["data"]["external_data_key"];
            $redirect_uri = $open_source_app_rsp["data"]["redirect_uri"];
            if (is_null($app_id) || is_null($secret) || is_null($redirect_uri)) {
                return $this->displayWarning("An error occurred generating TT credentials. Please reach out to support");
            }

            Configuration::updateValue('tt4b_secret', $secret);
            Configuration::updateValue('tt4b_app_id', $app_id);
            Configuration::updateValue('tt4b_external_data_key', $external_data_key);
        }

        //get business location: (locale, email, phone, country, currency)
        $locale = $this->context->language->iso_code;
        $email = Configuration::get('PS_SHOP_EMAIL');
        //phone likely not set, but not required for external_data
        $phone = Configuration::get('PS_SHOP_PHONE');
        $country_iso = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
        $currency = Currency::getDefaultCurrency()->iso_code;
        $industry_id = '291408';
        $timezone = Configuration::get('PS_TIMEZONE');
        $target_time_zone = new DateTimeZone($timezone);
        $date_time = new DateTime('now', $target_time_zone);
        $gmtFormattedTimezone = 'GMT'.$date_time->format('P');

        //get information to encode hmac
        $now = new DateTime();
        $timestamp = (string)($now->getTimestamp()*1000);
        $version = '1.5';
        $business_platform = 'PRESTA_SHOP';
        $hmacStr = 'version='.$version.'&timestamp='.$timestamp.'&locale='.$locale.
            '&business_platform='.$business_platform.'&external_business_id='.$external_business_id;
        $hmac = hash_hmac('sha256', $hmacStr, $external_data_key);
        Configuration::updateValue('tt4b_external_business_id', $external_business_id);

        //pass in parameters to external_data to render the plugin
        $obj = array(
            "external_business_id" => $external_business_id,
            "business_platform" => $business_platform,
            "locale" => $locale,
            "version" => $version,
            "timestamp" => $timestamp,
            "timezone" => $gmtFormattedTimezone,
            "country_region" => $country_iso,
            "phone_number" => $phone,
            "email" => $email,
            "industry_id" => $industry_id,
            "store_name" => $shop_name,
            "currency" => $currency,
            "website_url" => $shop_url,
            "domain" => $shop_domain,
            "app_id" => $app_id,
            "redirect_uri" => $shop_url,
            "hmac" => $hmac,
            "close_method" => "redirect_inside_tiktok",
            'is_email_verified'    => true,
            'is_verified'          => true,
            'net_gmv'              => $net_gmv,
            'net_order_count'      => $net_order_count,
            'tenure'               => $tenure,
        );
        $external_data = base64_encode(json_encode($obj, JSON_UNESCAPED_SLASHES));
	    PrestaShopLogger::addLog("external_data=$external_data", 1, null, __CLASS__, 40);

        //check to see if we need to render the Splash (connect) page, or the management page
        $is_connected = false;
        $advertiser_id = '';
        $bc_id = '';
        $catalog_id = '';
        $pixel_code = '';
        $access_token = Configuration::get('tt4b_access_token');
        $advanced_matching = true;
        $processing = 0;
        $approved = 0;
        $rejected = 0;
        if ($access_token !== false) {
            $is_connected = true;
            $business_profile_rsp = $this->getBusinessProfile();
            $business_profile = json_decode($business_profile_rsp, true);
            if (!is_null($business_profile["data"]["adv_id"])) {
                $advertiser_id = $business_profile["data"]["adv_id"];
                Configuration::updateValue('tt4b_adv_id', $advertiser_id);
            }
            if (!is_null($business_profile["data"]["bc_id"])) {
                $bc_id = $business_profile["data"]["bc_id"];
                Configuration::updateValue('tt4b_bc_id', $bc_id);
            }
            if (!is_null($business_profile["data"]["pixel_code"])) {
                $pixel_code = $business_profile["data"]["pixel_code"];
                Configuration::updateValue('tt4b_pixel_code', $pixel_code);
            }
            if (!is_null($business_profile["data"]["catalog_id"])) {
                $catalog_id = $business_profile["data"]["catalog_id"];
                Configuration::updateValue('tt4b_catalog_id', $catalog_id);
            }
            if (!is_null($business_profile["data"]["catalog_id"]) && !is_null($business_profile["data"]["bc_id"])) {
                $this->fullCatalogSync();
                $productReviewStatus = $this->getCatalogProcessingStatus($access_token, $bc_id, $catalog_id);
                $processing = $productReviewStatus["processing"];
                $approved = $productReviewStatus["approved"];
                $rejected = $productReviewStatus["rejected"];
            }
            if (is_null($advertiser_id) || is_null($pixel_code) || is_null($access_token)) {
                //set advanced matching to false if the pixel cannot be found
                $advanced_matching = false;
                Configuration::updateValue('tt4b_advanced_matching', $advanced_matching);
            } else {
                $pixel_rsp = $this->getPixels($access_token, $advertiser_id, $pixel_code);
                $pixels = json_decode($pixel_rsp, true);
                $pixel = $pixels["data"]["pixels"][0];
                $advanced_matching = $pixel["advanced_matching_fields"]["email"];
                Configuration::updateValue('tt4b_advanced_matching', $advanced_matching);
            }
        }
        $this->context->controller->addCSS('https://sf16-scmcdn-va.ibytedtos.com/obj/static-us/ads/ecommerce-demo/static/css/universal.9aeb226d.chunk.css');
        $this->context->controller->addCSS('https://sf16-scmcdn-va.ibytedtos.com/obj/static-us/ads/ecommerce-demo/static/css/bytedance.a3ab9203.chunk.css');
        $this->context->controller->addCSS('https://sf16-scmcdn-va.ibytedtos.com/obj/static-us/ads/ecommerce-demo/static/css/vendors.44654ff3.chunk.css');

        $this->smarty->assign(
            array(
            'business_platform' => $business_platform,
            'external_business_id' => $external_business_id,
            'advertiser_id' => $advertiser_id,
            'bc_id' => $bc_id,
            'catalog_id' => $catalog_id,
            'pixel_code' => $pixel_code,
            'external_data' => $external_data,
            'is_connected' => $is_connected,
            'processing' => $processing,
            'approved' => $approved,
            'rejected' => $rejected,
            'advanced_matching' => $advanced_matching,
            'access_token' => $access_token,
            'store_name' => $shop_name,
            'shop_url' => $shop_url,
            'request_uri' => $request_uri
            )
        );
        return $this->display(__FILE__, 'views/templates/admin/plugin.tpl');
    }

    public function fullCatalogSync()
    {
        //this method will retrieve products from the merchant DB and sync them over to TikTok
        //catalog manager
        $business_profile_rsp = $this->getBusinessProfile();
        if ($business_profile_rsp == '') {
	        PrestaShopLogger::addLog('full catalog sync error with biz profile', 1, null, __CLASS__, 255612);
            return;
        }
        $business_profile = json_decode($business_profile_rsp, true);
        if ($business_profile["message"] !== "OK") {
	        PrestaShopLogger::addLog('full catalog sync not OK from biz profile', 1, null, __CLASS__, 255612);
            return;
        }
        $catalog_id = $business_profile["data"]["catalog_id"];
        $bc_id = $business_profile["data"]["bc_id"];
        $store_name = $business_profile["data"]["store_name"];
        if (is_null($catalog_id) || is_null($bc_id) || is_null($store_name)) {
	        PrestaShopLogger::addLog("full catalog null params bc_id: $bc_id, catalog_id: $catalog_id, store_name: $store_name",
		        1, null, __CLASS__, 255612);
            return;
        }
        $access_token = Configuration::get('tt4b_access_token');

        //query params
        $id_lang = (int)Context::getContext()->language->id;
        $start = 0;
        $limit = 5000;
        $order_by = 'id_product';
        $order_way = 'DESC';
        $id_category = false;
        $only_active = true;
        $context = null;

        //get all products
        $products_base = Product::getProducts(
            $id_lang,
            $start,
            $limit,
            $order_by,
            $order_way,
            $id_category,
            $only_active,
            $context
        );
        $products = Product::getProductsProperties($this->context->language->id, $products_base);
        if (count($products) == 0) {
	        PrestaShopLogger::addLog("full catalog sync no products retrieved from Product::getProductsProperties",
		        1, null, __CLASS__, 255612);
        }
        //full catalog sync
        $dpa_products = [];
        $count = 0;
        foreach ($products as $product) {
            $sku_id = (string)($product['id_product']);
            $title = $product['name'];
            $description = $product['description_short'];

            if ($description === "") {
                $description = $title;
            }
            $description = strip_tags($description);

            $condition = Tools::strtoupper($product['condition']);
            $price = (string)($product['price']);
            $brand = $store_name;

            $availability = "IN_STOCK";
            if ($product['available_for_order'] !== "1") {
                $availability = "OUT_OF_STOCK";
            }

            //product url
            $link = new Link();
            $product_url = $link->getProductLink((int)($product['id_product']));

            //image url
            $image = Product::getCover((int)$product['id_product']);
            $image_url = $link->getImageLink($product['link_rewrite'] ?? $product['name'], (int)$image['id_image'], 'large_default');
            if (Tools::substr($image_url, 0, 7) !== "http://") {
                $image_url = "http://" . $image_url;
            }

	        // if any of the values are empty, the whole request will fail, so skip the product.
	        $missing_fields = [];
	        if ( '' === $sku_id || false === $sku_id ) {
		        $missing_fields[] = 'sku_id';
	        }
	        if ( '' === $title || false === $title ) {
		        $missing_fields[] = 'title';
	        }
	        if ( '' === $image_url || false === $image_url ) {
		        $missing_fields[] = 'image_url';
	        }
	        if ( '' === $price || '0' === $price ) {
		        $missing_fields[] = 'price';
	        }
	        if ( count( $missing_fields ) > 0 ) {
		        $debug_message = sprintf(
			        'sku_id: %s is missing the following fields for product sync: %s',
			        $sku_id,
			        join( ',', $missing_fields )
		        );
		        PrestaShopLogger::addLog($debug_message, 1, null, __CLASS__, 255612);
		        continue;
	        }

            $dpa_product = [
                'sku_id' => $sku_id,
                'item_group_id' => $sku_id,
                'title' => $title,
                'availability' => $availability,
                'description' => $description,
                'image_link' => $image_url,
                'brand' => $brand,
                'profession' => [
                    'condition' => $condition,
                ],
                'price' => [
                    'price' => $price
                ],
                'landing_url' => [
                    'link' => $product_url
                ]
            ];
            array_push($dpa_products, $dpa_product);
            $count += 1;
            if ($count == 400) {
                //push every 400 products to avoid API limit
                $curl = curl_init();
                $dpa_product_information = [
                    'bc_id' => $bc_id,
                    'catalog_id' => $catalog_id,
                    'dpa_products' => $dpa_products
                ];
                $data = json_encode($dpa_product_information, JSON_UNESCAPED_SLASHES);
                $headers = array(
                    "Content-Type:application/json",
                    "Access-Token:$access_token",
                );
                $optArray = array(
                    CURLOPT_POST => true,
                    CURLOPT_URL => 'https://business-api.tiktok.com/open_api/v1.2/catalog/product/upload/',
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                );
                curl_setopt_array($curl, $optArray);
                curl_exec($curl);
                curl_close($curl);
                $count = 0;
                $dpa_products = [];
            }
        }

        $curl = curl_init();
        $dpa_product_information = [
            'bc_id' => $bc_id,
            'catalog_id' => $catalog_id,
            'dpa_products' => $dpa_products
        ];
        $data = json_encode($dpa_product_information, JSON_UNESCAPED_SLASHES);
        $headers = array(
            "Content-Type:application/json",
            "Access-Token:$access_token",
        );
        $optArray = array(
            CURLOPT_POST => true,
            CURLOPT_URL => 'https://business-api.tiktok.com/open_api/v1.2/catalog/product/upload/',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        );
        curl_setopt_array($curl, $optArray);
        curl_exec($curl);
        curl_close($curl);
    }

    public function createOpenSourceApp($smb_id, $smb_name, $redirect_url)
    {
        //returns a raw API response from TikTok marketing_api/api/developer/app/create_auto_approve/ endpoint
        $curl = curl_init();
        $url = 'https://ads.tiktok.com/marketing_api/api/developer/app/create_auto_approve/';
        $tt4b_prestashop_token = '244e1de7-8dad-4656-a859-8dc09eea299d';
        $params = [
            'business_platform' => 'PROD',
            'smb_id' => $smb_id,
            'smb_name' => $smb_name,
            'redirect_url' => $redirect_url
        ];
        $data = json_encode($params, JSON_UNESCAPED_SLASHES);
        $headers = array(
            "Content-Type:application/json",
            "Access-Token:$tt4b_prestashop_token",
            "Referer:https://ads.tiktok.com"
        );

        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt_array($curl, $optArray);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function getAccessToken($app_id, $secret, $auth_code)
    {
        //returns a raw API response from TikTok oauth2/access_token_v2/ endpoint
        $curl = curl_init();
        $url = 'https://ads.tiktok.com/open_api/oauth2/access_token_v2/?app_id='
            .$app_id .'&secret='.$secret.'&auth_code='.$auth_code;
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($curl, $optArray);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function getBusinessProfile()
    {
        //returns a raw API response from TikTok tbp/business_profile/get/ endpoint
        $curl = curl_init();
        $access_token = Configuration::get('tt4b_access_token');
        $external_business_id = Configuration::get('tt4b_external_business_id');
        if ($external_business_id == false || $access_token == false) {
            return '';
        }
        $url = 'https://business-api.tiktok.com/open_api/v1.2/tbp/business_profile/get/?business_platform='.
            'PRESTA_SHOP&full_data=1&external_business_id='.$external_business_id;
        $headers = array(
            "Content-Type:application/json",
            "Access-Token:$access_token",
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION => true
        );
        curl_setopt_array($curl, $optArray);
        $result = curl_exec($curl);
	    PrestaShopLogger::addLog("response from business_profile=$result", 1, null, __CLASS__, 40);
        curl_close($curl);
        return $result;
    }

    public function getPixels($access_token, $advertiser_id, $pixel_code)
    {
        //returns a raw API response from TikTok pixel/list/ endpoint
        $curl = curl_init();
        $url = 'https://business-api.tiktok.com/open_api/v1.2/pixel/list/?advertiser_id='.$advertiser_id.'&code='.$pixel_code;
        $headers = array(
            "Content-Type:application/json",
            "Access-Token:$access_token",
        );

        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => $headers
        );
        curl_setopt_array($curl, $optArray);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function getCatalogProcessingStatus($access_token, $bc_id, $catalog_id)
    {
        //returns a counter of how many items are approved, processing, or rejected
        //from the TikTok catalog/product/get/ endpoint
        $curl = curl_init();
        $url = 'https://business-api.tiktok.com/open_api/v1.2/catalog/product/get/?page_size=500&bc_id='
            .$bc_id.'&catalog_id='.$catalog_id;
        $headers = array(
            "Content-Type:application/json",
            "Access-Token:$access_token",
        );

        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => $headers
        );
        curl_setopt_array($curl, $optArray);
        $result = curl_exec($curl);
        curl_close($curl);
        $obj = json_decode($result, true);
        if ($obj["message"] !== "OK") {
            return '';
        }
        $processing = 0;
        $approved = 0;
        $rejected = 0;
        foreach ($obj["data"]["list"] as $product) {
            if ($product["audit"]["audit_status"] == "processing") {
                $processing += 1;
            }
            if ($product["audit"]["audit_status"] == "approved") {
                $approved += 1;
            }
            if ($product["audit"]["audit_status"] == "rejected") {
                $rejected += 1;
            }
        }

        return array(
            'processing' => $processing,
            'approved' => $approved,
            'rejected' => $rejected,
        );
    }

    public function hookDisplayHome()
    {
        //        after finish set-up, the merchant is redirected to the front office page.
        //        If an auth_code can be found in the URL, retrieve the long term access token, and
        //        redirect the merchant to the back office module page
        $app_id = Configuration::get('tt4b_app_id');
        $secret = Configuration::get('tt4b_secret');

        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $splitUrl = explode('&', $url);
        for ($i=0; $i<count($splitUrl); $i++) {
            if (Tools::strpos($splitUrl[$i], 'auth_code') !== false) {
                $auth_code = Tools::substr($splitUrl[$i], Tools::strpos($splitUrl[$i], "=") + 1);
                if ($app_id !== false && $secret !== false) {
                    $access_token_rsp = $this->getAccessToken($app_id, $secret, $auth_code);
                    $results = json_decode($access_token_rsp, true);
                    if ($results["message"] == "OK") {
                        //status OK
                        $access_token = $results["data"]["access_token"];
                        Configuration::updateValue('tt4b_access_token', $access_token);
                        Tools::redirectAdmin(Configuration::get('tt4b_module_url'));
                    }
                }
            }
        }
    }

    public function hookDisplayProductExtraContent()
    {
	    PrestaShopLogger::addLog("hit hookDisplayProductExtraContent", 1, null, __CLASS__, 255611);
        $advertiser_id = Configuration::get('tt4b_adv_id');
        $pixel_code = Configuration::get('tt4b_pixel_code');
        $access_token = Configuration::get('tt4b_access_token');
        if ($advertiser_id == false  || $pixel_code == false  || $access_token == false) {
	        PrestaShopLogger::addLog("terminate early hookDisplayProductExtraContent advertiser_id: $advertiser_id, 
	        pixel_code: $pixel_code, access_token: $access_token", 1, null, __CLASS__, 255611);
            return;
        }
        $advanced_matching = Configuration::get('tt4b_advanced_matching');
        $hashed_email = '';
        if ($advanced_matching == '1') {
            $email = $this->context->customer->email;
            $hashed_email = hash('SHA256', Tools::strtolower($email));
        }

        //check if there is a ttclid
        $ttclid = '';
        if ($this->context->cookie->__isset('tt4b_ttclid')) {
            $ttclid = $this->context->cookie->__get('tt4b_ttclid');
        }

        $event = "ViewContent";
        //timestamp in ISO format
        $timestamp = date('c', time());
        $ipaddress = Tools::getRemoteAddr();
        $product_object = new Product(
            (int)Tools::getValue('id_product'),
            true,
            (int)Configuration::get('PS_LANG_DEFAULT')
        );
        $content_id = Tools::getValue('id_product');
        $product_type = $product_object->category;
        $price = $product_object->price;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $properties = [
            'contents' => [
                'price' => (int)$price,
                'content_type' => $product_type,
                'content_id' => $content_id
            ],
        ];

        $context = [
            'ad' => [
                'callback' => $ttclid
            ],
            'page' => [
                'url' => $url
            ],
            'ip' => $ipaddress,
            'user_agent' => $userAgent,
            'user' => [
                'email' => $hashed_email
            ]
        ];

        $curl = curl_init();
        $params = [
            'pixel_code' => $pixel_code,
            'partner_name' => "PRESTA_SHOP",
            'event' => $event,
            'timestamp' => $timestamp,
            'properties' => $properties,
            'context' => $context
        ];
        $data = json_encode($params, JSON_UNESCAPED_SLASHES);
        $headers = array(
            "Content-Type: application/json",
            "Access-Token:$access_token",
        );

        $optArray = array(
            CURLOPT_POST => true,
            CURLOPT_URL => "https://business-api.tiktok.com/open_api/v1.2/pixel/track/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
        );
        curl_setopt_array($curl, $optArray);

        $result = curl_exec($curl);
	    PrestaShopLogger::addLog("response from ViewContent_s2s=$result", 1, null, __CLASS__, 255611);
        curl_close($curl);
    }

    public function hookDisplayOrderConfirmation($params)
    {
	    PrestaShopLogger::addLog("hit hookDisplayOrderConfirmation", 1, null, __CLASS__, 255611);
        $advertiser_id = Configuration::get('tt4b_adv_id');
        $pixel_code = Configuration::get('tt4b_pixel_code');
        $access_token = Configuration::get('tt4b_access_token');
        if ($advertiser_id == false  || $pixel_code == false  || $access_token == false) {
	        PrestaShopLogger::addLog("terminate early hookDisplayOrderConfirmation advertiser_id: $advertiser_id, 
	        pixel_code: $pixel_code, access_token: $access_token", 1, null, __CLASS__, 255611);
            return;
        }
        $advanced_matching = Configuration::get('tt4b_advanced_matching');
        $hashed_email = '';
        if ($advanced_matching == '1') {
            $email = $this->context->customer->email;
            $hashed_email = hash('SHA256', Tools::strtolower($email));
        }

        //check if there is a ttclid
        $ttclid = '';
        if ($this->context->cookie->__isset('tt4b_ttclid')) {
            $ttclid = $this->context->cookie->__get('tt4b_ttclid');
        }

        $event = "Purchase";
        //timestamp in ISO format
        $timestamp = date('c', time());
        $order = $params["cart"];
        $ipaddress = Tools::getRemoteAddr();
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $products = $order->getProducts();
        $value = 0;
        $contents = [];
        foreach ($products as $product) {
            $content = [
                'price' => (int)$product["price"],
                'content_id' => $product["id_product"],
                'quantity' => $product["product_quantity"],
            ];
            $value += $product["price"]*$product["product_quantity"];
            array_push($contents, $content);
        }

        $properties = [
            'contents' => $contents,
            'value' => (int)$value,
        ];

        $context = [
            'ad' => [
                'callback' => $ttclid
            ],
            'page' => [
                'url' => $url
            ],
            'ip' => $ipaddress,
            'user_agent' => $userAgent,
            'user' => [
                'email' => $hashed_email
            ]
        ];

        $curl = curl_init();
        $params = [
            'pixel_code' => $pixel_code,
            'partner_name' => "PRESTA_SHOP",
            'event' => $event,
            'timestamp' => $timestamp,
            'properties' => $properties,
            'context' => $context
        ];
        $data = json_encode($params, JSON_UNESCAPED_SLASHES);
        $headers = array(
            "Content-Type: application/json",
            "Access-Token:$access_token",
        );

        $optArray = array(
            CURLOPT_POST => true,
            CURLOPT_URL => "https://business-api.tiktok.com/open_api/v1.2/pixel/track/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
        );
        curl_setopt_array($curl, $optArray);
        $result = curl_exec($curl);
	    PrestaShopLogger::addLog("response from Purchase_s2s=$result", 1, null, __CLASS__, 255611);
        curl_close($curl);
    }

    public function hookDisplayCheckoutSummaryTop($params)
    {
	    PrestaShopLogger::addLog("hit hookDisplayCheckoutSummaryTop", 1, null, __CLASS__, 255611);
        $advertiser_id = Configuration::get('tt4b_adv_id');
        $pixel_code = Configuration::get('tt4b_pixel_code');
        $access_token = Configuration::get('tt4b_access_token');
        if ($advertiser_id == false  || $pixel_code == false  || $access_token == false) {
	        PrestaShopLogger::addLog("terminate early hookDisplayCheckoutSummaryTop advertiser_id: $advertiser_id, 
	        pixel_code: $pixel_code, access_token: $access_token", 1, null, __CLASS__, 255611);
            return;
        }
        $advanced_matching = Configuration::get('tt4b_advanced_matching');
        $hashed_email = '';
        if ($advanced_matching == '1') {
            $email = $this->context->customer->email;
            $hashed_email = hash('SHA256', Tools::strtolower($email));
        }

        //check if there is a ttclid
        $ttclid = '';
        if ($this->context->cookie->__isset('tt4b_ttclid')) {
            $ttclid = $this->context->cookie->__get('tt4b_ttclid');
        }

        $event = "InitiateCheckout";
        $timestamp = date('c', time());
        $cart = $params["cart"];
        $ipaddress = Tools::getRemoteAddr();
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $products = $cart->getProducts();
        $value = 0;
        $contents = [];
        foreach ($products as $product) {
            $content = [
                'price' => (int)$product["price"],
                'content_id' => $product["id_product"],
                'quantity' => $product["cart_quantity"],
            ];
            $value += $product["price"]*$product["cart_quantity"];
            array_push($contents, $content);
        }

        $properties = [
            'contents' => $contents,
            'value' => (int)$value,
        ];

        $context = [
            'ad' => [
                'callback' => $ttclid
            ],
            'page' => [
                'url' => $url
            ],
            'ip' => $ipaddress,
            'user_agent' => $userAgent,
            'user' => [
                'email' => $hashed_email
            ]
        ];

        $curl = curl_init();
        $params = [
            'pixel_code' => $pixel_code,
            'partner_name' => "PRESTA_SHOP",
            'event' => $event,
            'timestamp' => $timestamp,
            'properties' => $properties,
            'context' => $context
        ];
        $data = json_encode($params, JSON_UNESCAPED_SLASHES);
        $headers = array(
            "Content-Type: application/json",
            "Access-Token:$access_token",
        );

        $optArray = array(
            CURLOPT_POST => true,
            CURLOPT_URL => "https://business-api.tiktok.com/open_api/v1.2/pixel/track/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
        );
        curl_setopt_array($curl, $optArray);
        $result = curl_exec($curl);
	    PrestaShopLogger::addLog("response from InitiateCheckout_s2s=$result", 1, null, __CLASS__, 255611);
        curl_close($curl);
    }

    public function hookdisplayShoppingCartFooter($params)
    {
	    PrestaShopLogger::addLog("hit hookDisplayCheckoutSummaryTop", 1, null, __CLASS__, 255611);
        $advertiser_id = Configuration::get('tt4b_adv_id');
        $pixel_code = Configuration::get('tt4b_pixel_code');
        $access_token = Configuration::get('tt4b_access_token');
        if ($advertiser_id == false  || $pixel_code == false  || $access_token == false) {
	        PrestaShopLogger::addLog("terminate early hookDisplayCheckoutSummaryTop advertiser_id: $advertiser_id, 
	        pixel_code: $pixel_code, access_token: $access_token", 1, null, __CLASS__, 255611);
            return;
        }
        $advanced_matching = Configuration::get('tt4b_advanced_matching');
        $hashed_email = '';
        if ($advanced_matching == '1') {
            $email = $this->context->customer->email;
            $hashed_email = hash('SHA256', Tools::strtolower($email));
        }

        //check if there is a ttclid
        $ttclid = '';
        if ($this->context->cookie->__isset('tt4b_ttclid')) {
            $ttclid = $this->context->cookie->__get('tt4b_ttclid');
        }
        $event = "AddToCart";

        $timestamp = date('c', time());
        $cart = $params["cart"];
        $ipaddress = Tools::getRemoteAddr();
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $products = $cart->getProducts();
        $value = 0;
        $contents = [];
        foreach ($products as $product) {
            $content = [
                'price' => (int)$product["price"],
                'content_id' => $product["id_product"],
                'quantity' => $product["cart_quantity"],
            ];
            $value += $product["price"]*$product["cart_quantity"];
            array_push($contents, $content);
        }

        $properties = [
            'contents' => $contents,
            'value' => (int)$value,
        ];

        $context = [
            'ad' => [
                'callback' => $ttclid
            ],
            'page' => [
                'url' => $url
            ],
            'ip' => $ipaddress,
            'user_agent' => $userAgent,
            'user' => [
                'email' => $hashed_email
            ]
        ];

        $curl = curl_init();
        $params = [
            'pixel_code' => $pixel_code,
            'partner_name' => "PRESTA_SHOP",
            'event' => $event,
            'timestamp' => $timestamp,
            'properties' => $properties,
            'context' => $context
        ];
        $data = json_encode($params, JSON_UNESCAPED_SLASHES);
        $headers = array(
            "Content-Type: application/json",
            "Access-Token:$access_token",
        );

        $optArray = array(
            CURLOPT_POST => true,
            CURLOPT_URL => "https://business-api.tiktok.com/open_api/v1.2/pixel/track/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
        );
        curl_setopt_array($curl, $optArray);
        $result = curl_exec($curl);
	    PrestaShopLogger::addLog("response from AddToCart_s2s=$result", 1, null, __CLASS__, 255611);
        curl_close($curl);
    }
}
