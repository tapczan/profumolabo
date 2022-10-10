<?php

/*
 * File from https://prestashow.pl
 *
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @authors     PrestaShow.pl <kontakt@prestashow.pl>
 * @copyright   2018 PrestaShow.pl
 * @license     https://prestashow.pl/license
 */

require_once dirname(__FILE__) . "/../../config.php";

use Prestashow\PrestaCore\Model\AbstractAdminController;
use Prestashow\PShowLazyImg\Repository\ImageOptimizeRepository;
use Prestashow\PShowLazyImg\Service\WebpService;
use WebPConvert\Convert\ConverterFactory;

class PShowLazyImgMainController extends AbstractAdminController
{

    public $default_action = 'index';
    public $select_menu_tab = 'subtab-PShowLazyImgMain';

    protected $fields = array(
        'pshowlazyimg_use_picture_tag',
        'pshowlazyimg_other_img',
    );

    /**
     * @var PShowLazyImg
     */
    public $module;

    public function __construct()
    {
        parent::__construct();
        $this->controller_displayName = $this->trans('Module Management');
    }

    public function indexAction()
    {
        $this->action_displayName = 'Module Settings';

        if (Tools::getValue('btnSubmit')) {
            foreach ($this->fields as $field) {
                Configuration::updateValue($field, Tools::getValue($field));
            }
            $this->alerts[] = array('success', $this->trans('Settings updated'));
        }

        $this->module->installOrUpdateHtaccess();

        $optionLabels = array(
            'no' => $this->trans(
                'Load normally (all images will be loaded immediately)'
            ),
            'yes_without_placeholder' => $this->trans(
                'Load lazily (images will be loaded when it will be visible on the screen)'
            ),
        );

        $numOfProductImages = ImageOptimizeRepository::countProductImages();
        $numOfProductImagesWithoutWebp = ImageOptimizeRepository::countProductImagesWithoutWebp();

        $isFriendlyUrlActive = (bool)Configuration::get('PS_REWRITING_SETTINGS');
        $url = $this->context->link->getModuleLink('pshowlazyimg', 'cron');
        $url .= $isFriendlyUrlActive ? '?' : '&';
        $url .= 'token=' . $this->module->getCronToken();

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Module settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'name' => 'pshowlazyimg_other_img',
                        'label' => 'All images',
                        'values' => array(
                            array(
                                'id' => 'no',
                                'value' => PShowLazyImg::LOAD_NORMAL,
                                'label' => $optionLabels['no'],
                            ),
                            array(
                                'id' => 'yes_without_placeholder',
                                'value' => PShowLazyImg::LOAD_LAZY_WITHOUT_PLACEHOLDER,
                                'label' => $optionLabels['yes_without_placeholder'],
                            ),
                        ),
                    ),
                    array(
                        'type' => 'radio',
                        'name' => 'pshowlazyimg_use_picture_tag',
                        'label' => 'Faster loading of WebP file',
                        'values' => array(
                            array(
                                'id' => 'yes',
                                'value' => PShowLazyImg::USE_PICTURE_TAG_YES,
                                'label' => $this->trans(
                                    'Use <picture> tag to load the WebP file immediately if it exists '
                                    . '(faster, but it may cause problems with loading graphics in some templates)'
                                ),
                            ),
                            array(
                                'id' => 'no',
                                'value' => PShowLazyImg::USE_PICTURE_TAG_NO,
                                'label' => $this->trans(
                                    'Load WebP graphics using redirection (slower but more universal)'
                                ),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name' => 'btnSubmit'
                )
            )
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $this->fields_form = array();
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = null;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        if (version_compare(_PS_VERSION_, '1.6.0', '<')) {
            $helper->currentIndex = $this->context->link->getAdminLink(
                'PShowImporterSettings',
                false
            );
            $helper->token = Tools::getAdminTokenLite('PShowImporterSettings');
        }
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        $form = $helper->generateForm(array($fields_form));
        $this->context->smarty->assign('content', $form);

        $this->alerts[] = array(
            'info',
            $this->trans(
                'You can ignore any image using \'data-lazyload-ignore\' in the '
                . '<img> tag. Example: <img src="..." data-lazyload-ignore alt="...">'
            )
        );

        $this->alerts[] = array(
            'info',
            (
                $this->trans(
                    'All images will be dynamically converted when customer open your store address '
                    . 'in the web browser. Once converted image is stored and served for all next customers. '
                )
                . '<br><br>'
                . $this->trans(
                    'You can add this URL into CRON (schedule manager) task in your hosting control panel '
                    . 'to convert all products images to WebP format:'
                )
                . '<br><a href="' . $url . '">' . $url . '</a><br>'
                . $this->trans('Product images converted to WebP format by CRON task:')
                . ' <strong>'
                . ($numOfProductImages - $numOfProductImagesWithoutWebp) . ' / ' . $numOfProductImages
                . '</strong>'
            )
        );

        $imageTestPng = _PS_MODULE_DIR_ . 'pshowlazyimg/image-test/test.png';
        $imageTestJpg = _PS_MODULE_DIR_ . 'pshowlazyimg/image-test/test.jpg';
        try {
            $test1 = WebpService::getInstance()->reconvertImage($imageTestPng);
        } catch (Exception $e) {
            $test1 = false;
        }
        try {
            $test2 = WebpService::getInstance()->reconvertImage($imageTestJpg);
        } catch (Exception $e) {
            $test2 = false;
        }
        if (!$test1) {
            $this->alerts[] = array(
                'warning',
                $this->trans(
                    'Module cannot convert PNG image to WebP format. '
                    . 'Contact with your hosting provider to enable one or more of the converters listed on the left.'
                )
            );
        } elseif (!$test2) {
            $this->alerts[] = array(
                'warning',
                $this->trans(
                    'Module cannot convert JPG image to WebP format. '
                    . 'Contact with your hosting provider to enable one or more of the converters listed on the left.'
                )
            );
        } else {
            $this->alerts[] = array(
                'success',
                $this->trans(
                    'Module is ready to convert images to WebP format.'
                )
            );
        }

        $this->context->smarty->assign('converters', $this->getConvertersList());
        $this->context->smarty->assign(
            'pshowHook_below_side_menu',
            $this->context->smarty->fetch(
                _PS_MODULE_DIR_ . 'pshowlazyimg/views/templates/side_converters.tpl'
            )
        );
    }

    protected function getConfigFieldsValues()
    {
        $result = array('separator' => '');
        foreach ($this->fields as $field) {
            $result[$field] = Configuration::get($field);
        }
        return $result;
    }

    private function getConvertersList(): array
    {
        $testConverter = function (string $name): bool {
            try {
                $converter = ConverterFactory::makeConverter(
                    $name,
                    _PS_MODULE_DIR_ . 'pshowlazyimg/image-test/test.png',
                    _PS_MODULE_DIR_ . 'pshowlazyimg/image-test/test.webp'
                );
                $converter->checkOperationality();
                $converter->checkConvertability();

                $converter = ConverterFactory::makeConverter(
                    $name,
                    _PS_MODULE_DIR_ . 'pshowlazyimg/image-test/test.jpg',
                    _PS_MODULE_DIR_ . 'pshowlazyimg/image-test/test.webp'
                );
                $converter->checkOperationality();
                $converter->checkConvertability();
            } catch (Exception $e) {
                return false;
            }
            return true;
        };

        return [
            [
                'name' => 'cwebp',
                'enabled' => $testConverter('cwebp'),
            ],
            [
                'name' => 'ffmpeg',
                'enabled' => $testConverter('ffmpeg'),
            ],
            [
                'name' => 'vips',
                'enabled' => $testConverter('vips'),
            ],
            [
                'name' => 'imagick',
                'enabled' => $testConverter('imagemagick'),
            ],
            [
                'name' => 'gmagick',
                'enabled' => $testConverter('graphicsmagick'),
            ],
            [
                'name' => 'imagickbinary',
                'enabled' => $testConverter('imagickbinary'),
            ],
            [
                'name' => 'gmagickbinary',
                'enabled' => $testConverter('gmagickbinary'),
            ],
            [
                'name' => 'gd',
                'enabled' => $testConverter('gd'),
            ],
        ];
    }

}
