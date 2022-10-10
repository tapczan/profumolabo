<?php

class AdminProductsController extends AdminProductsControllerCore
{
    public function getPreviewUrl(Product $product)
    {
        $preview_url = parent::getPreviewUrl($product);
		
		$preview_url .= ((strpos($preview_url, '?') === false) ? '?' : '&').'force_preview&id_product='.(int)$product->id;

        return $preview_url;
    }
}	