<?php

class createit_productfield2Override extends createit_productfield2
{
    public function hookDisplayCreateitProductfield2_id_product($params)
    {
        $this->context->smarty->assign([
            'product_object' => $this->getProductfield2ListFront2($params),
            'languages' => Language::getLanguages(),
        ]);

        return $this->display(__FILE__, 'views/templates/front/productfield2_field.tpl');
    }

    /**
     * TODO TO BE REFACTOR!!!
     * @param $params
     * @return array|string[]
     * @throws PrestaShopException
     */
    private function getProductfield2ListFront2($params)
    {

        $id_product = $params['product']['id_product'];

        $list = [
            'product_name' => '',
            'product_url' => ''
        ];

        /**
         * @var $repository CreateitProductfield2Repository
         */
        $repository = $this->get('prestashop.module.createit_productfield.createit_productfield2_repository');

        /**
         * @var $product CreateitProductfield2
         */
        $product = $repository->findOneBy(['productId' => $id_product]);

        if($product){
            $productObj = new Product($product->getProductIdLinked(), false, Context::getContext()->language->getId());

            $link = new Link();

            $url = $link->getProductLink($productObj);

            $list = [
                'product_name' => $productObj->name,
                'product_url' => $url
            ];

        }

        return $list;
    }

}