<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony;

use ASoftwareHouse\EParagony\Entity\EparagonyDocumentStatus;
use Configuration;
use Context;
use Mail;
use PrestaShop\PrestaShop\Adapter\Entity\Address;
use PrestaShop\PrestaShop\Adapter\Entity\Customer;
use PrestaShop\PrestaShop\Adapter\Entity\Language;
use PrestaShop\PrestaShop\Adapter\Entity\Order;

class Mailer
{
    public function mailReceipt(EparagonyDocumentStatus $documentStatus)
    {
        $orderId = $documentStatus->getOrderId();
        $order = new Order($orderId);
        $orderLanguage = new Language($order->id_lang);
        $address = new Address($order->id_address_invoice);
        $customer = new Customer($order->id_customer);

        $template = 'eparagon';
        $templatePath = _PS_MODULE_DIR_ . 'eparagony/mails';
        $subject = Context::getContext()->getTranslator()->trans(
            'Order Receipt',
            [],
            'Modules.Eparagony.Eparagony',
            $orderLanguage->locale
        );

        $send = Mail::Send(
            $orderLanguage->id,
            $template,
            $subject,
            array(
                '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                '{order_name}' => $order->getUniqReference(),
                '{firstname}' => $address->firstname,
                '{receipt_url}' => $documentStatus->extractRestField('url'),
                '{spark_img}' => MagicBox::getModuleLink('img', ['img' => 'spark']),
            ),
            $customer->email, /* receiver email address */
            $address->firstname . ' ' . $address->lastname, /* receiver name */
            null, /* from email address, the null will be set to shop one */
            null, /* from name, the null will be set to shop one */
            null, /* file attachment */
            null, /* legacy (mode_smtp) */
            $templatePath
        );

        return (bool)$send;
    }
}
