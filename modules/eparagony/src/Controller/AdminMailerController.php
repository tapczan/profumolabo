<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\Controller;

use Spark\EParagony\DocumentsManager;
use Spark\EParagony\Mailer;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMailerController extends FrameworkBundleAdminController
{
    private $mailer;
    private $documentManager;

    public function __construct(Mailer $mailer, DocumentsManager $documentManager)
    {
        $this->mailer = $mailer;
        $this->documentManager = $documentManager;
    }

    public function sendReceiptUrl(Request $request)
    {
        $status = $this->trans('Nothing done.', 'Modules.Eparagony.Eparagony');
        $orderId = $request->get('order_id');
        if ($orderId) {
            $document = $this->documentManager->getDocumentStatusIfExists($orderId);
            $url = $document->extractRestField('url');
            if ($url) {
                $ok = $this->mailer->mailReceipt($document);
                if ($ok) {
                    $status = $this->trans('Mail send.', 'Modules.Eparagony.Eparagony');
                }
            }
        }

        return new Response($status);
    }
}
