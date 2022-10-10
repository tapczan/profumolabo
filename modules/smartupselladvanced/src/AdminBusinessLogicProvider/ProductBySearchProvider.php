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

namespace Invertus\SmartUpsellAdvanced\AdminBusinessLogicProvider;

use Tools;
use Invertus\SmartUpsellAdvanced\Repository\ProductRepository;
use ImageType;

class ProductBySearchProvider
{
    const MINIMUM_SEARCH_LENGTH = 3;

    /**
     * Search for the given product
     *
     * @param $query
     * @param $context
     * @param bool $createLink
     * @return array
     */
    public function searchProduct($query, $context, $createLink = false)
    {
        $products = null;
        $minSearchLength = self::MINIMUM_SEARCH_LENGTH;
        if ($minSearchLength <= Tools::strlen($query)) {
            $products = ProductRepository::getProductsByQuery($query);
        }

        $type = ImageType::getFormattedName('small');
        $relatedProductUrl = null;

        if ($createLink) {
            $relatedProductUrl .= $context->link->getAdminLink('AdminSmartUpsellAdvancedProductDetails');
            $relatedProductUrl .= '&id_product=';
        }

        $results = [
            'products' => $products,
            'image_type' => $type,
            'product_url' => $relatedProductUrl,
        ];

        return $results;
    }
}
