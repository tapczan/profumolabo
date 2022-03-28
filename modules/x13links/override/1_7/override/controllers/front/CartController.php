<?php

class CartController extends CartControllerCore
{
    public function displayAjaxProductRefresh()
    {
        if ($this->id_product)
        {
            $url = $this->context->link->getProductLink($this->id_product, null, null, null, $this->context->language->id, null, (int)Product::getIdProductAttributesByIdAttributes($this->id_product, Tools::getValue('group'), true), false, false, true, ['quantity_wanted' => (int)$this->qty]);
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'idpa=' . (int)Product::getIdProductAttributesByIdAttributes($this->id_product, Tools::getValue('group'), true);
        }
        else
        {
            $url = false;
        }
        ob_end_clean();

        header('Content-Type: application/json');
        $this->ajaxDie(Tools::jsonEncode([
            'success' => true,
            'productUrl' => $url
        ]));
    }
}

?>