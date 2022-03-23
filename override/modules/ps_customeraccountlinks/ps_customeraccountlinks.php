<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Ps_CustomeraccountlinksOverride extends Ps_Customeraccountlinks
{
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $link = $this->context->link;

        $my_account_urls = [
            6 => [
                'title' => $this->trans('Contact with the store', [], 'Modules.Customeraccountlinks.Admin'),
                'url' => $link->getPageLink('contact', true, $this->context->cookie->id_lang),
            ],
            5 => [
                'title' => $this->trans('Favourite products', [], 'Modules.Customeraccountlinks.Admin'),
                'url' => $link->getModuleLink('blockwishlist','lists'),
            ],
            4 => [
                'title' => $this->trans('Order History', [], 'Modules.Customeraccountlinks.Admin'),
                'url' => $link->getPageLink('history', true, $this->context->cookie->id_lang),
            ],
            3 => [
                'title' => $this->trans('Addresses', [], 'Shop.Theme.Global'),
                'url' => $link->getPageLink('addresses', true, $this->context->cookie->id_lang),
            ],
            2 => [
                'title' => $this->trans('Personal data', [], 'Modules.Customeraccountlinks.Admin'),
                'url' => $link->getModuleLink('psgdpr','gdpr'),
            ],
            1 => [
                'title' => $this->trans('My cart', [], 'Shop.Theme.Global'),
                'url' => $link->getPageLink('cart', true, $this->context->cookie->id_lang),
            ],
        ];

        if (!$this->context->customer->logged) {
            $my_account_urls[0] = [
               'title' => $this->trans('Login', [], 'Modules.Customeraccountlinks.Admin'),
               'url' => $link->getPageLink('my-account', true),
            ];
        }

        // Sort Account links base in his index
        ksort($my_account_urls);

        return [
            'my_account_urls' => $my_account_urls,
            'logout_url' => $link->getPageLink('index', true, null, 'mylogout'),
        ];
    }
}
