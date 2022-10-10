<?php
/**
 * 2015-2021 Bonpresta
 *
 * Bonpresta Instagram Carousel Social Feed Photos
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    Bonpresta
 *  @copyright 2015-2021 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class BoninstagramcarouselOverride extends Boninstagramcarousel
{
    public function hookdisplayInstagram()
    {
        $this->context->smarty->assign('limit', Configuration::get('BONINSTAGRAMCAROUSEL_LIMIT'));
        $this->context->smarty->assign('instagram_type', Configuration::get('BONINSTAGRAMCAROUSEL_TYPE'));
        $this->context->smarty->assign('display_caroucel', Configuration::get('BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL'));
        $this->context->smarty->assign('user_tag', Configuration::get('BONINSTAGRAMCAROUSEL_TAG'));
        $this->context->smarty->assign('user_id', Configuration::get('BONINSTAGRAMCAROUSEL_USERID'));
        
        return $this->display(__FILE__, '../../themes/profumo-labo/modules/boninstagramcarousel/views/templates/hooks/ps_instagram.tpl');
    }
}
 