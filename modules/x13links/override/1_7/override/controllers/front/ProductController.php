<?php
class ProductController extends ProductControllerCore
{
    public function canonicalRedirection($canonical_url = '')
    {
        if (version_compare(_PS_VERSION_, '1.7.6', '>') === true && version_compare(_PS_VERSION_, '1.7.6.5', '<') === true) {
            if (Validate::isLoadedObject($this->product)) {
                $idProductAttribute = Tools::getValue('id_product_attribute', null);
                if (!$this->product->hasCombinations() || !$this->isValidCombination($idProductAttribute, $this->product->id)) {
                    //Invalid combination we redirect to the canonical url (without attribute id)
                    unset($_GET['id_product_attribute']);
                    $idProductAttribute = null;
                }

                // If the attribute id is present in the url we use it to perform the redirection, this will fix any domain
                // or rewriting error and redirect to the appropriate url
                // If the attribute is not present or invalid, we set it to null so that the request is redirected to the
                // real canonical url (without any attribute)
                FrontController::canonicalRedirection($this->context->link->getProductLink(
                    $this->product,
                    null,
                    null,
                    null,
                    null,
                    null,
                    $idProductAttribute
                ));
            }
        }

        return parent::canonicalRedirection($canonical_url);
    }

    public function getTemplateVarProduct()
    {
        $productSettings = $this->getProductPresentationSettings();
        $extraContentFinder = new PrestaShop\PrestaShop\Core\Product\ProductExtraContentFinder();
        $product = $this->objectPresenter->present($this->product);
        $product['id_product'] = (int) $this->product->id;
        $product['out_of_stock'] = (int) $this->product->out_of_stock;
        $product['new'] = (int) $this->product->new;
        if (Tools::getValue('idpa','false')){
			if (version_compare(_PS_VERSION_, '1.7.5', '>=') === true) {
				$product['id_product_attribute'] = $this->getIdProductAttributeByRequestOrGroup();
			} else {
				$product['id_product_attribute'] = $this->getIdProductAttribute(true);
			}
        } else {
            $product['id_product_attribute'] = Tools::getValue('idpa');
        }
		
        $product['minimal_quantity'] = $this->getProductMinimalQuantity($product);
        $product['quantity_wanted'] = $this->getRequiredQuantity($product);
        $product['extraContent'] = $extraContentFinder->addParams(array('product' => $this->product))->present();
        $product_full = Product::getProductProperties($this->context->language->id, $product, $this->context);
        $product_full = $this->addProductCustomizationData($product_full);
        $product_full['show_quantities'] = (bool) (
            Configuration::get('PS_DISPLAY_QTIES')
            && Configuration::get('PS_STOCK_MANAGEMENT')
            && $this->product->quantity > 0
            && $this->product->available_for_order
            && !Configuration::isCatalogMode()
        );
        $product_full['quantity_label'] = ($this->product->quantity > 1) ? $this->trans('Items', array(), 'Shop.Theme.Catalog') : $this->trans('Item', array(), 'Shop.Theme.Catalog');
        $product_full['quantity_discounts'] = $this->quantity_discounts;
        if ($product_full['unit_price_ratio'] > 0) {
            $unitPrice = ($productSettings->include_taxes) ? $product_full['price'] : $product_full['price_tax_exc'];
            $product_full['unit_price'] = $unitPrice / $product_full['unit_price_ratio'];
        }
        $group_reduction = GroupReduction::getValueForProduct($this->product->id, (int) Group::getCurrent()->id);
        if ($group_reduction === false) {
            $group_reduction = Group::getReduction((int) $this->context->cookie->id_customer) / 100;
        }
        $product_full['customer_group_discount'] = $group_reduction;
        $presenter = $this->getProductPresenter();
        return $presenter->present(
            $productSettings,
            $product_full,
            $this->context->language
        );
    }
	
    private function getIdProductAttribute($useGroups = false)
    {
		if (version_compare(_PS_VERSION_, '1.7.4', '>=')) {
			$requestedIdProductAttribute = (int) Tools::getValue('id_product_attribute');

			if ($useGroups === true) {
				$groups = Tools::getValue('group');

				if (!empty($groups)) {
					$requestedIdProductAttribute = (int) Product::getIdProductAttributesByIdAttributes(
						$this->product->id,
						$groups
					);
				}
			}

			if (!Configuration::get('PS_DISP_UNAVAILABLE_ATTR')) {
				$productAttributes = array_filter(
					$this->product->getAttributeCombinations(),
					function ($elem) {
						return $elem['quantity'] > 0;
					}
				);
				$productAttribute = array_filter(
					$productAttributes,
					function ($elem) use ($requestedIdProductAttribute) {
						return $elem['id_product_attribute'] == $requestedIdProductAttribute;
					}
				);

				if (empty($productAttribute) && !empty($productAttributes)) {
					return (int)array_shift($productAttributes)['id_product_attribute'];
				}
			}
		} else {
			$requestedIdProductAttribute = (int)Tools::getValue('idpa');
			if (!Configuration::get('PS_DISP_UNAVAILABLE_ATTR')) {
				$productAttributes = array_filter(
					$this->product->getAttributeCombinations(),
					function ($elem) {
						return $elem['quantity'] > 0;
					});
				$productAttribute = array_filter(
					$productAttributes,
					function ($elem) use ($requestedIdProductAttribute) {
						return $elem['id_product_attribute'] == $requestedIdProductAttribute;
					});
				if (empty($productAttribute) && !empty($productAttributes)) {
					return (int)array_shift($productAttributes)['id_product_attribute'];
				}
			}
		}
		
        return $requestedIdProductAttribute;
    }
	
    /**
     * Assign price and tax to the template.
     */
    protected function assignPriceAndTax()
    {
        $id_customer = (isset($this->context->customer) ? (int) $this->context->customer->id : 0);
        $id_group = (int) Group::getCurrent()->id;
        $id_country = $id_customer ? (int) Customer::getCurrentCountry($id_customer) : (int) Tools::getCountry();
        $tax = (float) $this->product->getTaxesRate(new Address((int) $this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
        $this->context->smarty->assign('tax_rate', $tax);
        $product_price_with_tax = Product::getPriceStatic($this->product->id, true, null, 6);
        if (Product::$_taxCalculationMethod == PS_TAX_INC) {
            $product_price_with_tax = Tools::ps_round($product_price_with_tax, 2);
        }
        $id_currency = (int) $this->context->cookie->id_currency;
        $id_product = (int) $this->product->id;
        $id_product_attribute = Tools::getValue('id_product_attribute', null);
        $id_shop = $this->context->shop->id;
        $quantity_discounts = SpecificPrice::getQuantityDiscounts($id_product, $id_shop, $id_currency, $id_country, $id_group, $id_product_attribute, false, (int) $this->context->customer->id);
        foreach ($quantity_discounts as &$quantity_discount) {
            if ($quantity_discount['id_product_attribute']) {
                $combination = new Combination((int) $quantity_discount['id_product_attribute']);
                $attributes = $combination->getAttributesName((int) $this->context->language->id);
                foreach ($attributes as $attribute) {
                    $quantity_discount['attributes'] = $attribute['name'].' - ';
                }
                $quantity_discount['attributes'] = rtrim($quantity_discount['attributes'], ' - ');
            }
            if ((int) $quantity_discount['id_currency'] == 0 && $quantity_discount['reduction_type'] == 'amount') {
                $quantity_discount['reduction'] = Tools::convertPriceFull($quantity_discount['reduction'], null, Context::getContext()->currency);
            }
        }
        $product_price = $this->product->getPrice(Product::$_taxCalculationMethod == PS_TAX_INC, false);
        $this->quantity_discounts = $this->formatQuantityDiscounts($quantity_discounts, $product_price, (float) $tax, $this->product->ecotax);
        $this->context->smarty->assign(array(
            'no_tax' => Tax::excludeTaxeOption() || !$tax,
            'tax_enabled' => Configuration::get('PS_TAX') && !Configuration::get('AEUC_LABEL_TAX_INC_EXC'),
            'customer_group_without_tax' => Group::getPriceDisplayMethod($this->context->customer->id_default_group),
        ));
    }	
	
    /**
     * Return id_product_attribute by id_product_attribute request parameter
     * or by the group request parameter.
     *
     * @return int|null
     *
     * @throws PrestaShopException
     */
    private function getIdProductAttributeByRequestOrGroup()
    {
        $requestedIdProductAttribute = (int) Tools::getValue('id_product_attribute');

        $groupIdProductAttribute = $this->getIdProductAttributeByGroup();
        $requestedIdProductAttribute = null !== $groupIdProductAttribute ? $groupIdProductAttribute : $requestedIdProductAttribute;

        return $this->tryToGetAvailableIdProductAttribute($requestedIdProductAttribute);
    }

    /**
     * Return id_product_attribute by the group request parameter.
     *
     * @return int|null
     *
     * @throws PrestaShopException
     */
    private function getIdProductAttributeByGroup()
    {
        $groups = Tools::getValue('group');
        if (empty($groups)) {
            return null;
        }

        return (int) Product::getIdProductAttributeByIdAttributes(
            $this->product->id,
            $groups,
            true
        );
    }

    /**
     * If the PS_DISP_UNAVAILABLE_ATTR functionality is enabled, this method check
     * if $checkedIdProductAttribute is available.
     * If not try to return the first available attribute, if none are available
     * simply returns the input.
     *
     * @param int $checkedIdProductAttribute
     *
     * @return int
     */
    private function tryToGetAvailableIdProductAttribute($checkedIdProductAttribute)
    {
        if (!Configuration::get('PS_DISP_UNAVAILABLE_ATTR')) {
            $availableProductAttributes = array_filter(
                $this->product->getAttributeCombinations(),
                function ($elem) {
                    return $elem['quantity'] > 0;
                }
            );

            $availableProductAttribute = array_filter(
                $availableProductAttributes,
                function ($elem) use ($checkedIdProductAttribute) {
                    return $elem['id_product_attribute'] == $checkedIdProductAttribute;
                }
            );

            if (empty($availableProductAttribute) && count($availableProductAttributes)) {
                return (int) array_shift($availableProductAttributes)['id_product_attribute'];
            }
        }

        return $checkedIdProductAttribute;
    }
}
