<?php
/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2021 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */


require_once(dirname(__FILE__) . '../../../model/nvoucher.php');

class AdminNewsletterVoucherVouchersController extends ModuleAdminController
{
    protected $position_identifier = 'id_nvoucher';

    public function __construct()
    {
        $this->table = 'nvoucher';
        $this->className = 'nvoucher';
        $this->list_no_link = true;
        $this->lang = false;
        //$this->addRowAction('edit');
        $this->addRowAction('delete');
        parent::__construct();
        $this->bootstrap = true;
        $this->_orderBy = 'id_nvoucher';
        $this->fields_list = array(
            'id_nvoucher' => array(
                'title' => $this->l('ID'),
                'align' => 'left',
                'orderby' => true,
                'width' => 30
            ),
            'email' => array(
                'title' => $this->l('Customer'),
                'width' => 'auto',
                'orderby' => true,
            ),
            'deliverydate' => array(
                'title' => $this->l('Delivery date'),
                'width' => 'auto',
                'orderby' => true,
            ),
            'code' => array(
                'title' => $this->l('Voucher code'),
                'align' => 'left',
                'orderby' => true,
                'width' => 120
            ),
        );
    }

    public function renderList()
    {
        $this->initToolbar();
        return parent::renderList();
    }

    public function init()
    {
        parent::init();
    }

    public function initToolbar()
    {
    }
}