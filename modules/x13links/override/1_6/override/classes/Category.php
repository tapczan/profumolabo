<?php

class Category extends CategoryCore
{
    public static function getUrlRewriteInformations($id_category)
    {
        $sql = 'SELECT l.`id_lang`, c.`link_rewrite`, c.`id_shop`
            FROM `'._DB_PREFIX_.'category_lang` AS c
            LEFT JOIN  `'._DB_PREFIX_.'lang` AS l ON c.`id_lang` = l.`id_lang`
            WHERE c.`id_category` = '.(int)$id_category.' AND c.`id_shop` = '.Context::getContext()->shop->id.'
            AND l.`active` = 1';
        
        return Db::getInstance()->executeS($sql);
    }
}
