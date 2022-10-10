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

namespace Invertus\SmartUpsellAdvanced\Repository;

use Category;

class CategoryRepository
{
    /**
     * Gets all categories for lists
     *
     * @return array
     */
    public static function getAllCategoriesForList()
    {
        $categories = [];

        foreach (Category::getAllCategoriesName() as $category) {
            $categories[$category['name']] = $category['name'];
        }

        return $categories;
    }
}
