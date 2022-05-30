<?php

class Mail extends MailCore
{

	public static function Send($id_lang, $template, $subject, $template_vars, $to,
			$to_name = null, $from = null, $from_name = null, $file_attachment = null, $mode_smtp = null,
			$template_path = _PS_MAIL_DIR_, $die = false, $id_shop = null, $bcc = null, $reply_to = null)
	{
	
	$template_vars['{recieptorinvoice}'] = Order::getRecieptOrInvoice((int)Context::getContext()->cart->id);

 	return parent::Send($id_lang, $template, $subject, $template_vars, $to,
			$to_name, $from, $from_name, $file_attachment, $mode_smtp,
			$template_path, $die, $id_shop, $bcc, $reply_to);

	}

}

