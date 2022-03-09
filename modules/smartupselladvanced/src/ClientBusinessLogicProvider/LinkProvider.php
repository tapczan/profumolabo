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
use Image;
use ImageType;
use Product;

class LinkProvider
{

    /**
     * Return the image link by product ID
     *
     * @param $productId
     * @param string $imageType
     *
     * @return string
     */
    public function getImageLinkById($productId, $imageType = 'cart')
    {
        $image = Image::getCover($productId);
        $product = new Product($productId, false, Context::getContext()->language->id);
        $link = Context::getContext()->link;
        $formatedName = ImageType::getFormattedName($imageType);
        $imagePath = $link->getImageLink($product->link_rewrite, $image['id_image'], $formatedName);
        return $imagePath;
    }

    /**
     * Return the product link by the product ID
     *
     * @param $productId
     *
     * @return string
     */
    public function getProductLinkById($productId)
    {
        $product = new Product($productId, false, Context::getContext()->language->id);
        $link = Context::getContext()->link;
        return $link->getProductLink($product);
    }
}
