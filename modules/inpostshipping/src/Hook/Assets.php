<?php
/**
 * Copyright 2021-2022 InPost S.A.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the EUPL-1.2 or later.
 * You may not use this work except in compliance with the Licence.
 *
 * You may obtain a copy of the Licence at:
 * https://joinup.ec.europa.eu/software/page/eupl
 * It is also bundled with this package in the file LICENSE.txt
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the Licence is distributed on an AS IS basis,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the Licence for the specific language governing permissions
 * and limitations under the Licence.
 *
 * @author    InPost S.A.
 * @copyright 2021-2022 InPost S.A.
 * @license   https://joinup.ec.europa.eu/software/page/eupl
 */

namespace InPost\Shipping\Hook;

use InPost\Shipping\Configuration\CheckoutConfiguration;
use InPost\Shipping\ShipX\Resource\Service;
use Media;
use ModuleFrontController;
use Tools;

class Assets extends AbstractHook
{
    const HOOK_LIST = [
        'actionAdminControllerSetMedia',
        'actionFrontControllerSetMedia',
        'displayAdminAfterHeader',
    ];

    const SUPERCHECKOUT_MODULE = 'supercheckout';

    protected $ordersDisplay;

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') === 'AdminOrders') {
            $display = $this->getOrdersDisplay();

            Media::addJsDef([
                'shopIs177' => $this->shopContext->is177(),
                'inPostLockerServices' => Service::LOCKER_SERVICES,
                'inPostLockerStandard' => Service::INPOST_LOCKER_STANDARD,
            ]);

            if ($display === 'view') {
                $assetsManager = $this->module->getAssetsManager();

                $assetsManager
                    ->registerJavaScripts([
                        $assetsManager::GEO_WIDGET_JS_URL,
                        'map.js',
                        'admin/tools.js',
                        'admin/common.js',
                        'admin/order-details.js',
                    ])
                    ->registerStyleSheets([
                        $assetsManager::GEO_WIDGET_CSS_URL,
                        'admin/orders.css',
                    ]);
            } elseif ($display === 'index') {
                $this->module->getAssetsManager()
                    ->registerJavaScripts([
                        'admin/tools.js',
                        'admin/order-list.js',
                    ]);
            }
        }

        if ($this->shouldDisplayLoader()) {
            $this->module
                ->getAssetsManager()
                ->registerStyleSheets(['admin/loader.css']);
        }
    }

    public function hookDisplayAdminAfterHeader()
    {
        return $this->shouldDisplayLoader()
            ? $this->module->display($this->module->name, 'views/templates/hook/loader.tpl')
            : '';
    }

    protected function shouldDisplayLoader()
    {
        return Tools::getValue('controller') === 'AdminOrders'
            && in_array($this->getOrdersDisplay(), ['index', 'view'])
            || isset($this->context->controller->module)
            && $this->context->controller->module === $this->module;
    }

    public function hookActionFrontControllerSetMedia()
    {
        if ($this->isCheckoutControllerContext()) {
            $assetsManager = $this->module->getAssetsManager();

            $assetsManager
                ->registerJavaScripts([$assetsManager::GEO_WIDGET_JS_URL], [
                    'position' => 'head',
                    'attributes' => 'async',
                ])
                ->registerJavaScripts([
                    'map.js',
                    $this->shopContext->is17() ? 'checkout17.js' : 'checkout16.js',
                ])
                ->registerStyleSheets([
                    $assetsManager::GEO_WIDGET_CSS_URL,
                    'front.css',
                ]);

            if ($scripts = $this->getModuleSpecificScriptFiles()) {
                $assetsManager->registerJavaScripts($scripts);
            }

            Media::addJsDef([
                'inPostAjaxController' => $this->context->link->getModuleLink($this->module->name, 'ajax'),
                'inPostLocale' => Tools::strtolower($this->context->language->iso_code) === 'pl' ? 'pl' : 'uk',
            ]);
        }
    }

    protected function getOrdersDisplay()
    {
        if (!isset($this->ordersDisplay)) {
            $this->ordersDisplay = $this->initOrdersDisplay();
        }

        return $this->ordersDisplay;
    }

    protected function initOrdersDisplay()
    {
        if ($this->shopContext->is177()) {
            switch (Tools::getValue('action')) {
                case 'vieworder':
                    return 'view';
                case 'addorder':
                    return 'create';
                default:
                    return 'index';
            }
        }

        if (Tools::isSubmit('vieworder')) {
            return 'view';
        } elseif (Tools::isSubmit('addorder')) {
            return 'create';
        }

        return 'index';
    }

    protected function isCheckoutControllerContext()
    {
        $controller = Tools::getValue('controller');

        if (in_array($controller, ['order', 'orderopc'])) {
            return true;
        }

        if ($this->context->controller instanceof ModuleFrontController) {
            switch ($this->context->controller->module->name) {
                case self::SUPERCHECKOUT_MODULE:
                    return 'supercheckout' === $this->getModuleControllerName($controller);
                default:
                    return $this->isCustomCheckoutController(
                        $this->context->controller->module->name,
                        $controller
                    );
            }
        }

        return false;
    }

    protected function getModuleControllerName($controller)
    {
        $parts = explode('-', $controller);

        return end($parts);
    }

    protected function isCustomCheckoutController($moduleName, $controller)
    {
        /** @var CheckoutConfiguration $configuration */
        $configuration = $this->module->getService('inpost.shipping.configuration.checkout');

        if ($configuration->isUsingCustomCheckoutModule()) {
            $controllers = $configuration->getCustomCheckoutControllers();

            return isset($controllers[$moduleName])
                && in_array(
                    $this->getModuleControllerName($controller),
                    $controllers[$moduleName]
                );
        }

        return false;
    }

    protected function getModuleSpecificScriptFiles()
    {
        if ($this->context->controller instanceof ModuleFrontController) {
            switch ($this->context->controller->module->name) {
                case self::SUPERCHECKOUT_MODULE:
                    return ['modules/supercheckout.js'];
                default:
                    return [];
            }
        }

        return [];
    }
}
