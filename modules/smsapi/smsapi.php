<?php
/**
 * SMSAPI moduł
 * @prestashop
 * Version 1.0
 *  @author Europasaz.pl <biuro@europasaz.pl
 *  @copyright 2014 Europasaz.pl
 *  @license   http://pl.wikipedia.org/wiki/GNU_General_Public_License
 */

class smsapi extends Module
{
    const CHARSET = 'UTF-8';
    const REPLACE_FLAGS = ENT_COMPAT;
    private $_html;
    public function __construct()
    {
        $this->name = 'smsapi';
        $this->tab = 'analytics_stats';
        $this->version = '1.7';
        $this->author = 'europasaz.pl';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7');
         if (configuration::get('PS_VERSION_DB') > '1.6.9.9') {
            $this->shopv = 1;
        }else{
            $this->shopv = 0;
        }
        parent::__construct();
        $this->displayName = $this->l('SmsAPI');
        $this->description = $this->l('send sms module');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
    } //construct
    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);
        $id_lang = configuration::get('PS_LANG_DEFAULT');
        $order_states = OrderState::getOrderStates($id_lang);
        foreach ($order_states as $i => $value)
        {
            if (!parent::install() or !$this->registerHook('displayAdminOrder') or !$this->registerHook('adminOrder') or !$this->registerHook('postUpdateOrderStatus') or !Configuration::updateValue('SMSID', 'login SMSAPI') or !Configuration::updateValue('SMSPASS', 'hasło md5 SMSAPI') or !Configuration::updateValue('SMSSENDER',
                'Info') or !Configuration::updateValue('SMSECO', '1') or !Configuration::updateValue('SMSTEST', '0') or !Configuration::updateValue('SMSFLASH', 'FALSE') or !Configuration::updateValue('SMSORDC', '1') or !Configuration::updateValue('SMSORDA', '1') or !Configuration::updateValue('SMSTXTA' . $value['id_order_state'],
                '0') or !Configuration::updateValue('SMSTXT' . $value['id_order_state'], 'Witaj {$FIRST} {$LAST}. Twoje zamówienie o numerze {$ORDERID} i wartości  {$VALUE} zmieniło status. Wysłaliśmy email do {$MAIL}. Pozdrowienia {$SHOP}'))
                return false;
            return true;
        }
    }
    public function uninstall()
    {
        $id_lang = configuration::get('PS_LANG_DEFAULT');
        $order_states = OrderState::getOrderStates($id_lang);
        foreach ($order_states as $i => $value)
        {
            if (!parent::uninstall() or !Configuration::deleteByName('SMSID') or !Configuration::deleteByName('SMSPASS') or !Configuration::deleteByName('SMSSENDER') or !Configuration::deleteByName('SMSORDC') or !Configuration::deleteByName('SMSORDA') or !Configuration::deleteByName('SMSECO') or !Configuration::deleteByName
                ('SMSTEST') or !Configuration::deleteByName('SMSFLASH') or !Configuration::deleteByName('SMSTXTA' . $value['id_order_state']) or !Configuration::deleteByName('SMSTXT' . $value['id_order_state']))
                return false;
            return true;
        }
    }

    public function getContent()
    {
        //newform
        if (Tools::isSubmit('submit'))
        {
            Configuration::updateValue('SMSID', $_POST['SMSID']);
            Configuration::updateValue('SMSPASS', $_POST['SMSPASS']);
            Configuration::updateValue('SMSSENDER', $_POST['SMSSENDER']);
            //Configuration::updateValue('SMSORDC', $_POST['SMSORDC']);
            Configuration::updateValue('SMSORDER', $_POST['SMSORDER']);
            Configuration::updateValue('SMSECO', $_POST['SMSECO']);
            Configuration::updateValue('SMSFLASH', $_POST['SMSFLASH']);
            Configuration::updateValue('SMSPASS', $_POST['SMSPASS']);
            Configuration::updateValue('SMSTEST', $_POST['SMSTEST']);
            $id_lang = configuration::get('PS_LANG_DEFAULT');
            $order_states = OrderState::getOrderStates($id_lang);
            foreach ($order_states as $i => $value)
            {
                Configuration::updateValue('SMSTXTA' . $value['id_order_state'] . '', $_POST['SMSTXTA' . $value['id_order_state']]);
                Configuration::updateValue('SMSTXT' . $value['id_order_state'] . '', $_POST['SMSTXT' . $value['id_order_state']]);
            }
        }
        $this->_drawFormssms();
        return $this->_html;
    } //contenT
    private function _drawFormssms()
    {
        $this->_html .= '<div class="panel">';
        $this->_html .= $this->_drawSettingsFormssms();
        $this->_html .= $this->_updateButtonssms();
    }
    private function _updateButtonssms()
    {
        $_html = '';
        $_html .= '<p class="center" style="background: none repeat scroll 0pt 0pt rgb(255, 255, 240); border: 1px solid rgb(223, 213, 195); padding: 10px; margin-top: 10px;clear:both;">
					<input type="submit" name="submit" value="' . $this->l('Update settings') . '" 
                		   class="button"  />
                	</p>';

        $_html .= '</form></div></div>';

        return $_html;
    }
    private function _drawSettingsFormssms()
    {
        $this->uri = ToolsCore::getCurrentUrlProtocolPrefix() . $this->context->shop->domain_ssl . $this->context->shop->physical_uri;
        $imgsms = $this->uri . '/modules/smsapi/smsapi.png';
        $imgsms2 = $this->uri . '/modules/smsapi/europasaz.png';
        $_html = '';
        $_html .= '<div class="row"><div class="panel-heading">' . $this->l('General settings:') . '</div>';
        $_html .= '
    	<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        $_html .= '
        <fieldset"><div class="row">
        <div class="info" style="width:45%;float:right;">
	   	<a href="http://www.smsapi.pl/" target="_blank"><img src="' . $imgsms . '"/></a>
	   	<a href="http://europasaz.pl/" target="_blank"><img src="' . $imgsms2 . '"/></a>
	   	<p style="clear: both;margin-top:60px;float:none;display:block;"></p>
		<a href="mailto:sprzedaz@smsapi.pl">' . $this->l('Individual offer: sprzedaz@smsapi.pl') . '</a>
		<p style="clear: both;margin-top:60px;float:none;display:block;"></p>
	   	<h3>' . $this->l('Instructions:') . '</h3>
	   	<br/>' . $this->l('1. Create an account on: http://smsapi.pl ') . '
	   	<br/>' . $this->l('2. Enter yours SMSAPI login ') . '
	   	<br/>' . $this->l('3. Enter password in md5 - important ') . '
	   	<br/>' . $this->l('4. SMS sender - not allowed in eco mode. Supported only if you have registered name in smsapi. Default - Info') . '
	   	<br/>' . $this->l('5. Eco - cheapest but slow method.') . '
	   	<br/>' . $this->l('6. Test mode - When set to 1 - SMS is not send. Set to 0 in production mode') . '
	   	<br/>' . $this->l('7. Flash sms mode - allowed only in premium mode. ') . '
	    <p style="clear: both;margin-top:30px;float:none;display:block;"></p>
	    <br/><h3>' . $this->l('Variables:') . '</h3>
	    <br/>' . $this->l('1. {$FIRST} - Customer name.') . '
	    <br/>' . $this->l('2. {$LAST} - Customer last name.') . '
	    <br/>' . $this->l('3. {$ORDERID} - Id of shop order.') . '
	    <br/>' . $this->l('4. {$VALUE} - Total anmount of order without currency.') . '
	    <br/>' . $this->l('5. {$MAIL} - Customer email.') . '
	    <br/>' . $this->l('6. {$SHOP} - Shop name.') . '
        <br/>' . $this->l('7. {$TRACK} - Tracking number.') . '
         <br/>' . $this->l('8. {$CARRIER} - Carrier name.') . '
          <br/>' . $this->l('9. {$REFERENCE} - Order reference.') . '
	    </div>
    	<div style="width:50%;float:left;">';
        $_html .= '
    	<label>' . $this->l('Login SMSAPI:') . '</label>
        <legend>' . $this->l('Enter yours login to SMSAPI.') . ' </legend>
        <div class="margin-form">
            <input type="text" name="SMSID" value="' . Configuration::get('SMSID') . '" size="40">
            </div>
        </br>
    	<label>' . $this->l('Password:') . '</label>
        <legend>' . $this->l('Enter password to SMSAPI [MD5].') . ' </legend>
        <div class="margin-form">
            <input type="text" name="SMSPASS" value="' . Configuration::get('SMSPASS') . '" size="40">
            </div>
        </br>
        <label>' . $this->l('Sender name:') . '</label>
        <legend>' . $this->l('www.mysite.com') . ' </legend>
        <div class="margin-form">
            <input type="text" name="SMSSENDER" value="' . Configuration::get('SMSSENDER') . '" size="40">
            </div>
        </br>
        <label>' . $this->l('Eco:') . '</label>
        <legend>' . $this->l('Eco VALUE 0 OR 1') . ' </legend>
        <div class="margin-form">
            <input type="text" name="SMSECO" value="' . Configuration::get('SMSECO') . '" size="10">
            </div>
        </br>
         <label>' . $this->l('Flash SMS:') . '</label>
        <legend>' . $this->l('Flash sms on phone display VALUE FALSE OR TRUE') . ' </legend>
        <div class="margin-form">
            <input type="text" name="SMSFLASH" value="' . Configuration::get('SMSFLASH') . '" size="10">
            </div>
        </br>
         <label>' . $this->l('Test mode:') . '</label>
        <legend>' . $this->l('Debug mode to test only. Value 0 - non active. 1 - active') . ' </legend>
        <div class="margin-form">
            <input type="text" name="SMSTEST" value="' . Configuration::get('SMSTEST') . '" size="10">
            </div>
        </br>
          <label>' . $this->l('Default message in order:') . '</label>
        <legend>' . $this->l('Default message for field in order') . ' </legend>
        <div class="margin-form">
            <textarea name="SMSORDER" rows="10" cols="60">' . Configuration::get('SMSORDER') . ' </textarea>
            </div>
        </br>
        ';
        $id_lang = configuration::get('PS_LANG_DEFAULT');
        $order_states = OrderState::getOrderStates($id_lang);
        foreach ($order_states as $i => $value)
        {
            $_html .= '
		<HR><label>' . $this->l('Active:') . '</label>
        <div class="margin-form">
            <legend>' . $this->l('ACTIVE: Value 0 - non active. 1 - active') . ' </legend>
            <input type="text" name="SMSTXTA' . $value['id_order_state'] . '" value="' . Configuration::get('SMSTXTA' . $value['id_order_state']) . '" size="5">
            </div>
        </br>
        <label>' . $this->l('Text of message:') . '</label>
        <legend>' . $value['name'] . ' </legend>
        <div class="margin-form">
            <textarea name="SMSTXT' . $value['id_order_state'] . '"  " rows="10" cols="100">' . Configuration::get('SMSTXT' . $value['id_order_state']) . '</textarea>
            </div>
        </br>
        ';
        }
        $_html .= '</fieldset></div>';

        return $_html;

    }
    public function hookpostUpdateOrderStatus($params)
    {
        $this->uri = ToolsCore::getCurrentUrlProtocolPrefix() . $this->context->shop->domain_ssl . $this->context->shop->physical_uri;
        $get_file_url = $this->uri . '/modules/smsapi/lib.php';
        $Order = new Order($params['id_order']);
        $customer = new Customer($Order->id_customer);
        $address = new Address($Order->id_address_invoice);

        /**
         * @var $newOrderStatus OrderState
         */
        $newOrderStatus = $params['newOrderStatus'];
        $id_order_state = $newOrderStatus->id;

        $order_active = (int)Configuration::get('SMSTXTA' . $id_order_state);
        if ($order_active == 1)
        {
            $last = $address->lastname;
            $first = $address->firstname;
            $mail = $customer->email;
            $id_order = $params['id_order'];
            $val = Db::getInstance()->getRow('SELECT `reference`, `total_paid_tax_incl`, `shipping_number`, `id_carrier` FROM `' . _DB_PREFIX_ . 'orders` WHERE `id_order` = ' . $id_order);
            $carrier = Db::getInstance()->getRow('SELECT `name` FROM `' . _DB_PREFIX_ . 'carrier` WHERE `id_carrier` = ' . $val['id_carrier']);

            if (empty($address->phone_mobile)) {
                $phone = $address->phone;
            } else {
                $phone = $address->phone_mobile;
            }

            $senddata = curl_init();
            $fields = array(
                'SMSID' => Configuration::get('SMSID'),
                'SMSPASS' => Configuration::get('SMSPASS'),
                'FROM' => Configuration::get('SMSSENDER'),
                'SMSECO' => Configuration::get('SMSECO'),
                'SMSFLASH' => Configuration::get('SMSFLASH'),
                'SMSTEST' => Configuration::get('SMSTEST'),
                'SMSTXT' => Configuration::get('SMSTXT' . $id_order_state),
                'PHONE' => $phone,
                'FIRST' => $first,
                'LAST' => $last,
                'MAIL' => $mail,
                'ORDERID' => $id_order,
                'REFERENCE' => $val['reference'],
                'VALUE' => $val['total_paid_tax_incl'],
                'TRACK' => $val['shipping_number'],
                'CARRIER' => $carrier['name'],
            );

            $postvars = '';
            foreach ($fields as $key => $value)
            {
                $postvars .= $key . "=" . $value . "&";
            }

            curl_setopt($senddata, CURLOPT_URL, $get_file_url);
            curl_setopt($senddata, CURLOPT_POST, count($fields));
            curl_setopt($senddata, CURLOPT_POSTFIELDS, $postvars);
            curl_exec($senddata);
            curl_close($senddata);
        }
    }
     public function hookDisplayAdminOrder($params)
    {
        $order = new Order($params['id_order']);
        $id_order = (int)$order->id;
        
        $this->uri = ToolsCore::getCurrentUrlProtocolPrefix() . $this->context->shop->domain_ssl . $this->context->shop->physical_uri;
        $get_file_url = $this->uri . '/modules/smsapi/';
        
                
        $this->context->smarty->assign('module_dir', $get_file_url);
        $this->context->smarty->assign('SMSID', Configuration::get('SMSID'));
        $this->context->smarty->assign('SMSPASS', Configuration::get('SMSPASS'));
        $this->context->smarty->assign('SMSSENDER', Configuration::get('SMSSENDER'));
        $this->context->smarty->assign('SMSECO', Configuration::get('SMSECO'));
        $this->context->smarty->assign('SMSFLASH', Configuration::get('SMSFLASH'));
        $this->context->smarty->assign('SMSTEST', Configuration::get('SMSTEST'));
        $this->context->smarty->assign('SMSORDER', Configuration::get('SMSORDER'));
        
            $customer = new Customer($order->id_customer);
            $address = new Address($order->id_address_invoice);
            
            $last = $address->lastname;
            $first = $address->firstname;
            $mail = $this->context->customer->email;
            $val = Db::getInstance()->getRow('SELECT `reference`, `total_paid_tax_incl`, `shipping_number`, `id_carrier` FROM `' . _DB_PREFIX_ . 'orders` WHERE `id_order` = ' . $id_order);
            $carrier = Db::getInstance()->getRow('SELECT `name` FROM `' . _DB_PREFIX_ . 'carrier` WHERE `id_carrier` = ' . $val['id_carrier']);
            if (empty($address->phone_mobile))
            {
                $phone = $address->phone;
            } else
            {
                $phone = $address->phone_mobile;
            }
        $this->context->smarty->assign('PHONE', $phone);
        $this->context->smarty->assign('FIRST', $first);
        $this->context->smarty->assign('LAST', $last);
        $this->context->smarty->assign('MAIL', $mail);
        $this->context->smarty->assign('ORDERID', $id_order);
        $this->context->smarty->assign('REFERENCE', $val['reference']);
        $this->context->smarty->assign('VALUE', $val['total_paid_tax_incl']);
        $this->context->smarty->assign('TRACK', $val['shipping_number']);
        $this->context->smarty->assign('CARRIER', $carrier['name']);
            
        if ($this->shopv == 1) {
            $outputorder = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/order17.tpl');
        }else{
            $outputorder = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/order.tpl');
        }
        return $outputorder;
    }
} //END

?>