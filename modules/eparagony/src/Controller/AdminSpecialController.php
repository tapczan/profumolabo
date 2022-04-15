<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\Controller;

use ASoftwareHouse\EParagony\DocumentsManager;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSpecialController extends FrameworkBundleAdminController
{
    private $documentManager;

    public function __construct(DocumentsManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function forceReceiptDownload(Request $request)
    {
        $status = $this->trans('Nothing done.', 'Modules.Eparagony.Eparagony');
        $orderId = $request->get('order_id');
        if ($orderId) {
            $ok = $this->documentManager->forceDownloadRecipe($orderId);
            if ($ok) {
                $status = $this->trans('Download finished.', 'Modules.Eparagony.Eparagony');
            }
        }

        return new Response($status);
    }
}
