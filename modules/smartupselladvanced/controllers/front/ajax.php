<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   Addons PrestaShop license limitation
 * @see       /LICENSE
 *
 * International Registered Trademark & Property of INVERTUS, UAB
 */

use Invertus\SmartUpsellAdvanced\Repository\ProductRepository;
use Invertus\SmartUpsellAdvanced\Repository\SpecialOfferCartRelationRepository;

/**
 * @property SmartUpsellAdvanced $module
 */
class SmartUpsellAdvancedAJAXModuleFrontController extends ModuleFrontController
{

    /**
     * SmartUpsellAdvancedAJAXModuleFrontController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if token is valid
     * @return bool
     */
    public function checkAccess()
    {
        if (!$this->isTokenValid()) {
            $result = [
                'error' => $this->module->l('Security token is invalid. ', 'ajax'),
            ];
            die(json_encode($result));
        }

        return parent::checkAccess();
    }

    /**
     * Direct AJAX call to the right function
     */
    public function postProcess()
    {
        if (!$this->isXmlHttpRequest()) {
            return;
        }

        $action = Tools::getValue('action');

        if ('get_product_price' === $action) {
            $this->processGetProductPrice();
        }

        if ('add_to_cart' === $action) {
            $this->processAddToCart();
        }

        if ('get_upsell_modal_product_price' === $action) {
            $this->processGetUpsellModalProductPrice();
        }

        if ('add_to_cart_upsell_modal' === $action) {
            $this->processAddToCartUpsellModal();
        }

        if ('is_product_in_stock' === $action) {
            $this->processIsProductInStock();
        }

        if ('get_upsell_product_price' === $action) {
            $this->processGetUpsellProductPrice();
        }
    }

    /**
     * Return product price
     */
    private function processGetProductPrice()
    {
        $specialOfferHelper = $this->module->getService('smartupselladvanced.helper.specialoffer');
        $productId = Tools::getValue('productId');
        $specialOfferId = Tools::getValue('specialOfferId');
        $specialProductId = Tools::getValue('specialProductId');
        $dropdownAttributeId = Tools::getValue('dropdownAttributeId');
        $radioAttributeId = Tools::getValue('radioAttributeId');
        $attributeIds = $this->mergeAtrributes($dropdownAttributeId, $radioAttributeId);
        $productAttributeId = null;
        $isOutOfStock = false;

        if (!empty($attributeIds)) {
            $productAttributeId = Product::getIdProductAttributeByIdAttributes($specialOfferId, $attributeIds);
        }

        $discount = $specialOfferHelper->getSpecialOfferDiscountReadable(
            $productId,
            $specialProductId,
            $attributeIds
        );
        $fullPrice = $specialOfferHelper->getSpecialOfferPriceReadable($specialOfferId, $attributeIds);

        $minimalQuantity = $this->getProductMinimalQuantity($specialOfferId, $productAttributeId);

        $productStockQuantity = Product::getQuantity($specialOfferId, $productAttributeId);

        if ($productStockQuantity < $minimalQuantity) {
            $productStockQuantity = 0;
        }

        if ($productStockQuantity <= 0) {
            $isOutOfStock = true;
        }

        $customerId = $this->context->customer->id;
        if ($customerId === null) {
            $customerId = $this->context->customer->id_guest;
        }

        $result = [
            'full_price' => $fullPrice,
        ];

        if (!empty(SpecialOfferCartRelationRepository::isUsed($customerId, $specialOfferId))) {
            $result = [
                'full_price' => $fullPrice,
                'discount' => $discount,
                'is_out_of_stock' => $isOutOfStock,
            ];
        }

        die(json_encode($result));
    }

    /**
     * Add product to the cart
     */
    private function processAddToCart()
    {
        /** @var \Invertus\SmartUpsellAdvanced\Helper\CartHelper $cartHelper */
        $cartHelper = $this->module->getService('smartupselladvanced.helper.cart');
        $specialOfferHelper = $this->module->getService('smartupselladvanced.helper.specialoffer');
        $specialProductQuantity = 1;
        $productId = Tools::getValue('productId');
        $specialOfferProductId = Tools::getValue('specialOfferId');
        $dropdownAttributeId = Tools::getValue('dropdownAttributeId');
        $radioAttributeId = Tools::getValue('radioAttributeId');
        $specialOfferId = SpecialOfferCartRelationRepository::getSpecialOfferId($productId);
        $attributeIds = $this->mergeAtrributes($dropdownAttributeId, $radioAttributeId);
        $productAttributeId = null;
        $mainProductAttributeId = Product::getDefaultAttribute($productId);
        $isProductDeleted = false;
        $customerId = $this->context->customer->id;
        if ($customerId === null) {
            $customerId = $this->context->customer->id_guest;
        }

        if (!empty($attributeIds)) {
            $productAttributeId = Product::getIdProductAttributeByIdAttributes($specialOfferProductId, $attributeIds);
        } else {
            $productAttributeId = Product::getDefaultAttribute($specialOfferProductId);
        }

        $specialOfferDiscount = $specialOfferHelper->getSpecialOfferDiscountReadable(
            $productId,
            $specialOfferProductId,
            $attributeIds
        );

        //if special offer is not cross sell then remove main product
        if (!$specialOfferHelper->isCrossSell($productId)) {
            $isProductDeleted = $this->context->cart->deleteProduct(
                $productId,
                $mainProductAttributeId
            );
        }

        $isProductWithSpecificPriceRelation = $specialOfferDiscount != 0
            && empty(SpecialOfferCartRelationRepository::getSpecialOfferRelation(
                $specialOfferId[0]['id_special_offer'],
                $customerId
            ));
        $specificPrice = new SpecificPrice();
        $specificPrice->id_currency = 0;

        if ($isProductWithSpecificPriceRelation) {
            $specificPriceId = $cartHelper->addSpecificPrice(
                (int)$specialOfferProductId,
                0,
                (float)$specialOfferDiscount['product_discount'],
                $specialOfferDiscount['discount_type'],
                $this->context->cart->id,
                $this->context->shop->getContextualShopId(),
                0,
                $this->context->country->id
            );

            // Adds special price relation to database
            SpecialOfferCartRelationRepository::saveSpecialOfferRelation(
                $specialOfferId[0]['id_special_offer'],
                $this->context->cart->id,
                $customerId,
                $specificPriceId
            );
        } else {
            $specificPriceRelations = SpecialOfferCartRelationRepository::getSpecialOfferRelation(
                $specialOfferId[0]['id_special_offer'],
                $customerId
            );

            $cartHelper->updateSpecificPrice(
                (int)$specificPriceRelations[0]['id_specific_price'],
                (int)$specialOfferProductId,
                0,
                (float)$specialOfferDiscount['product_discount'],
                $specialOfferDiscount['discount_type'],
                $this->context->cart->id,
                $this->context->shop->getContextualShopId(),
                0,
                $this->context->country->id
            );
        }

        $quantityToAdd = $this->getValidatedAddToCartQuantity(
            $specialProductQuantity,
            $specialOfferProductId,
            $productAttributeId
        );

        // Adds product to the cart
        $isCartUpdated = $this->context->cart->updateQty(
            $quantityToAdd,
            $specialOfferProductId,
            $productAttributeId
        );

        $result = [
            'is_product-deleted' => $isProductDeleted,
            'is_cart_updated' => $isCartUpdated,
        ];

        die(json_encode($result));
    }

    /**
     * Merge product attributes into one array
     *
     * @param $dropdownAttributeIds
     * @param $radioAttributeIds
     *
     * @return array
     */
    private function mergeAtrributes($dropdownAttributeIds, $radioAttributeIds)
    {
        $attributeIds = [];
        if ($dropdownAttributeIds) {
            $attributeIds = array_merge($attributeIds, $dropdownAttributeIds);
        }

        if ($radioAttributeIds) {
            $attributeIds = array_merge($attributeIds, $radioAttributeIds);
        }
        return $attributeIds;
    }

    /**
     * Return the minimal quantity that can be bought of the product
     *
     * @param $productId
     * @param $productAttributeId
     * @return int
     */
    private function getProductMinimalQuantity($productId, $productAttributeId)
    {
        $product = new Product($productId, false, $this->context->language->id, $this->context->shop->id);
        if (!empty($productAttributeId) || $productAttributeId > 0) {
            $minimalQuantity = (int)Attribute::getAttributeMinimalQty($productAttributeId);
        } else {
            $minimalQuantity = (int)$product->minimal_quantity;
        }

        return $minimalQuantity;
    }

    /**
     * Process product price request that comes from modal
     */
    private function processGetUpsellModalProductPrice()
    {
        $specialOfferHelper = $this->module->getService('smartupselladvanced.helper.specialoffer');
        $priceHelper = $this->module->getService('smartupselladvanced.helper.price');
        $productId = Tools::getValue('productId');
        $dropdownAttributeId = Tools::getValue('dropdownAttributeId');
        $radioAttributeId = Tools::getValue('radioAttributeId');
        $attributeIds = $this->mergeAtrributes($dropdownAttributeId, $radioAttributeId);
        $productAttributeId = null;
        $isOutOfStock = false;

        if (!empty($attributeIds)) {
            $productAttributeId = Product::getIdProductAttributeByIdAttributes($productId, $attributeIds);
        }

        $discount = $priceHelper->convertDiscountNumToReadable(
            $priceHelper->getProductPrice($productId, $attributeIds),
            $priceHelper->getProductDiscountAmount($productId, $attributeIds),
            'amount',
            $this->context->currency
        );

        $fullPrice = $specialOfferHelper->getSpecialOfferPriceReadable($productId, $attributeIds);

        $minimalQuantity = $this->getProductMinimalQuantity($productId, $productAttributeId);

        $productStockQuantity = Product::getQuantity($productId, $productAttributeId);

        if ($productStockQuantity < $minimalQuantity) {
            $productStockQuantity = 0;
        }

        if ($productStockQuantity <= 0) {
            $isOutOfStock = true;
        }

        $result = [
            'full_price' => $fullPrice,
            'discount' => $discount,
            'is_out_of_stock' => $isOutOfStock,
        ];

        die(json_encode($result));
    }

    /**
     * Process add to cart request that comes from add to cart modal
     */
    private function processAddToCartUpsellModal()
    {
        $productId = Tools::getValue('productId');
        $productQuantity = Tools::getValue('quantity');
        $dropdownAttributeId = Tools::getValue('dropdownAttributeId');
        $radioAttributeId = Tools::getValue('radioAttributeId');
        $attributeIds = $this->mergeAtrributes($dropdownAttributeId, $radioAttributeId);
        $productAttributeId = null;
        if (!empty($attributeIds)) {
            $productAttributeId = Product::getIdProductAttributeByIdAttributes($productId, $attributeIds);
        }

        $quantityToAdd = $this->getValidatedAddToCartQuantity($productQuantity, $productId, $productAttributeId);
        // Adds product to the cart
        $isCartUpdated = $this->context->cart->updateQty(
            $quantityToAdd,
            $productId,
            $productAttributeId
        );

        // Gets cart link
        $cartLink = $this->context->link->getPageLink(
            'cart',
            null,
            null,
            ['action' => 'show']
        );

        $result = [
            'add_to_cart_successful' => $isCartUpdated,
            'cart_link' => $cartLink,
        ];

        die(json_encode($result));
    }

    /**
     * Return quantity of the product that can be added to the cart
     *
     * @param $quantity
     * @param $productId
     * @param $productAttributeId
     * @return int
     */
    private function getValidatedAddToCartQuantity($quantity, $productId, $productAttributeId)
    {
        // Gets products minimal quantity
        $minimalQuantity = $this->getProductMinimalQuantity($productId, $productAttributeId);

        if ($minimalQuantity > $quantity) {
            $quantityToAdd = $minimalQuantity;
        } else {
            $quantityToAdd = $quantity;
        }

        return $quantityToAdd;
    }

    /**
     * Check if product is in stock
     */
    private function processIsProductInStock()
    {
        $productId = Tools::getValue('productId');
        $dropdownAttributeId = Tools::getValue('dropdownAttributeId');
        $radioAttributeId = Tools::getValue('radioAttributeId');
        $attributeIds = $this->mergeAtrributes($dropdownAttributeId, $radioAttributeId);
        $productAttributeId = null;
        $isProductAvailableToBuy = false;

        if (!empty($attributeIds)) {
            $productAttributeId = Product::getIdProductAttributeByIdAttributes($productId, $attributeIds);
        }

        $productStockQuantity = Product::getQuantity($productId, $productAttributeId);
        $minimalQuantity = $this->getProductMinimalQuantity($productId, $productAttributeId);

        if ($productStockQuantity > 0 && $productStockQuantity > $minimalQuantity) {
            $isProductAvailableToBuy = true;
        }

        $result = [
            'available_to_buy' => $isProductAvailableToBuy,
        ];

        die(json_encode($result));
    }

    /**
     * Process upsell product price
     */
    private function processGetUpsellProductPrice()
    {
        $specialOfferHelper = $this->module->getService('smartupselladvanced.helper.specialoffer');
        $priceHelper = $this->module->getService('smartupselladvanced.helper.price');
        $productId = Tools::getValue('productId');
        $productAttributeId = Tools::getValue('productAttributeId');

        $isOutOfStock = false;

        $discount = $priceHelper->convertDiscountNumToReadable(
            $priceHelper->getProductPrice($productId, null, $productAttributeId),
            $priceHelper->getProductDiscountAmount($productId, null, $productAttributeId),
            'amount',
            $this->context->currency
        );

        $fullPrice = $specialOfferHelper->getSpecialOfferPriceReadable($productId, null, $productAttributeId);

        $minimalQuantity = $this->getProductMinimalQuantity($productId, $productAttributeId);

        $productStockQuantity = Product::getQuantity($productId, $productAttributeId);

        if ($productStockQuantity < $minimalQuantity) {
            $productStockQuantity = 0;
        }

        if ($productStockQuantity <= 0) {
            $isOutOfStock = true;
        }

        if ($isOutOfStock) {
            $outOfStockText = $this->l('Out Of Stock');
        } else {
            $outOfStockText = $this->l('In Stock');
        }

        $result = [
            'full_price' => $fullPrice,
            'discount' => $discount,
            'is_out_of_stock' => $isOutOfStock,
            'out_of_stock_text' => $outOfStockText,
        ];

        die(json_encode($result));
    }
}
