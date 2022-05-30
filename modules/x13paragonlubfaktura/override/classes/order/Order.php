<?php

class Order extends OrderCore {
	
	public static function getRecieptOrInvoice($id_cart) {
		$sql = '
			SELECT `recieptorinvoice`
			FROM `'._DB_PREFIX_.'x13recieptorinvoice`
			WHERE `id_cart` = '.(int)$id_cart.'
		';
		$value = Db::getInstance()->getValue($sql);
		switch($value) {
			case 'reciept': return 'paragon';
			case 'invoice': return 'faktura';
			default: return 'nieokreślony';
		}
	}
	
}
