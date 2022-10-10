<?php
namespace XReceiptOrInvoice\Controller;

use Db;
use Order;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\ModuleActivated;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use x13paragonlubfaktura;

/**
 * Class HandleStatus.
 *
 * @ModuleActivated(moduleName="x13paragonlubfaktura", redirectRoute="admin_module_manage")
 */
class HandleStatus extends FrameworkBundleAdminController
{    
    /**
     * @AdminSecurity("is_granted('update', request.get('_legacy_controller'))", message="Access denied.")
     *
     * @param Request $request
     *
     * @return Response
    */
    public function updateAction(Request $request)
    {
        try {
            $cartId = Order::getCartIdStatic((int) $request->get('orderId'));

            $this->updateDocument($cartId);

            $result = [
                'success' => true,
                'message' => $this->trans('Successful update.', 'Admin.Notifications.Success'),
            ];
        } catch (Exception $e) {
            return $this->json(
                [
                    'success' => false,
                    'message' => $this->trans('Unable to update settings.', 'Admin.Notifications.Error')
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
        return $this->json($result);
    }

    /**
     * @AdminSecurity("is_granted('update', request.get('_legacy_controller'))", message="Access denied.")
     *
     * @param Request $request
     *
     * @return Response
    */
    public function updateBulkAction(Request $request)
    {
        $ordersToUpdate = $request->get('order_orders_bulk');

        if (!count($ordersToUpdate)) {
            $this->addFlash('error', $this->trans('Unable to update settings.', 'Admin.Notifications.Error'));
            return $this->redirectToRoute('admin_orders_index');
        }

        $ordersWithErrors = [];

        foreach ($ordersToUpdate as $idOrder) {
            try {
                $cartId = Order::getCartIdStatic((int) $idOrder);

                $this->updateDocument($cartId, ((int) $request->get('invoice') ? 'invoice' : 'reciept'));

            } catch (Exception $e) {
                $ordersWithErrors[] = $idOrder;
            }
        }

        $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

        return $this->redirectToRoute('admin_orders_index');
    }

    /**
     * @param int $cartId
     * @param string|null $document
     * 
     * @return bool
     */
    private function updateDocument(int $cartId, string $document = null): bool
    {
        $currentChoice = x13paragonlubfaktura::getByIdCart($cartId);

        $result = false;

        if ($document) {
            return x13paragonlubfaktura::setForCart($cartId, $document);
        }

        if ($currentChoice) {
            $result = Db::getInstance()->query('UPDATE `'._DB_PREFIX_.'x13recieptorinvoice` roi SET roi.`recieptorinvoice` = IF(roi.`recieptorinvoice` = "invoice", "reciept", "invoice") WHERE roi.`id_cart` = '.(int) $cartId);
        } else {
            $result = x13paragonlubfaktura::setForCart($cartId, 'invoice');
        }

        return (bool) $result;
    }
}
