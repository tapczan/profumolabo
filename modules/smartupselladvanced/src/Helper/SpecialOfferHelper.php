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

namespace Invertus\SmartUpsellAdvanced\Helper;

use Currency;
use Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider\LinkProvider;
use Invertus\SmartUpsellAdvanced\Repository\ProductRepository;
use Invertus\SmartUpsellAdvanced\Repository\SpecialOfferCartRelationRepository;
use Product;
use Context;
use Tools;

class SpecialOfferHelper
{
    /**
     * @var Currency
     */
    private $currency;
    /**
     * @var LinkProvider
     */
    private $linkProvider;
    /**
     * @var PriceHelper
     */
    private $priceHelper;

    /**
     * SpecialOfferHelper constructor.
     * @param LinkProvider $linkProvider
     * @param PriceHelper $priceHelper
     */
    public function __construct(LinkProvider $linkProvider, PriceHelper $priceHelper)
    {
        $this->currency = Context::getContext()->currency;
        $this->linkProvider = $linkProvider;
        $this->priceHelper = $priceHelper;
    }

    /**
     * @param $specialOfferID
     *
     * @return string
     */
    public function getSpecialOfferName($specialOfferID)
    {
        $product = new Product($specialOfferID, false, Context::getContext()->language->id);
        return $product->name;
    }

    /**
     * @param $specialOfferID
     *
     * @return string
     */
    public function getProductShortDescription($specialOfferID)
    {
        $product = new Product($specialOfferID, false, Context::getContext()->language->id);
        return $product->description_short;
    }

    /**
     * @param $specialOffer
     *
     * @return int|mixed
     */
    public function displaySpecialOfferTimer($specialOffer)
    {
        return $specialOffer['is_limited_time'] == 1 ? $specialOffer['time_limit'] : -1;
    }

    /**
     * @param $productId
     *
     * @return array
     */
    public function getAttributeGroupArray($productId)
    {
        $return = []; // final array
        // The item can have more than one group arrays. This var stores current group array
        $currentGroupArrayEntry = [];
        $currentIdAttributeGroup = 0;
        $attributes = [];
        $language = Context::getContext()->language->id;
        $product = new Product($productId, false, $language);
        $allAttributes = $product->getAttributesGroups($language);
        $groupName = '';
        $PublicGroupName = '';
        $groupType = '';
        $selected = false;

        $i = 0;
        $len = count($allAttributes);
        foreach ($allAttributes as $attribute) {
            // If group name is different then create new array with IdProductAttribute as
            // key and store new values to it, if needed to check if the product group is still the same
            if ($groupName != $attribute['group_name'] &&
                $PublicGroupName != $attribute['public_group_name'] &&
                $groupType != $attribute['group_type']
            ) {
                $selected = true;
                // Checks if it's not initial cycle
                if ($groupName != '' && $PublicGroupName != '' && $groupType != '') {
                    $currentGroupArrayEntry['attributes'] = $attributes;
                    $attributes = [];
                    $return[$currentIdAttributeGroup] = $currentGroupArrayEntry;
                }// Initiates current group attributes
                $groupName = $attribute['group_name'];
                $PublicGroupName = $attribute['public_group_name'];
                $groupType = $attribute['group_type'];

                //
                $currentIdAttributeGroup = $attribute['id_attribute_group'];
                $currentGroupArrayEntry['group_name'] = $groupName;
                $currentGroupArrayEntry['name'] = $PublicGroupName;
                $currentGroupArrayEntry['group_type'] = $groupType;
            }

            $selectionName = $attribute['attribute_name'];
            $htmlColorCode = $attribute['attribute_color'];
            $texture = "";

            $attributeEntry = [];
            $attributeEntry['name'] = $selectionName;
            $attributeEntry['html_color_code'] = $htmlColorCode;
            $attributeEntry['texture'] = $texture;
            $attributeEntry['selected'] = $selected;
            if (!array_key_exists($attribute['id_attribute'], $attributes)) {
                $attributes[$attribute['id_attribute']] = $attributeEntry;
            }
            $selected = false;

            // Check if it's the last cycle
            if (($groupName != $attribute['group_name'] &&
                    $PublicGroupName != $attribute['public_group_name'] &&
                    $groupType != $attribute['group_type']) ||
                $i == $len - 1
            ) {
                $currentGroupArrayEntry['attributes'] = $attributes;
                $attributes = [];
                $return[$currentIdAttributeGroup] = $currentGroupArrayEntry;
            }
            $i++;
        }

        return $return;
    }


    /**
     * @param $specialOfferId
     * @param null $attributeId
     * @param null $productAttrbuteId
     *
     * @return string
     */
    public function getSpecialOfferPriceReadable($specialOfferId, $attributeId = null, $productAttrbuteId = null)
    {
        $priceHelper = new PriceHelper();
        return Tools::displayPrice(
            $priceHelper->getProductPrice($specialOfferId, $attributeId, $productAttrbuteId),
            $this->currency
        );
    }

    /**
     * @param $mainProductId
     * @param $specialOfferId
     * @param null $attributeId
     *
     * @return array
     */
    public function getSpecialOfferDiscountReadable($mainProductId, $specialOfferId, $attributeId = null)
    {
        $discountHelper = new PriceHelper();
        $return = [];
        $discountArray = ProductRepository::getSpecialOfferDiscount($mainProductId);
        $productPrice = $discountHelper->getProductPrice($specialOfferId, $attributeId);

        if (!empty($discountArray)) {
            $discount = $discountArray[0]['discount'];
            $discountType = $discountArray[0]['discount_type'];

            $return = $discountHelper->convertDiscountNumToReadable(
                $productPrice,
                $discount,
                $discountType,
                $this->currency
            );
        }

        return $return;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function getSpecialOffers(array $items)
    {
        $return = [];

        foreach ($items as $item) {
            $specialOffer = ProductRepository::getSpecialOffer($item['id_product']);
            $specialOfferGroups = ProductRepository::getSpecialOfferGroups($item['id_product']);

            if (!empty($specialOffer)) {
                $tempItem = $specialOffer[0];
                $groupArray = [];
                $groupArray['groups'] = $specialOfferGroups;
                $return[] = array_merge($tempItem, $groupArray);
            }
        }

        return $return;
    }

    /**
     * @param $IdProduct
     *
     * @return string
     */
    public function getProductName($IdProduct)
    {
        $product = new Product($IdProduct, false, Context::getContext()->language->id);
        return $product->name;
    }

    /**
     * @param $specialOffers
     * @param $ajaxModuleLink
     *
     * @return array
     */
    public function getSmartyFriendlySpecialOffers($specialOffers, $ajaxModuleLink, $customerId)
    {
        $specialOffersSmartyFriendly = [];
        $imageLinkProvider = new LinkProvider();

        if (!empty($specialOffers)) {
            foreach ($specialOffers as $specialOffer) {
                $tempSpecialOffer = [];
                $tempSpecialOffer['special_offer_name'] = $this->getSpecialOfferName(
                    $specialOffer['id_special_product']
                );
                $tempSpecialOffer['image_link'] = $imageLinkProvider->getImageLinkById(
                    $specialOffer['id_special_product']
                );
                $tempSpecialOffer['product_link'] = $imageLinkProvider->getProductLinkById(
                    $specialOffer['id_special_product']
                );
                $tempSpecialOffer['short_description'] = $this->getProductShortDescription(
                    $specialOffer['id_special_product']
                );
                $tempSpecialOffer['product_name'] = $this->getProductName(
                    $specialOffer['id_main_product']
                );
                $tempSpecialOffer['special_offer_time'] = $this->displaySpecialOfferTimer($specialOffer);
                $tempSpecialOffer['group_attributes'] = $this->getAttributeGroupArray(
                    $specialOffer['id_special_product']
                );
                $tempSpecialOffer['price'] = $this->getSpecialOfferPriceReadable(
                    $specialOffer['id_special_product']
                );

                $tempSpecialOffer['used'] = false;

                if (!empty(SpecialOfferCartRelationRepository::isUsed(
                    $customerId,
                    $specialOffer['id_special_offer']
                ))) {
                    $tempSpecialOffer['used'] = true;
                }

                $tempSpecialOffer['discount'] = $this->getSpecialOfferDiscountReadable(
                    $specialOffer['id_main_product'],
                    $specialOffer['id_special_product']
                );
                $tempSpecialOffer['id_special_product'] = $specialOffer['id_special_product'];
                $tempSpecialOffer['id_special_offer'] = $specialOffer['id_special_offer'];
                $tempSpecialOffer['id_main_product'] = $specialOffer['id_main_product'];
                $tempSpecialOffer['special_offer_type'] = $specialOffer['is_type'];
                $tempSpecialOffer['module_link'] = $ajaxModuleLink;
                $specialOffersSmartyFriendly[] = $tempSpecialOffer;
            }
        }

        return $specialOffersSmartyFriendly;
    }

    /**
     * @param $specialOfferId
     *
     * @return mixed
     */
    public function isCrossSell($specialOfferId)
    {
        $specialOffer = ProductRepository::getSpecialOffer($specialOfferId);
        return $specialOffer[0]['is_type'];
    }
}
