<?php

class AdminInformationBarController extends ModuleAdminController
{
    protected $entityJsonFields = array();

    public function __construct()
    {
        $this->table = 'information_bar';
        $this->className = 'InformationBar';
        $this->lang = true;

        $this->entityJsonFields = array('styling', 'counter_options', 'button_options');

        $this->bootstrap = true;

        parent::__construct();

        if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
            $this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, Tab::getIdFromClassName('AdminInformationBar'));
            $this->tabAccess['view'] = Module::getPermissionStatic($this->module->id, 'view');
            
            $configAccess = Module::getPermissionStatic($this->module->id, 'configure');
            $this->tabAccess['add'] = $configAccess;
            $this->tabAccess['edit'] = $configAccess;
            $this->tabAccess['delete'] = $configAccess;
        } else {
            $this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, Tab::getIdFromClassName('AdminInformationBar'));
        }

        $this->actions = array('edit', 'delete', 'duplicate');
        
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );

        $this->fields_list = array(
            'id_information_bar' => array(
                'title' => $this->l('ID'),
                'type' => 'int',
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ),
            'name' => array(
                'title' => $this->l('Name'),
            ),
            'type' => array(
                'title' => $this->l('Type'),
                'callback' => 'translateType',
                'type' => 'select',
                'list' => array(
                    InformationBar::TYPE_INFO => $this->l('Information bar'),
                    InformationBar::TYPE_COUNTER => $this->l('Information + counter'),
                ),
                'filter_key' => 'a!type',
                'orderby' => false
            ),
            'date_from' => array(
                'title' => $this->l('Displayed from'),
                'type' => 'datetime'
            ),
            'date_to' => array(
                'title' => $this->l('Displayed to'),
                'type' => 'datetime'
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'type' => 'bool',
                'orderby' => false,
                'active' => 'active',
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ),
            'date_add' => array(
                'title' => $this->l('Creation date'),
                'type' => 'datetime'
            ),
        );

        $this->_orderBy = 'id_information_bar';
        $this->_orderWay = 'DESC';
    }

    public function init()
    {
        parent::init();

        HelperList::$cache_lang['Copy images too?'] = false;

        Shop::addTableAssociation($this->table, array('type' => 'shop'));

        if (Shop::getContext() == Shop::CONTEXT_SHOP) {
            $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'information_bar_shop` sa ON (a.`id_information_bar` = sa.`id_information_bar` AND sa.id_shop = '.(int) $this->context->shop->id.') ';
        }

        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = ' AND sa.`id_shop` = '.(int) Context::getContext()->shop->id;
        }

        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            unset($this->fields_list['position']);
        }
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        if ($this->display != 'edit' && $this->display != 'add') {
            $this->page_header_toolbar_btn['new_infobar'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->l('Add new information bar'),
                'icon' => 'process-icon-new'
            );
        }

        if ($this->display == 'edit' || $this->display == 'add') {
            $back = self::$currentIndex.'&token='.$this->token;
            $this->page_header_toolbar_btn['back_to_list'] = array(
                'href' => $back,
                'desc' => $this->l('Back to list'),
                'icon' => 'process-icon-back'
            );
        }
    }

    // public function displayEnableLink($token, $id, $value, $active, $id_category = null, $id_product = null, $ajax = false)
    // {
    //     $this->context->smarty->assign(array(
    //         'enabled' => (bool)$value,
    //     ));

    //     if ($this->module->ps_version == 1.5) {
    //         return $this->context->smarty->fetch(_PS_MODULE_DIR_.'x13privacymanager/views/templates/admin/list_action_enable_15.tpl');
    //     }

    //     return $this->context->smarty->fetch(_PS_MODULE_DIR_.'x13privacymanager/views/templates/admin/list_action_enable.tpl');
    // }

    public function translateType($value, $row = null)
    {
        switch ($value) {
            case InformationBar::TYPE_INFO:
                return $this->l('Information bar');
                break;

            case InformationBar::TYPE_COUNTER:
                return $this->l('Information + counter');
                break;

            default:
                break;
        }
    }

    public function postProcess()
    {
        if (count($this->entityJsonFields) && $this->action == 'save') {
            foreach ($this->entityJsonFields as $jsonField) {
                $_POST[$jsonField] = json_encode(Tools::getValue($jsonField));
            }
        }
        parent::postProcess();
    }

    /**
     * This method is workaround for a colorpicker bug
     * 
     * @see https://github.com/PrestaShop/PrestaShop/pull/25012
     */
    public function addJqueryPlugin($name, $folder = null, $css = true)
    {
        if ($name == 'colorpicker') {
            return;
        }

        parent::addJqueryPlugin($name, $folder, $css);
    }

    public function renderForm()
    {
        $id_lang = $this->context->language->id;
        $obj = $this->loadObject(true);

        Media::addJsDef(
            [
                'previewInfobar' => ((int) Tools::getValue('previewInfobar', 0) && Validate::isLoadedObject($obj)),
                'previewInfobarUrl' => $this->context->link->getPageLink(
                    'index',
                    null,
                    null,
                    [
                        'previewInfobar' => $obj->id,
                        'previewToken' => Tools::encrypt($obj->id)
                    ]
                )
            ]
        );

        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/admin.js');
        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/jquery.colorpicker.js');
        $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/admin.css');

        $i = 0;
        $this->fields_form[$i]['form'] = array(
            'legend' => array(
                'title' => $this->l('Information bar'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'name' => 'name',
                    'label' => $this->l('Name'),
                    'desc' => $this->l('only for back-office'),
                    'required' => true,
                    'lang' => true
                ),
                array(
                    'type' => 'switch',
                    'class' => 't',
                    'label' => $this->l('Info bar enabled?'),
                    'name' => 'active',
                    'required' => true,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'class' => 't',
                    'label' => $this->l('Show close button?'),
                    'name' => 'closeable',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'closeable_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'closeable_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'class' => 't',
                    'label' => $this->l('Enabled on mobile?'),
                    'name' => 'mobile',
                    'required' => true,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'mobile_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'mobile_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Displayed from'),
                    'desc' => $this->l('thanks to this option you can plan information for the future'),
                    'name' => 'date_from',
                    'required' => false,
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Displayed to'),
                    'desc' => $this->l('used to decide when counter ends, and when to stop display information'),
                    'name' => 'date_to',
                    'required' => false,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'desc' => $this->l('use [button] to insert button, [counter] to insert counter, of course this will work if valid options are set below'),
                    'name' => 'text',
                    'cols' => 75,
                    'rows' => 7,
                    'required' => true,
                    'lang' => true,
                    'autoload_rte' => true
                ),
                array(
                    'type' => 'html',
                    'name' => 'htmlcontent',
                    'html_content' => '
                        <div>
                            <button onClick="x13InsertCounter(event)" class="btn btn-default">'.$this->l('Insert counter').'</button>
                            <button onClick="x13InsertButton(event)" class="btn btn-default">'.$this->l('Insert button').'</button>
                            <button onClick="x13InsertSection(event)" class="btn btn-default">'.$this->l('Insert new section').'</button>
                        </div>
                        <hr>
                        <details>
                            <summary>'.$this->l('How does "sections" works?').'</summary>
                            <p>
                                '.sprintf(
                                    $this->l('This is a great feature if you want to display multiple texts in one infobar, simply click a button and add text between horizontal lines. %s Important, you may want to set fixed height for bar to avoid issues with its height causing jumping of background.')
                                    ,'<br>'
                                ).'
                            </p>
                        </details>
                        <hr>
                    ',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Text align:'),
                    'name' => 'styling[text_align]',
                    'validation' => 'isGenericName',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'left',
                                'name' => $this->l('Left'),
                            ),
                            array(
                                'id' => 'center',
                                'name' => $this->l('Center'),
                            ),
                            array(
                                'id' => 'right',
                                'name' => $this->l('Right'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Position:'),
                    'desc' => $this->l('Static: stay at the top of the page, Fixed: follow user position while scrolling'),
                    'name' => 'styling[position]',
                    'validation' => 'isGenericName',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'fixed',
                                'name' => $this->l('Fixed'),
                            ),
                            array(
                                'id' => 'fixedBottom',
                                'name' => $this->l('Fixed bottom'),
                            ),
                            array(
                                'id' => 'static',
                                'name' => $this->l('Static'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Minimum height of the bar'),
                    'desc' => $this->l('for example: 40px, default 0'),
                    'name' => 'styling[min_height]',
                    'required' => false,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Style, background color'),
                    'name' => 'styling[background_color]',
                    'required' => false,
                ),
                array(
                    'type' => 'switch',
                    'class' => 't',
                    'label' => $this->l('Animate background?'),
                    'desc' => $this->l('decide whether you want to animate background a bit, it will fade from light to dark version of color you choose above'),
                    'name' => 'styling[animate_bg]',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'styling_animate_bg_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'styling_animate_bg_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Style, text color'),
                    'name' => 'styling[text_color]',
                    'required' => false,
                ),
                array(
                    'type' => 'paddings',
                    'label' => $this->l('Style, paddings'),
                    'name' => 'styling[padding]',
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Style, text size'),
                    'desc' => $this->l('for example: 12px'),
                    'name' => 'styling[font_size]',
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Interval time between section change'),
                    'desc' => $this->l('in seconds'),
                    'name' => 'styling[section_interval]',
                    'suffix' => 's',
                    'class' => 'fixed-width-xs',
                    'required' => false,
                ),
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Save and stay'),
                    'name' => 'submitAdd'.$this->table.'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'
                ),
                'save-and-preview' => array(
                    'title' => $this->l('Save and preview'),
                    'name' => 'submitAdd'.$this->table.'AndPreview',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-preview'
                )
            )
        );
        $i++;

        $this->fields_form[$i]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced options'),
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Type:'),
                    'name' => 'type',
                    'validation' => 'isUnsignedInt',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => InformationBar::TYPE_INFO,
                                'name' => $this->l('Information'),
                            ),
                            array(
                                'id' => InformationBar::TYPE_COUNTER,
                                'name' => $this->l('Counter (and information)'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Counter theme'),
                    'name' => 'counter_options[theme]',
                    'validation' => 'isUnsignedInt',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => InformationBar::COUNTER_THEME_DEFAULT,
                                'name' => $this->l('Inline'),
                            ),
                            array(
                                'id' => InformationBar::COUNTER_THEME_BOX_INSIDE,
                                'name' => $this->l('Box with labels inside'),
                            ),
                            array(
                                'id' => InformationBar::COUNTER_THEME_BOX_OUTSIDE,
                                'name' => $this->l('Box with labels outside'),
                            ),
                            array(
                                'id' => InformationBar::COUNTER_THEME_FLIP,
                                'name' => $this->l('Flip'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                // array(
                //     'type' => 'select',
                //     'label' => $this->l('Counter style:'),
                //     'name' => 'styling[style]',
                //     'validation' => 'isUnsignedInt',
                //     'form_group_class' => 'type_counter',
                //     'options' => array(
                //         'query' => array(
                //             array(
                //                 'id' => 1,
                //                 'name' => $this->l('First'),
                //             ),
                //             array(
                //                 'id' => 2,
                //                 'name' => $this->l('Second'),
                //             ),
                //             array(
                //                 'id' => 3,
                //                 'name' => $this->l('Third'),
                //             ),
                //             array(
                //                 'id' => 4,
                //                 'name' => $this->l('Fourth'),
                //             ),
                //         ),
                //         'id' => 'id',
                //         'name' => 'name'
                //     ),
                // ),
                array(
                    'type' => 'color',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, timer text color'),
                    'name' => 'counter_options[timer_text_color]',
                    'required' => false,
                ),
                array(
                    'type' => 'color',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, timer background color'),
                    'name' => 'counter_options[timer_background_color]',
                    'required' => false,
                ),
                array(
                    'type' => 'border',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, timer border style'),
                    'name' => 'counter_options[timer_border]',
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, timer border-radius'),
                    'desc' => $this->l('set it to 50% to make circle'),
                    'name' => 'counter_options[timer_border_radius]',
                    'required' => false,
                ),
                array(
                    'type' => 'select',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, show days as'),
                    'name' => 'counter_options[days]',
                    'required' => false,
                    'validation' => 'isUnsignedInt',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('d'),
                            ),
                            array(
                                'id' => 2,
                                'name' => $this->l('days'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, show hours as'),
                    'name' => 'counter_options[hours]',
                    'required' => false,
                    'validation' => 'isUnsignedInt',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('h'),
                            ),
                            array(
                                'id' => 2,
                                'name' => $this->l('hour.'),
                            ),
                            array(
                                'id' => 3,
                                'name' => $this->l('hours'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, show minutes as'),
                    'name' => 'counter_options[minutes]',
                    'required' => false,
                    'validation' => 'isUnsignedInt',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('m'),
                            ),
                            array(
                                'id' => 2,
                                'name' => $this->l('min.'),
                            ),
                            array(
                                'id' => 3,
                                'name' => $this->l('minutes'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, show seconds as'),
                    'name' => 'counter_options[seconds]',
                    'required' => false,
                    'validation' => 'isUnsignedInt',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('s'),
                            ),
                            array(
                                'id' => 2,
                                'name' => $this->l('sec.'),
                            ),
                            array(
                                'id' => 3,
                                'name' => $this->l('seconds'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'form_group_class' => 'type_counter',
                    'label' => $this->l('Counter, what to do after finish:'),
                    'name' => 'after_end',
                    'validation' => 'isUnsignedInt',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => InformationBar::AFTER_END_RELOAD,
                                'name' => $this->l('Reload entire page'),
                            ),
                            array(
                                'id' => InformationBar::AFTER_END_HIDE,
                                'name' => $this->l('Hide counter'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Save and stay'),
                    'name' => 'submitAdd'.$this->table.'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'
                ),
                'save-and-preview' => array(
                    'title' => $this->l('Save and preview'),
                    'name' => 'submitAdd'.$this->table.'AndPreview',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-preview'
                )
            )
        );
        $i++;

        $this->fields_form[$i]['form'] = array(
            'legend' => array(
                'title' => $this->l('Call to action button'),
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'class' => 't',
                    'label' => $this->l('Call2Action button?'),
                    'desc' => $this->l('decide whether you want or not use button inside information bar, use [button] tag to insert button'),
                    'name' => 'button',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'button_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'button_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'textarea',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, text'),
                    'name' => 'button_text',
                    'cols' => 75,
                    'rows' => 7,
                    'required' => false,
                    'lang' => true,
                    'autoload_rte' => false
                ),
                array(
                    'type' => 'text',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, URL'),
                    'name' => 'url',
                    'lang' => true,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, text size'),
                    'desc' => $this->l('for example: 12px'),
                    'name' => 'button_options[font_size]',
                    'required' => false,
                ),
                array(
                    'type' => 'color',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, text color'),
                    'name' => 'button_options[text_color]',
                    'required' => false,
                ),
                array(
                    'type' => 'color',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, background color'),
                    'name' => 'button_options[background_color]',
                    'required' => false,
                ),
                array(
                    'type' => 'color',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, background color on hover'),
                    'name' => 'button_options[hover]',
                    'required' => false,
                ),
                array(
                    'type' => 'color',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, text color on hover'),
                    'name' => 'button_options[hover_text]',
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, border radius'),
                    'desc' => $this->l('for example: 3px'),
                    'name' => 'button_options[border_radius]',
                    'required' => false,
                ),
                array(
                    'type' => 'paddings',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, paddings'),
                    'name' => 'button_options[padding]',
                    'required' => false,
                ),
                array(
                    'type' => 'border',
                    'form_group_class' => 'button_enabled',
                    'label' => $this->l('Button, border'),
                    'name' => 'button_options[border]',
                    'required' => false,
                ),
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Save and stay'),
                    'name' => 'submitAdd'.$this->table.'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'
                ),
                'save-and-preview' => array(
                    'title' => $this->l('Save and preview'),
                    'name' => 'submitAdd'.$this->table.'AndPreview',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-preview'
                )
            )
        );
        $i++;

        $this->fields_form[$i]['form'] = array(
            'legend' => array(
                'title' => $this->l('Custom CSS and Troubleshooting'),
            ),
            'input' => array(
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Custom CSS'),
                    'desc' => $this->l('you can use this field to add some custom CSS directly to this information bar'),
                    'name' => 'custom_css',
                    'cols' => 75,
                    'rows' => 7,
                    'required' => false,
                    'lang' => false,
                    'autoload_rte' => false
                ),
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Save and stay'),
                    'name' => 'submitAdd'.$this->table.'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'
                ),
                'save-and-preview' => array(
                    'title' => $this->l('Save and preview'),
                    'name' => 'submitAdd'.$this->table.'AndPreview',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-preview'
                )
            )
        );
        $i++;

        if (!$obj->id) {
            foreach (Language::getLanguages(false) as $lang) {
                $this->fields_value['button_text'][$lang['id_lang']] = 'Text';
                $this->fields_value['url'][$lang['id_lang']] = '#';
            }
        }

        $counterOptionsFields = InformationBar::getCounterFields();
        $counterOptions = json_decode($obj->counter_options, true);
        foreach ($counterOptionsFields as $field => $defaultValue) {
            if (is_array($counterOptions) && array_key_exists($field, $counterOptions)) {
                $this->fields_value['counter_options['.$field.']'] = $counterOptions[$field];
            } else {
                $this->fields_value['counter_options['.$field.']'] = $defaultValue;
            }
        }

        $stylingOptionsFields = InformationBar::getStylingFields();
        $stylingOptions = json_decode($obj->styling, true);
        foreach ($stylingOptionsFields as $field => $defaultValue) {
            if (is_array($stylingOptions) && array_key_exists($field, $stylingOptions)) {
                $this->fields_value['styling['.$field.']'] = $stylingOptions[$field];
            } else {
                $this->fields_value['styling['.$field.']'] = $defaultValue;
            }
        }

        $buttonOptionsFields = InformationBar::getButtonFields();
        $buttonOptions = json_decode($obj->button_options, true);
        foreach ($buttonOptionsFields as $field => $defaultValue) {
            if (is_array($buttonOptions) && array_key_exists($field, $buttonOptions)) {
                $this->fields_value['button_options['.$field.']'] = $buttonOptions[$field];
            } else {
                $this->fields_value['button_options['.$field.']'] = $defaultValue;
            }
        }

        if (Shop::isFeatureActive()) {
            $this->fields_form[$i]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Shop association:'),
                ),
                'input' => array(
                    array(
                        'type' => 'shop',
                        'label' => $this->l('Shop association:'),
                        'name' => 'checkBoxShopAsso',
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            );
        }
        $i++;

        $this->multiple_fieldsets = true;
        
        return parent::renderForm();
    }

    public function initContent()
    {
        if ($this->module->initModule()) {
            parent::initContent();
        }
    }

    public function initProcess()
    {
        if (Tools::getIsset('duplicate'.$this->table)) {
            $this->action = 'duplicate';
        }

        if (Tools::getIsset('active'.$this->table)) {
            $this->action = 'toggleActivity';
        }

        parent::initProcess();
    }

    public function processToggleActivity()
    {
        if (Validate::isLoadedObject($infobar = new InformationBar((int) Tools::getValue('id_information_bar')))) {
            $infobar->active = !$infobar->active;
            $infobar->save();
            Tools::redirectAdmin(self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminInformationBar').'&conf=4');
        } else {
            $this->errors[] = $this->l('There was an error while changing information bar activity');
        }
    }

    public function processDuplicate()
    {
        if (Validate::isLoadedObject($infobar = new InformationBar((int) Tools::getValue('id_information_bar')))) {
            unset($infobar->id);
            $infobar->add();
            Tools::redirectAdmin(self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminInformationBar').'&conf=19');
        } else {
            $this->errors[] = $this->l('There was an error while copying information bar');
        }
    }

    public function processSave()
    {
        parent::processSave();

        if (Tools::isSubmit('submitAdd'.$this->table.'AndPreview')) {
            $this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$this->object->id.'&conf=3&update'.$this->table.'&token='.$this->token.'&previewInfobar=1';
        }
    }
}
