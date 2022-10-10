<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SAS Comptoir du Code
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SAS Comptoir du Code is strictly forbidden.
 * In order to obtain a license, please contact us: contact@comptoirducode.com
 *
 * @author    Vincent - Comptoir du Code
 * @copyright Copyright(c) 2015-2022 SAS Comptoir du Code
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * @package   cdc_googletagmanager
 */

/**
 * Represent GTM google_tag_params (for dynamic remarketing)
 */
class Gtm_GoogleTagParams
{
	public $ecomm_pagetype;
	public $ecomm_prodid;
	public $ecomm_totalvalue;
	public $ecomm_category;

	public function removeNull()
	{
		$properties = get_object_vars($this);
		foreach ($properties as $p_key => $p_val) {
			if(is_null($p_val)) {
				unset($this->$p_key);
			}
		}
	}
}
