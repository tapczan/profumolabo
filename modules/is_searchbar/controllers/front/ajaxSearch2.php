<?php

class Is_searchbarAjaxSearch2ModuleFrontController extends ModuleFrontController
{
    public $search_string;
    public $per_page;

    public function init()
    {
        parent::init();

        $this->search_string = Tools::getValue('s');
        $this->per_page = Tools::getValue('perPage');
    }

    public function initContent()
    {
        parent::initContent();

        $this->ajaxRender(json_encode([
            'hasError' => false,
            'sucess' => true,
            'content' => $this->renderResults()
        ]));

        $this->ajaxDie();
    }

    private function renderResults()
    {
        $data = Search::find(
            $this->context->language->id,
            $this->search_string,
            1,
            $this->per_page
        );

        $products = $data['result'];
        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = $presenterFactory->getPresenter();

        $products_for_template = [];
        $new_products = [];

        foreach ($products as $rawProduct) {

            $category = strtolower($rawProduct['category']);
            if( $category == 'drugstore' || $category == 'drogeria') {
                continue;
            }
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );

            $new_products[] = $rawProduct;
        }
        
        $moreResults = false;
        $moreResultsCount = 0;

        if ($data['total'] > count($products)) {
            $moreResults = $this->context->link->getPageLink('search', true, null, ['s' => $this->search_string]);
            $moreResultsCount = $data['total'] - count($products);
        }

        $this->context->smarty->assign(array(
            'products' => $products_for_template,
            'moreResults' => $moreResults,
            'moreResultsCount' => $moreResultsCount,
            'newProducts' => $new_products
        ));

        return $this->context->smarty->fetch('module:is_searchbar/views/templates/front/search_result.tpl');
    }

}
