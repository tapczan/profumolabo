<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitCountdown\Controller\Admin;

use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Handler\FormHandler;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;

class CreateitCountdownController extends FrameworkBundleAdminController
{
    public function indexAction(Request $request)
    {
        /**
         * @var $formBuilder FormBuilder
         */
        $formBuilder = $this->get('prestashop.module.createit_countdown.form.identifiable_object.builder.createit_countdown_form_builder');
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        /**
         * @var $formHandler FormHandler
         */
        $formHandler = $this->get('prestashop.module.createit_countdown.form.identifiable_object.handler.createit_countdown_form_data_handler');

        $result = $formHandler->handle($form);

        if(null !== $result->getIdentifiableObjectId()) {
            $this->addFlash('success', 'Success');

            return $this->redirectToRoute('createit_countdown_index');
        }

        return $this->render(
            '@Modules/createit_countdown/views/templates/admin/index.html.twig', [
                'form' => $form->createView()
        ]);
    }

}