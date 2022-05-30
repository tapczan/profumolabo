<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\Controller;

use Spark\EParagony\DocumentsManager;
use Spark\EParagony\PrinterLogManager;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTwigController extends FrameworkBundleAdminController
{
    private $documentManager;
    private $printerLogManager;

    public function __construct(DocumentsManager $documentManager, PrinterLogManager $printerLogManager)
    {
        $this->documentManager = $documentManager;
        $this->printerLogManager = $printerLogManager;
    }

    public function debugWebServer(Request $request)
    {
        $variables = [
            'logs' => [],
        ];

        $documentId = $request->request->get('documentId');
        if ($documentId) {
            $variables['logs'] = $this->printerLogManager->getLogForDocumentId($documentId);

        }
        
        return $this->render('@Modules/eparagony/views/templates/admin/debug_print_server.twig', $variables);
    }
}
