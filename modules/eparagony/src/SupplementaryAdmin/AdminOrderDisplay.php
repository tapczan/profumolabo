<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\SupplementaryAdmin;

use ASoftwareHouse\EParagony\DocumentsManager;
use ASoftwareHouse\EParagony\Entity\EparagonyDocumentStatus;
use LogicException;
use Symfony\Component\Routing\Router;
use Twig;

class AdminOrderDisplay
{
    private $twig;
    private $router;
    private $manager;

    const BTN_CODE_ISSUE = 'issue';
    const BTN_CODE_ISSUING = 'issuing';
    const BTN_CODE_ISSUED = 'issued';
    const BTN_CODE_ERROR = 'error';

    public function __construct(Twig\Environment $twig, Router $router, DocumentsManager $manager)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->manager = $manager;
    }

    private function decodeDocumentRecipeState(EparagonyDocumentStatus $status)
    {
        $state = $status->getDocumentState();
        $rest = json_decode($status->getRest(), true);
        if (!is_array($rest)) {
            $rest = [];
        }

        switch ($state) {
            case EparagonyDocumentStatus::STATE_READY:
            case EparagonyDocumentStatus::STATE_CONFIRMED:
                $url = $rest['url'] ?? null;
                $ready = (bool)$url;
                break;
            case EparagonyDocumentStatus::STATE_COMPILED:
            case EparagonyDocumentStatus::STATE_IN_PROGRESS:
                $url = $rest['url'] ?? null;
                $waiting = true;
                break;
            case EparagonyDocumentStatus::STATE_QUEUED:
            case EparagonyDocumentStatus::STATE_DOWNLOADING:
                $url = null;
                $waiting = true;
                break;
            default:
                $url = null;
                $error = true;
        }

        if ($url) {
            $params = [
                'order_id' => $status->getOrderId(),
            ];
            $sendEmail = $this->router->generate('eparagony_admin_mail_receipt', $params);
        }

        return [
            'internalState' => $state,
            'receiptError' => $error ?? false,
            'receiptReady' => $ready ?? false,
            'receiptWaiting' => $waiting ?? false,
            'receiptUrl' => $url ?? null,
            'sendEmail' => $sendEmail ?? null,
        ];
    }

    private function forForcedDownload(?EparagonyDocumentStatus $status, $orderId)
    {
        if ($status) {
            $url = $status->extractRestField('url');
        } else {
            $url = null;
        }

        if (!$url) {
            $params = [
                'order_id' => $orderId,
            ];
            $forceDownload = $this->router->generate('eparagony_admin_force_download', $params);
        }

        return [
            'forceDownload' => $forceDownload ?? null,
        ];
    }

    public function getTopContent($orderId)
    {
        #TODO Softly kill this part of code.
        return '';

        $variables = [
            'internalState' => null,
            'forceDownload' => null,
        ];
        $status = $this->manager->getDocumentStatusIfExists($orderId);
        $variables = $this->forForcedDownload($status, $orderId) + $variables;
        if ($status && $status->getDocumentType() === $status::TYPE_RECEIPT) {
            $variables = $this->decodeDocumentRecipeState($status) + $variables;
        }

        return $this->twig->render('@Modules/eparagony/views/templates/hook/admin_order_top.twig', $variables);
    }

    public function getButton($orderId)
    {
        $ret = [
            'display' => false,
            'grayed' => true,
            'code' => null,
            'url' => null,
            'additionalClass' => '',
        ];

        $status = $this->manager->getDocumentStatusIfExists($orderId);
        if ($status) {
            $state = $status->getDocumentState();
            $ret['display'] = true;
        } else {
            #TODO Not sure if it sane.
            $state = EparagonyDocumentStatus::STATE_BLANK;
            $ret['display'] = true;
        }

        switch ($state) {
            case EparagonyDocumentStatus::STATE_BLANK:
            case EparagonyDocumentStatus::STATE_QUEUED:
                $params = [
                    'order_id' => $orderId,
                ];
                $ret['url'] = $this->router->generate('eparagony_admin_force_download', $params);
                $ret['grayed'] = false;
                $ret['code'] = self::BTN_CODE_ISSUE;
                $ret['additionalClass'] = 'js-eparagony-force-download';
                break;
            case EparagonyDocumentStatus::STATE_DOWNLOADING:
                $ret['code'] = self::BTN_CODE_ISSUING;
                break;
            case EparagonyDocumentStatus::STATE_COMPILED:
            case EparagonyDocumentStatus::STATE_IN_PROGRESS:
            case EparagonyDocumentStatus::STATE_READY:
                $ret['code'] = self::BTN_CODE_ISSUING;
                $ret['url'] = $status->extractRestField('url');
                break;
            case EparagonyDocumentStatus::STATE_CONFIRMED:
                $ret['code'] = self::BTN_CODE_ISSUED;
                $ret['url'] = $status->extractRestField('url');
                break;
            default:
                $ret['code'] = self::BTN_CODE_ERROR;
                break;
        }

        return $ret;
    }

    private function forOrderTab(?EparagonyDocumentStatus $status, $orderId)
    {
        $r = [
            'documentType' => null,
            'url' => null,
            'stateRaw' => null,
            'documentId' => null,
            'sparkToken' => null,
            'updated' => null,
            'retryCount' => null,
            'sendEmail' => false,
            'forceDownload' => false,
            'documentId' => null,
            'debugPrintServer' => null,
        ];

        if ($status) {
            $r['documentType'] = $status->getDocumentType();
            $r['url'] = $status->extractRestField('url');
            $r['stateRaw'] = $status->getDocumentState();
            $r['documentId'] = $status->getTextId();
            $r['sparkToken'] = $status->extractRestField('sparkToken');
            $r['updated'] = $status->getUpdated()->format('Y-m-d H:i:s');
            $r['retryCount'] = $status->getRetryCount();
            $r['documentId'] = $status->getTextId();
        }

        if ($r['url']) {
            $params = [
                'order_id' => $status->getOrderId(),
            ];
            $r['sendEmail'] = $this->router->generate('eparagony_admin_mail_receipt', $params);
        } else {
            $params = [
                'order_id' => $orderId,
            ];
            $r['forceDownload'] = $this->router->generate('eparagony_admin_force_download', $params);
        }

        if ($r['documentId']) {
            /* We have to pass parameters in a form. */
            $r['debugPrintServer'] = $this->router->generate('eparagony_admin_debug_web_server');
        }

        return $r;
    }

    public function getTabLink($orderId)
    {
        $variables = [];
        return $this->twig->render('@Modules/eparagony/views/templates/hook/admin_order_tab_link.twig', $variables);
    }

    public function getTabContent($orderId)
    {
        $status = $this->manager->getDocumentStatusIfExists($orderId);
        $variables = $this->forOrderTab($status, $orderId);

        return $this->twig->render('@Modules/eparagony/views/templates/hook/admin_order_tab_content.twig', $variables);
    }
}
