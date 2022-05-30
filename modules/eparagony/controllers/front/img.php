<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

use Spark\EParagony\SupplementaryFront\FrontAction;

class EparagonyImgModuleFrontController extends ModuleFrontController
{
    private $img;

    public function postProcess()
    {
        parent::postProcess();
        $this->img = Tools::getValue('img');
    }

    public function display()
    {
        $dir = dirname(__FILE__) . '/../../';
        switch ($this->img) {
            case 'spark':
                header('Content-Type: image/png');
                readfile($dir . 'views/img/spark.png');
                break;
            default:
                echo 'No image';
        }
    }
}
