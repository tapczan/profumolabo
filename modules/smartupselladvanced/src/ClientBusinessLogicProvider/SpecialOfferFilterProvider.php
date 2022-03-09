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

namespace Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider;

use Context;
use Invertus\SmartUpsellAdvanced\Helper\CartHelper;
use Invertus\SmartUpsellAdvanced\Helper\PriceHelper;
use Invertus\SmartUpsellAdvanced\Repository\SpecialOfferCartRelationRepository;
use Product;

class SpecialOfferFilterProvider
{
    const HUNDRED_PERCENT = 100;

    /**
     * Filters array of special offers to:
     * Have no duplicate special offers (chooses the one with a higher discount).
     * Not to show special offer if it is already in the cart
     * Not to show if special offer option "Active" is disabled
     * Not to show if special offer if it is not in valid date
     * Not to show special offer if limit of special offer exceeds defined max special offers in option menu
     * Not to show special offer if the price is already reduced
     * Not to show special offer if it is not defined to be shown for current customer customer-group
     * Not to show special offer if the option is selected not to show for same customer for particular time
     *
     * @param $specialOffers
     * @param $cartProducts
     * @param $customerGroup
     * @param $customerId
     *
     * @return array
     */
    public function filterSpecialOffers($specialOffers, $cartProducts, $customerGroup, $customerId)
    {
        $filteredSpecialOffers = [];
        foreach ($specialOffers as $specialOffer) {
            $isSpecialOfferAvailable =
                !$this->isSpecialOfferAlreadyInCart($specialOffer['id_special_product'], $cartProducts)
                && $this->isSpecialOfferActive($specialOffer)
                && $this->isSpecialOfferValidInCurrentDate($specialOffer)
                && sizeof($filteredSpecialOffers) < CartHelper::getSpecialOffersLimit()
                && !$this->isPriceAlreadyReduced($specialOffer)
                && $this->isInSelectedGroups($specialOffer['groups'], $customerGroup)
                && $this->isSpecialOfferValidForCustomer($customerId, $specialOffer['id_special_product']);

            if (!empty($filteredSpecialOffers)) {// Checks if return array already has any special offers
                if ($isSpecialOfferAvailable) {
                    if ($this->isSpecialOfferInArray($filteredSpecialOffers, $specialOffer['id_special_product']) &&
                        $this->isCurrentOfferDiscountHigher($filteredSpecialOffers, $specialOffer)
                    ) {
                        $filteredSpecialOffers = $this->removeSpecialOfferDuplicates(
                            $filteredSpecialOffers,
                            $specialOffer
                        );
                    } else {
                        $filteredSpecialOffers[] = $specialOffer;
                    }
                }
            } else {
                if ($isSpecialOfferAvailable) {
                    $filteredSpecialOffers[] = $specialOffer;
                }
            }
        }
        return $filteredSpecialOffers;
    }

    /**
     * Validate if special offer is valid for the customer
     *
     * @param $customerId
     * @param $idSpecialProduct
     *
     * @return bool
     */
    private function isSpecialOfferValidForCustomer($customerId, $idSpecialProduct)
    {
        if ($customerId === null) {
            return true;
        }

        $specialOfferRelationForCustomer = SpecialOfferCartRelationRepository::getRelationsForCustomer(
            $customerId,
            $idSpecialProduct
        );
        if (!empty($specialOfferRelationForCustomer)) {
            $specialOfferHideExpiration = $specialOfferRelationForCustomer[0]['date_expires'];
            $dateTimeNow = date('Y-m-d H:i:s');

            return $specialOfferHideExpiration < $dateTimeNow;
        }

        return true;
    }

    /**
     * Check if customer is in one of selected groups
     *
     * @param $selectedCustomerGroups
     * @param $customerGroup
     *
     * @return bool
     */
    private function isInSelectedGroups($selectedCustomerGroups, $customerGroup)
    {
        foreach ($selectedCustomerGroups as $selectedCustomerGroup) {
            foreach ($selectedCustomerGroup as $currentGroup) {
                if ($currentGroup == $customerGroup) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if special offer is already in the cart
     *
     * @param $specialOfferId
     * @param $cartList
     *
     * @return bool
     */
    private function isSpecialOfferAlreadyInCart($specialOfferId, $cartList)
    {
        $return = false;
        foreach ($cartList as $cartItem) {
            if ($cartItem['id_product'] == $specialOfferId) {
                $return = true;
            }
        }

        return $return;
    }

    /**
     * Remove the special offer duplicates
     *
     * @param $specialOffers
     * @param $specialOffer
     *
     * @return array
     */
    private function removeSpecialOfferDuplicates($specialOffers, $specialOffer)
    {
        $return = [];

        foreach ($specialOffers as $offer) {
            if ($offer['id_special_product'] == $specialOffer['id_special_product']) {
                $return[] = $specialOffer;
            } else {
                $return[] = $offer;
            }
        }

        return $return;
    }

    /**
     * Check if special offer is in the list
     *
     * @param $specialOffers
     * @param $specialOfferId
     *
     * @return bool
     */
    private function isSpecialOfferInArray($specialOffers, $specialOfferId)
    {
        $return = false;

        foreach ($specialOffers as $specialOffer) {
            if ($specialOffer['id_special_product'] == $specialOfferId) {
                $return = true;
            }
        }

        return $return;
    }

    /**
     * Check if current special offer discount is higher
     *
     * @param $specialOffers
     * @param $currentOffer
     *
     * @return bool
     */
    private function isCurrentOfferDiscountHigher($specialOffers, $currentOffer)
    {
        $priceHelper = new PriceHelper();
        $return = false;


        foreach ($specialOffers as $offer) {
            if ($offer['id_special_product'] == $currentOffer['id_special_product']) {
                $offerDiscount = $this->convertDiscountToPrice(
                    $priceHelper->getProductPrice($offer['id_special_product']),
                    (float)$offer['discount'],
                    $offer['discount_type']
                );
                $currentOfferDiscount = $this->convertDiscountToPrice(
                    $priceHelper->getProductPrice($currentOffer['id_special_product']),
                    (float)$currentOffer['discount'],
                    $currentOffer['discount_type']
                );

                if ($currentOfferDiscount > $offerDiscount) {
                    $return = true;
                }
            }
        }
        return $return;
    }

    /**
     * @param $totalPrice
     * @param $discountAmount
     * @param $discountType
     *
     * @return float|int
     */
    private function convertDiscountToPrice($totalPrice, $discountAmount, $discountType)
    {
        $return = 0;

        if ($discountType === 'percent') {
            $return = $totalPrice * ($discountAmount / self::HUNDRED_PERCENT);
        } elseif ($discountType === 'amount') {
            $return = $discountAmount;
        }

        return $return;
    }

    /**
     * @param $specialOffer
     *
     * @return bool
     */
    private function isSpecialOfferValidInCurrentDate($specialOffer)
    {
        $return = false;
        $validFrom = strtotime($specialOffer['valid_from']);
        $validTo = strtotime($specialOffer['valid_to']);
        $currentDateTime = getdate()[0];
        $isValidInInterval = $specialOffer['is_valid_in_specific_interval'];

        if (($isValidInInterval == 1 &&
                $currentDateTime > $validFrom &&
                $currentDateTime < $validTo) ||
            $isValidInInterval == 0
        ) {
            $return = true;
        }

        return $return;
    }

    /**
     * @param $specialOffer
     *
     * @return bool
     */
    private function isSpecialOfferActive($specialOffer)
    {
        $return = false;
        if ($specialOffer['is_active'] == 1) {
            $return = true;
        }
        return $return;
    }


    /**
     * @param $specialOffer
     *
     * @return bool
     */
    public function isPriceAlreadyReduced($specialOffer)
    {

        if ($this->isPriceReducedBySpecialOffer($specialOffer)) {
            return false;
        }

        $return = false;
        $product = new Product($specialOffer['id_special_product'], false, Context::getContext()->language->id);

        $reduction = $product->getPrice(
            false,
            null,
            6,
            null,
            true,
            false
        );
        if ($reduction > 0) {
            $return = true;
        }

        return $return;
    }

    /**
     * @param $specialOffer
     *
     * @return bool
     */
    private function isPriceReducedBySpecialOffer($specialOffer)
    {
        $specialOfferInCart = SpecialOfferCartRelationRepository::getSpecialOffersInCartByOfferId(
            $specialOffer['id_special_offer']
        );

        return !empty($specialOfferInCart);
    }
}
