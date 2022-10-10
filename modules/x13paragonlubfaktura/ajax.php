<?php
require_once dirname(__FILE__) . '/../../config/config.inc.php';
require_once dirname(__FILE__).'/x13paragonlubfaktura.php';

if (Tools::getValue('data')) {
    $data = Tools::getValue('data');
    if ($data['method'] == 'setForCart') {
        if ((int) $data['data']['id_cart'] > 0 && in_array($data['data']['recieptorinvoice'], array('reciept', 'invoice', 'none'))) {
            $result = x13paragonlubfaktura::setForCart((int) $data['data']['id_cart'], $data['data']['recieptorinvoice']);
            die(
                json_encode(
                    array(
                        array(
                            'hasError' => !$result,
                            'status' => 'success',
                        )
                    )
                )
            );
        }
    }
}
