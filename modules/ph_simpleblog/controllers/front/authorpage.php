<?php
/**
 * Blog for PrestaShop module by PrestaHome Team.
 *
 * @author    PrestaHome Team <support@prestahome.com>
 * @copyright Copyright (c) 2011-2021 PrestaHome Team - www.PrestaHome.com
 * @license   You only can use module, nothing more!
 */
require_once _PS_MODULE_DIR_ . 'ph_simpleblog/ph_simpleblog.php';
require_once _PS_MODULE_DIR_ . 'ph_simpleblog/controllers/front/author.php';

class PH_SimpleBlogAuthorPageModuleFrontController extends ph_simpleblogauthorModuleFrontController
{
    public function __construct()
    {
        parent::__construct();

        if (!version_compare(_PS_VERSION_, '1.7', '>=')) {
            $this->display_column_left = (is_object(Context::getContext()->theme) ? Context::getContext()->theme->hasLeftColumn('module-ph_simpleblog-list') : true);
            $this->display_column_right = (is_object(Context::getContext()->theme) ? Context::getContext()->theme->hasRightColumn('module-ph_simpleblog-list') : true);
        }
    }
}
