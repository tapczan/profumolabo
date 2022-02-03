<?php

/*=============================================================================*/
/*  PW Instagram feed
/*  ---
/*  PRESTAWORKS AB (www.prestaworks.se)
/*=============================================================================*/

class pwinstafeedClass extends ObjectModel
{
    public $id;
    public $id_shop;
    public $pwinstafeed_pagetitle;
    public $pwinstafeed_pagebreadcrumb;
    public $pwinstafeed_pagecontent;
    public $pwinstafeed_pagelimit;
    public $pwinstafeed_pagemodal;
    public $pwinstafeed_pagegrid_xs;
    public $pwinstafeed_pagegrid_sm;
    public $pwinstafeed_pagegrid_md;
    public $pwinstafeed_pagegrid_lg;
    public $pwinstafeed_pagegrid_xl;
    public $pwinstafeed_pagestyle;
    public $pwinstafeed_pagebgcolor;
    public $pwinstafeed_pagefgcolor;
    public $pwinstafeed_bgcolor;
    public $pwinstafeed_fgcolor;
    public $pwinstafeed_pagebtnbgcolor;
    public $pwinstafeed_pagebtnfgcolor;
    public $pwinstafeed_pagespacing;
    public $pwinstafeed_pagelikes;
    public $pwinstafeed_pagecomments;

    public static $definition = array(
        'table' => 'pwinstafeed',
        'primary' => 'id_pwinstafeed',
        'multilang' => true,
        'fields' => array(
            'id_shop'                    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'pwinstafeed_pagetitle'      => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
            'pwinstafeed_pagebreadcrumb' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
            'pwinstafeed_pagecontent'    => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'pwinstafeed_pagelimit'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagemodal'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagelikes'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagemodal'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagegrid_xs'    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagegrid_sm'    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagegrid_md'    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagegrid_lg'    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagegrid_xl'    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'pwinstafeed_pagestyle'      => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'pwinstafeed_pagebgcolor'    => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'pwinstafeed_pagefgcolor'    => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'pwinstafeed_bgcolor'        => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'pwinstafeed_fgcolor'        => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'pwinstafeed_pagebtnbgcolor' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'pwinstafeed_pagebtnfgcolor' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'pwinstafeed_pagespacing'    => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
        )
    );

    public static function getByIdShop($id_shop)
    {
        $id = Db::getInstance()->getValue('SELECT `id_pwinstafeed` FROM `'._DB_PREFIX_.'pwinstafeed` WHERE `id_shop` ='.(int)$id_shop);
        return new pwinstafeedClass($id);
    }

    public function copyFromPost()
    {
        foreach ($_POST as $key => $value)
        {
            if (key_exists($key, $this) && $key != 'id_'.$this->table)
                $this->{$key} = $value;
        }
        if (count($this->fieldsValidateLang))
        {
            $languages = Language::getLanguages(false);
            foreach ($languages as $language)
            {
                foreach ($this->fieldsValidateLang as $field => $validation)
                {
                    if (Tools::getIsset($field.'_'.(int)$language['id_lang']))
                        $this->{$field}[(int)$language['id_lang']] = $_POST[$field.'_'.(int)$language['id_lang']];
                }
            }
        }
    }
}
