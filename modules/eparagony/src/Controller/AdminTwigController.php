<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\Controller;

use PrestaShopBundle\Service\Routing\Router;
use Spark\EParagony\DocumentsManager;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Spark\EParagony\DocumentsMassManager;
use Spark\EParagony\DocumentStatusQuery;
use Spark\EParagony\Entity\EparagonyDocumentStatus;
use Spark\EParagony\Pager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTwigController extends FrameworkBundleAdminController
{
    private $documentManager;
    private $documentMassManager;

    public function __construct(DocumentsManager $manager, DocumentsMassManager $massManager)
    {
        parent::__construct();
        $this->documentManager = $manager;
        $this->documentMassManager = $massManager;
    }

    public function queue(Request $request)
    {
        $query = new DocumentStatusQuery();
        $query->pageSize = 100;
        $query->textId = trim($request->query->get('text-id'));
        $query->orderId = trim($request->query->get('order-id'));
        $query->state = trim($request->query->get('state'));
        $query->offset = Pager::getOffsetFromPage($request->query->get('page', 1), $query);
        $query->orderBy = $request->query->get('by');
        $query->orderAsc = (bool)($request->query->get('asc', false));

        $count = $this->documentMassManager->getListCount($query);

        $variables = [
            'legacy' => version_compare(_PS_VERSION_, '1.7.7', '<'),
            'postUrl' => $this->generateUrl('eparagony_admin_force_download_multiple'),
            'list' => $this->documentMassManager->getList($query),
            'count' => $count,
            'currentPage' => Pager::getPage($query),
            'lastPage' => Pager::getLastPage($count, $query),
            'orderBy' => $query->orderBy,
            'asc' => $query->orderAsc,
            'token' => $request->query->get('_token'),
            'textId' => $query->textId,
            'orderId' => $query->orderId,
            'state' => $query->state,
            'stateOptions' => [
                EparagonyDocumentStatus::STATE_QUEUED,
                EparagonyDocumentStatus::STATE_SENDING,
                EparagonyDocumentStatus::STATE_SENT,
                EparagonyDocumentStatus::STATE_CONFIRMED,
                EparagonyDocumentStatus::STATE_ERROR,
                EparagonyDocumentStatus::STATE_UNKNOWN,
            ],
        ];

        return $this->render('@Modules/eparagony/views/templates/admin/queue.twig', $variables);
    }

    public function forceMultiple(Request $request)
    {
        $records = $request->get('d');
        /* We go one by one. */
        foreach ($records as $one) {
            $documentStatus = $this->documentManager->getByTag($one);
            if ($documentStatus) {
                $this->documentManager->forceDownloadRecipe($documentStatus->getOrderId());
            }
        }

        $url = $this->generateUrl('eparagony_admin_queue');

        return new RedirectResponse($url, Response::HTTP_SEE_OTHER);
    }
}
