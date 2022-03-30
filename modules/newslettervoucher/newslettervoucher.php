<?php

/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2021 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

if (file_exists(_PS_MODULE_DIR_ . 'newslettervoucher/lib/voucherengine/engine.php') && class_exists('newslettervoucher')) {
    require_once _PS_MODULE_DIR_ . 'newslettervoucher/lib/voucherengine/engine.php';
}

if (file_exists(_PS_MODULE_DIR_ . 'newslettervoucher/model/nvoucher.php')) {
    require_once _PS_MODULE_DIR_ . 'newslettervoucher/model/nvoucher.php';
}

class newslettervoucher extends Module
{
    public function __construct()
    {
        $this->name = 'newslettervoucher';
        $this->displayName = $this->l('Voucher for newsletter subscription');
        $this->description = $this->l('Give unique voucher codes for newsletter subscription');
        $this->tab = 'advertising_marketing';
        $this->author = 'MyPresta.eu';
        $this->bootstrap = true;
        $this->mypresta_link = 'https://mypresta.eu/modules/advertising-and-marketing/voucher-code-after-registration.html';
        $this->module_key = '3ec1ba193b0e3c189c4f14761c9188e9';
        $this->version = '1.6.3';
        parent::__construct();

        $this->secure_key = Tools::encrypt($this->name);
        $this->l('Active');
        $this->l('Priority');

        $this->checkforupdates();
        //voucher engine fields to translate - for translation purposes
        $this->l('Tax excluded');
        $this->l('Tax included');
        $this->l('Shipping excluded');
        $this->l('Shipping included');
        $this->l('Enabled');
        $this->l('Disabled');
        $this->l('Percent(%)');
        $this->l('Amount');
        $this->l('None');
        $this->l('Value');
        $this->l('Amount');
        $this->l('Order (without shipping)');
        $this->l('Specific product');
        $this->l('Product ID');
        $this->l('enter product ID number');
        $this->l('how to get product id?');
        $this->l('Select categories from list above, use CTRL+click to select multiple categories, CTRL+A to select all of them');
        $this->l('Select products from list above, use CTRL+click to select multiple products, CTRL+A to select all of them');
        $this->l('General settings');
        $this->l('Name');
        $this->l('This will be displayed in the cart summary, as well as on the invoice');
        $this->l('Description');
        $this->l('For your eyes only. This will never be displayed to the customer');
        $this->l('Voucher length');
        $this->l('How many characters will be used to generate voucher code');
        $this->l('Enable sufix');
        $this->l('Turn this option on if you want to enable sufix for your voucher code. It will be added AFTER generated code like CODE_sufix.');
        $this->l('Sufix');
        $this->l('Define sufix for your voucher code');
        $this->l('Enable prefix');
        $this->l('Turn this option on if you want to enable prefix for your voucher code. It will be added BEFORE generated code like prefix_CODE.');
        $this->l('Prefix');
        $this->l('Define prefix for your voucher code');
        $this->l('Highlight');
        $this->l('If the voucher is not yet in the cart, it will be displayed in the cart summary.');
        $this->l('Partial use');
        $this->l('Only applicable if the voucher value is greater than the cart total. If you do not allow partial use, the voucher value will be lowered to the total order amount. If you allow partial use, however, a new voucher will be created with the remainder.');
        $this->l('Priority');
        $this->l('Cart rules are applied by priority. A cart rule with a priority of "1" will be processed before a cart rule with a priority of "2".');
        $this->l('Active');
        $this->l('Conditions');
        $this->l('Expiration time');
        $this->l('Define how long (in days) voucher code will be active');
        $this->l('Minimum amount');
        $this->l('You can choose a minimum amount for the cart either with or without the taxes and shipping.');
        $this->l('Total available');
        $this->l('The cart rule will be applied to the first "X" customers only.');
        $this->l('Total available for each user');
        $this->l('A customer will only be able to use the cart rule "X" time(s).');
        $this->l('Add rule concerning categories');
        $this->l('Add rule concerning products');
        $this->l('Actions');
        $this->l('Free shipping');
        $this->l('Apply a discount');
        $this->l('Apply discount to');
        $this->l('Turn this option on if you want dont want to allow to use this code with other voucher codes');
        $this->l('Uncombinable with other codes');
        $this->l('Select manufacturers from list above, use CTRL+click to select multiple products, CTRL+A to select all of them');
        $this->l('Add rule concerning manufacturers');
        $this->l('Add rule concerning attributes');
        $this->l('Select Attributes from list above, use CTRL+click to select multiple products, CTRL+A to select all of them');
        $this->l('Cheapest product');
        $this->l('Selected products');
        $this->l('Cumulative with price reductions');
        $this->l('Turn this option on if you want to allow to use this code with price reductions');
        $this->l('Date from');
        $this->l('Date to');
        $this->l('Expiry date, format: YYYY-MM-DD HH:MM:SS');
        $this->l('Start date, format: YYYY-MM-DD HH:MM:SS');
        $this->l('Conditions');
        //2.1
        $this->l('Search for product');
        $this->l('or enter product ID');
        $this->l('product combination ID');
        //2.5
        $this->l('Send free gift');
        //2.7
        $this->l('Add rule concerning carriers');
        $this->l('Select carriers from list above, use CTRL+click to select multiple items CTRL+A to select all of them');
        //3.2
        $this->l('Please fill out each available field - do not leave fields empty. Otherwise module will not generate coupon codes or these codes will not work properly.');
        $this->l('Below you can find links to YouTube videos where you can find more informations about this voucher code configuration tool.');
        $this->l('Please note that settings here are related to one unique voucher code that module will generate.');
        $this->l('Suggested values for fields below: Total available: 1, Total available for each user: 1');
        $this->l('This means that customer that will receive one unique voucher will have possibility to use it during checkout only one time (as long as you will use suggested values)');
        $this->l('Video description of advanced voucher configuration tool');
        $this->l('General settings');
        $this->l('Conditions settings');
        $this->l('Actions settings');
        //3.4
        $this->l('Add rule concerning suppliers');
        $this->l('Select suppliers from list above, use CTRL+click to select multiple products, CTRL+A to select all of them');
        //3.6
        $this->l('Share voucher between shops');
        $this->l('If enabled - voucher will be shared between shops (multistore), if disabled - voucher will be available only in shop where it was generated');
        //4.0
        $this->l('Select groups from list above, use CTRL+click to select multiple items CTRL+A to select all of them');
        $this->l('Add rule concerning groups of customers');
        //4.4
        $this->l('Exclude discounted products');
        $this->l('If enabled, the voucher will not apply to products already on sale.');
        //5.2
        $this->l('Select countries from list above, use CTRL+click to select multiple items CTRL+A to select all of them');
        $this->l('Add rule concerning countries');
    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 16 //
        // ---------- //
        // ---------- //
        $this->mkey = "nlc";
        if (@file_exists('../modules/' . $this->name . '/key.php')) {
            @require_once('../modules/' . $this->name . '/key.php');
        } else {
            if (@file_exists(dirname(__FILE__) . $this->name . '/key.php')) {
                @require_once(dirname(__FILE__) . $this->name . '/key.php');
            } else {
                if (@file_exists('modules/' . $this->name . '/key.php')) {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }
        if ($form == 1) {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:0px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_module_block_settings">
                         ' . ($this->psversion() == 5 ? '<legend style="">' . $this->l('MyPresta updates') . '</legend>' : '') . '
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? ($this->inconsistency(0) ? '' : '') . $this->checkforupdates(1) : '') . '
                                <button style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" class="button btn btn-default" />
                                <i class="process-icon-update"></i>
                                ' . $this->l('Check now') . '
                                </button>
                            </div>
                            <label>' . $this->l('Updates notifications') . '</label>
                            <div class="margin-form">
                                <select name="mypresta_updates">
                                    <option value="-">' . $this->l('-- select --') . '</option>
                                    <option value="1" ' . ((int)(Configuration::get('mypresta_updates') == 1) ? 'selected="selected"' : '') . '>' . $this->l('Enable') . '</option>
                                    <option value="0" ' . ((int)(Configuration::get('mypresta_updates') == 0) ? 'selected="selected"' : '') . '>' . $this->l('Disable') . '</option>
                                </select>
                                <p class="clear">' . $this->l('Turn this option on if you want to check MyPresta.eu for module updates automatically. This option will display notification about new versions of this addon.') . '</p>
                            </div>
                            <label>' . $this->l('Module page') . '</label>
                            <div class="margin-form">
                                <a style="font-size:14px;" href="' . $this->mypresta_link . '" target="_blank">' . $this->displayName . '</a>
                                <p class="clear">' . $this->l('This is direct link to official addon page, where you can read about changes in the module (changelog)') . '</p>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="submit_settings_updates"class="button btn btn-default pull-right" />
                                <i class="process-icon-save"></i>
                                ' . $this->l('Save') . '
                                </button>
                            </div>
                        </form>
                    </fieldset>
                    <style>
                    #fieldset_myprestaupdates {
                        display:block;clear:both;
                        float:inherit!important;
                    }
                    </style>
                </div>
            </div>
            </div>';
        } else {
            if (defined('_PS_ADMIN_DIR_')) {
                if (Tools::isSubmit('submit_settings_updates')) {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') != false) {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = newslettervoucherUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (newslettervoucherUpdate::version($this->version) < newslettervoucherUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax', 'false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = newslettervoucherUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (newslettervoucherUpdate::version($this->version) < newslettervoucherUpdate::version(newslettervoucherUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public function install()
    {
        if ($this->psversion() == 5 || $this->psversion() == 6 || $this->psversion() == 7) {
            if (parent::install() == false or
                $this->registerHook('actionFrontControllerInitAfter') == false or
                $this->registerHook('displayHeader') == false or
                $this->registerHook('actionCustomerAccountAdd') == false or
                $this->installdb() == false or
                $this->createMenu() == false) {
                return false;
            }
            return true;
        }
    }

    public function uninstall()
    {
        if (parent::uninstall() == false) {
            return false;
        }

        $idTabs = array();
        if (Tab::getIdFromClassName('AdminNewsletterVoucherVouchers')) {
            $idTabs[] = Tab::getIdFromClassName('AdminNewsletterVoucherVouchers');
            foreach ($idTabs as $idTab) {
                if ($idTab) {
                    $tab = new Tab($idTab);
                    $tab->delete();
                }
            }
        }
        return true;
    }

    public function hookactionFrontControllerInitAfter($params)
    {
        if (Tools::getValue('module') == 'ps_emailsubscription' && Tools::getValue('controller') == 'subscription' && Configuration::get('NW_VERIFICATION_EMAIL') != 1) {
            $this->newsletterRegistration();
        }

        if (Tools::getValue('email', 'false') != 'false' && strpos($_SERVER['REQUEST_URI'], 'labpopupnewsletter/ajax.php') !== false) {
            $_POST['action'] = 0;
            $this->newsletterRegistration();
        }
    }

    public function hookHeader($params)
    {
        if (Tools::isSubmit('submitNewsletter') && Configuration::get('NW_VERIFICATION_EMAIL') != 1) {
            $this->newsletterRegistration();
        } elseif (Tools::getValue('module') == 'ps_emailsubscription' && Tools::getValue('controller') == 'verification' && Tools::getValue('token', 'false') != 'false') {
            if ($email = $this->getGuestEmailByToken(Tools::getValue('token'))) {
                if (!nvoucher::getOneByEmail($email)) {
                    $this->sendNewsletterVoucher($email);
                }
            } elseif ($email = $this->getUserEmailByToken(Tools::getValue('token'))) {
                if (!nvoucher::getOneByEmail($email)) {
                    $this->sendNewsletterVoucher($email);
                }
            }
        }
    }

    protected function getGuestEmailByToken($token)
    {
        $sql = 'SELECT `email`
                FROM `' . _DB_PREFIX_ . 'emailsubscription`
                WHERE MD5(CONCAT( `email` , `newsletter_date_add`, \'' . pSQL(Configuration::get('NW_SALT')) . '\')) = \'' . pSQL($token) . '\'
                AND `active` = 1';
        return Db::getInstance()->getValue($sql);
    }

    protected function getUserEmailByToken($token)
    {
        $sql = 'SELECT `email`
                FROM `' . _DB_PREFIX_ . 'customer`
                WHERE MD5(CONCAT( `email` , `date_add`, \'' . pSQL(Configuration::get('NW_SALT')) . '\')) = \'' . pSQL($token) . '\'
                AND `newsletter` = 1';
        return Db::getInstance()->getValue($sql);
    }

    public function cronJobUrl()
    {
        $croonurl = Tools::getProtocol(Tools::usingSecureMode()) . $_SERVER['HTTP_HOST'] . $this->getPathUri() . 'cronjob.php?key=' . $this->secure_key;
        return '' . $this->l('Add this url to your cron job table to send voucher codes for customers that signup to newsletter during customer account change process') . '<br />
            			' . $this->l('This will also generate voucher codes for old customers accounts that subscribed to newsletter') . '<br />
                        ' . $croonurl . '';
    }

    public function cronJob()
    {
        if (file_exists(_PS_MODULE_DIR_ . 'newslettervoucher/lib/voucherengine/engine.php') && class_exists('newslettervoucher')) {
            require_once _PS_MODULE_DIR_ . 'newslettervoucher/lib/voucherengine/engine.php';
        }
        $date = Configuration::get('NV_B_DATE');
        if (!strtotime($date)) {
            $date = false;
        }

        $emails = nvoucher::getNewsletterWithoutVoucherBlockNewsletter($date);

        if (is_array($emails)) {
            if (count($emails) > 0) {
                foreach ($emails AS $customer => $details) {
                    $this->sendNewsletterVoucher($details['email']);
                }
            }
        }

        $emails = nvoucher::getNewsletterWithoutVoucher($date);
        if (is_array($emails)) {
            if (count($emails) > 0) {
                foreach ($emails AS $customer => $details) {
                    $this->sendNewsletterVoucher($details['email']);
                }
            }
        }

    }

    public function isNewsletterRegistered($customer_email)
    {
        $sql = 'SELECT `email`
				FROM ' . _DB_PREFIX_ . 'emailsubscription
				WHERE `email` = \'' . pSQL($customer_email) . '\'
				AND id_shop = ' . $this->context->shop->id;

        if (Db::getInstance()->getRow($sql)) {
            return true;
        }

        $sql = 'SELECT `newsletter`
				FROM ' . _DB_PREFIX_ . 'customer
				WHERE `email` = \'' . pSQL($customer_email) . '\'
				AND id_shop = ' . $this->context->shop->id;

        if ($registered = Db::getInstance()->getRow($sql)) {
            if ($registered['newsletter'] == '1') {
                return true;
            }
        }

        return false;
    }

    public function newsletterRegistration()
    {
        if (empty($_POST['email']) || !Validate::isEmail($_POST['email'])) {
            return $this->error = $this->l('Invalid email address.');
        }

        if ($_POST['action'] == '0') {
            $register_status = $this->isNewsletterRegistered($_POST['email']);
            if ($register_status == true) {
                return $this->error = $this->l('This email address is already registered.');
            } else {
                if (!nvoucher::getOneByEmail($_POST['email'])) {
                    $this->sendNewsletterVoucher($_POST['email']);
                }
            }
        }

    }

    public function createMenu()
    {
        $tab = new Tab();
        $tab->id_parent = Tab::getIdFromClassName('AdminPriceRule');
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Newsletter vouchers';
        }
        $tab->class_name = 'AdminNewsletterVoucherVouchers';
        $tab->module = $this->name;
        $tab->add();

        return true;
    }

    public function hookactionCustomerAccountAdd($params)
    {
        $id_lang = $this->context->cookie->id_lang;
        $newuser = new Customer($params['newCustomer']->id);
        $id_shop = Context::getContext()->shop->id;
        if ($newuser->newsletter == 1) {
            if (!nvoucher::getOneByEmail($newuser->email)) {
                $this->sendNewsletterVoucher($newuser->email);
            }
        }
    }

    private function installdb()
    {
        $prefix = _DB_PREFIX_;
        $engine = _MYSQL_ENGINE_;
        $statements = array();
        $statements[] = "CREATE TABLE IF NOT EXISTS `${prefix}nvoucher` (" . '`id_nvoucher` int(10) NOT NULL AUTO_INCREMENT,' . '`deliverydate` DATETIME,' . '`email` VARCHAR(100),' . '`code` VARCHAR(100),' . 'PRIMARY KEY (`id_nvoucher`)' . ")";

        foreach ($statements as $statement) {
            if (!Db:: getInstance()->Execute($statement)) {
                return false;
            }
        }
        return true;
    }

    public static function psversion($part = 1)
    {
        $version = _PS_VERSION_;
        $exp = explode('.', $version);
        if ($part == 1) {
            return $exp[1];
        }
        if ($part == 2) {
            return $exp[2];
        }
        if ($part == 3) {
            return $exp[3];
        }
    }

    public function getContent()
    {
        if (Tools::isSubmit('selecttab')) {
            Configuration::updateValue('nv_lasttab', Tools::getValue('selecttab'));
        }

        if (Tools::isSubmit('save_voucher_settings')) {
            newslettervoucherVoucherEngine::updateVoucher(Tools::getValue('voucherPrefix'), $_POST);
        }

        if (Tools::isSubmit('submitdisplayFormCronJob')) {
            Configuration::updateValue('NV_B_DATE', Tools::getValue('NV_B_DATE'));
        }

        if (Tools::isSubmit('submitdisplayFormMailTitle')) {
            $title = array();
            foreach (Language::getLanguages(true) AS $value) {
                $title[$value['id_lang']] = Tools::getValue('nv_mtitle_' . $value['id_lang']);
            }
            Configuration::updateValue('nv_mtitle', $title);
            Configuration::updateValue('nv_datetime', Tools::getValue('nv_datetime'));
        }

        return $this->displayFormMailTitle() . $this->displayFormCronJob() . $this->displayForm() . "<div class='bootstrap'>" . "</div>";
    }

    public function sendNewsletterVoucher($user_email)
    {
        $id_lang = $this->context->cookie->id_lang;
        $id_shop = Context::getContext()->shop->id;

        $newCustomer = customer::customerExists($user_email, true, true);

        if ($newCustomer != 0) {
            $coupon = newslettervoucherVoucherEngine::AddVoucherCode('nv_', $newCustomer);
        } else {
            $coupon = newslettervoucherVoucherEngine::AddVoucherCode('nv_', null, null, null, null, null, null, null, null, null, null, null, null, null, null);
        }

        if ($this->psversion() == 5 || $this->psversion() == 6 || $this->psversion() == 7) {
            //GET VALUE OF VOUCHER
            $cartRule = new CartRule(CartRule::getIdByCode($coupon->code));
            $voucher_value = null;
            $voucher_currency_sign = '';
            if ($cartRule->reduction_amount > 0) {
                $voucher_currency = new Currency($cartRule->reduction_currency);
                $voucher_currency_sign = $voucher_currency->sign;
                $voucher_value = number_format($cartRule->reduction_amount, 2, '.', '') . " " . $voucher_currency_sign;
                if ($cartRule->free_shipping == 1) {
                    if ($voucher_value == null) {
                        $voucher_value = $this->l('Free shipping');
                    } else {
                        $voucher_value .= " + " . $this->l('Free shipping');
                    }
                }
            } elseif ($cartRule->reduction_percent > 0) {
                $voucher_value = $cartRule->reduction_percent . "%";
                if ($cartRule->free_shipping == 1) {
                    if ($voucher_value == null) {
                        $voucher_value = $this->l('Free shipping');
                    } else {
                        $voucher_value .= " + " . $this->l('Free shipping');
                    }
                }
            } elseif ($cartRule->free_shipping == 1) {
                if ($voucher_value == null) {
                    $voucher_value = $this->l('Free shipping');
                } else {
                    $voucher_value .= " + " . $this->l('Free shipping');
                }
            }

            $voucher_minimal_currency = new Currency($cartRule->minimum_amount_currency);
            $voucher_minimal_currency_sign = $voucher_minimal_currency->sign;
            $voucher_minimal_value = number_format($cartRule->minimum_amount, 2, '.', '') . " " . $voucher_currency_sign;

            $templatevars['{voucher}'] = $coupon->code;
            //$templatevars['{customer_firstname}'] = $newuser->firstname;
            //$templatevars['{customer_lastname}'] = $newuser->lastname;
            $nv_datetime = (Configuration::get('nv_datetime') == 1 ? true:false);
            $templatevars['{voucher_date_from}'] = Tools::displayDate($coupon->date_from, Context::getContext()->language->id, $nv_datetime);
            $templatevars['{voucher_date_to}'] = Tools::displayDate($coupon->date_to, Context::getContext()->language->id, $nv_datetime);
            $templatevars['{voucher_value}'] = $voucher_value;
            $templatevars['{voucher_minimal_basket_value}'] = $voucher_minimal_value;
            if ($id_lang == null) {
                $id_lang = Context::getContext()->language->id;
            }
            if ($id_shop == null) {
                $id_shop = Context::getContext()->shop->id;
            }

            if (Mail::Send($id_lang, 'newscoupon', Configuration::get('nv_mtitle', $this->context->language->id), $templatevars, (string)$user_email, null, (string)Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop), (string)Configuration::get('PS_SHOP_NAME', null, null, $id_shop), null, null, dirname(__file__) . '/mails/', false, $id_shop)) {
                $nvoucher = new nvoucher();
                $nvoucher->code = $coupon->code;
                $nvoucher->email = $user_email;
                $nvoucher->deliverydate = date("Y-m-d H:i:s");
                $nvoucher->add();
            }
        }
    }

    public function displayForm()
    {
        $nv = new newslettervoucherVoucherEngine('nv_');
        $form = '<div class="bootstrap">
            <form id="updateslideform" action="' . $_SERVER['REQUEST_URI'] . '" class="panel" method="post" enctype="multipart/form-data" >
                    <fieldset style="margin-bottom:10px;">
                        <legend>' . $this->l('Voucher settings') . '</legend>
                        ' . $nv->generateForm() . '
                            <div class="panel-footer">
                                <button class="btn btn-default pull-right" name="save_voucher_settings" type="submit">
                                    <i class="process-icon-save"></i>
                                    ' . $this->l('Save settings') . '
                                </button>
                            </div>
                    </fieldset>
                </form>
            </div>';
        return '
        <style>
            .bootstrap label {
                font-size:16px;
            }
        </style>
        <form name="selectform1" id="selectform1" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="selecttab" value="1"></form>
        <form name="selectform2" id="selectform2" action="' . $_SERVER['REQUEST_URI'] . '" method="post"><input type="hidden" name="selecttab" value="2"></form>
        <link href="../modules/' . $this->name . '/css.css" rel="stylesheet" type="text/css" />' . $form . $this->checkforupdates(0, 1);
    }

    public function displayFormCronJob()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Cron Job settings'),
                    'icon' => 'icon-cogs',
                ),
                'description' => $this->cronJobUrl(),
                'input' => array(
                    array(
                        'type' => 'date',
                        'label' => $this->l('Breakpoint date'),
                        'name' => 'NV_B_DATE',
                        'desc' => $this->l('Module will send voucher codes for users that subscribed to newsletter after this date. If you want to send voucher for all users, just setup some ancient date like 1970-01-01'),
                    ),
                ),
                'submit' => array('title' => $this->l('Save settings'),)
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = 'displayFormCronJob';
        $helper->submit_action = 'submitdisplayFormCronJob';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }

    public function displayFormMailTitle()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Email Settings'),
                    'icon' => 'icon-cogs'
                ),
                'description' => $this->l('Settings of email that module will send to customers'),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Email Title'),
                        'name' => 'nv_mtitle',
                        'lang' => true,
                        'desc' => $this->l('Define the title of the email that module will send to customers'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show dates in email with included time'),
                        'name' => 'nv_datetime',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                        'desc' => $this->l('Module includes voucher expiry date to email. You can decide if you want to show this date with time like: 1988-01-30 09:30:30 or just 1988-01-30'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array('title' => $this->l('Save settings'),)
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = 'displayFormMailTitleIdentifier';
        $helper->submit_action = 'submitdisplayFormMailTitle';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $title = array();
        foreach (Language::getLanguages(true) AS $value) {
            $title[$value['id_lang']] = Configuration::get('nv_mtitle', $value['id_lang']);
        }

        return array(
            'nv_mtitle' => Tools::getValue('nv_mtitle', $title),
            'NV_B_DATE' => Tools::getValue('NV_B_DATE', Configuration::get('NV_B_DATE')),
            'nv_datetime' => Tools::getValue('nv_datetime', Configuration::get('nv_datetime'))
        );
    }

}

class newslettervoucherUpdate extends newslettervoucher
{
    public static function inconsistency($return)
    {
        return true;
    }

    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3) {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2) {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1) {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0) {
            $version = (int)$version . "0000";
        }
        return (int)$version;
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function verify($module, $key, $version)
    {
        if (ini_get("allow_url_fopen")) {
            if (function_exists("file_get_contents")) {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&version=" . self::encrypt($version) . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }
}

if (file_exists(_PS_MODULE_DIR_ . 'newslettervoucher/lib/voucherengine/engine.php') && class_exists('newslettervoucher') && !class_exists('newslettervoucherVoucherEngine')) {
    require_once _PS_MODULE_DIR_ . 'newslettervoucher/lib/voucherengine/engine.php';
}

?>
