<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Controller\Admin;

use PrestaShop\Module\CreateitAccordion\Grid\Definition\Factory\CreateitAccordionDefinitionFactory;
use PrestaShop\Module\CreateitAccordion\Grid\Filters\CreateitAccordionFilters;
use PrestaShop\PrestaShop\Core\Foundation\Database\EntityNotFoundException;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\ModuleActivated;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ModuleActivated(moduleName="createit_accordion", redirectRoute="admin_module_manage")
 */
class CreateitAccordionAdminController extends FrameworkBundleAdminController
{
    /**
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))", message="Access denied.")
     * @param CreateitAccordionFilters $createitAccordionFilters
     * @return Response|null
     */
    public function indexAction(CreateitAccordionFilters $createitAccordionFilters)
    {
        $gridFactory = $this->get('prestashop.module.createit_accordion.grid.factory.accordion');

        $grid = $gridFactory->getGrid($createitAccordionFilters);

        return $this->render(
            '@Modules/createit_accordion/views/templates/admin/index.html.twig', [
            'grid' => $this->presentGrid($grid),
            'layoutTitle' => 'createIT Accordion Field',
            'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
            'enableSidebar' => true,
        ]);
    }

    /**
     * @param Request $request
     * @return Response|RedirectResponse|null
     */
    public function createAction(Request $request)
    {
        $formBuilder = $this->get('prestashop.module.createit_accordion.form.identifiable_object.builder.createit_accordion_form_builder');
        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        $formHandler = $this->get('prestashop.module.createit_accordion.form.identifiable_object.handler.createit_accordion_form_handler');
        $result = $formHandler->handle($form);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', 'Successfully added a custom field.');

            return $this->redirectToRoute('createit_accordion_index');
        }

        return $this->render(
            '@Modules/createit_accordion/views/templates/admin/create.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    public function searchAction(Request $request)
    {
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('prestashop.module.createit_accordion.grid.definition.factory.accordion'),
            $request,
            CreateitAccordionDefinitionFactory::GRID_ID,
            'createit_accordion_index'
        );
    }

    /**
     * @param Request $request
     * @param $createitAccordionId
     * @return RedirectResponse|Response|null
     */
    public function editAction(Request $request, $createitAccordionId)
    {
        $formBuilder = $this->get('prestashop.module.createit_accordion.form.identifiable_object.builder.createit_accordion_form_builder');
        $form = $formBuilder->getFormFor((int) $createitAccordionId);
        $form->handleRequest($request);

        $formHandler = $this->get('prestashop.module.createit_accordion.form.identifiable_object.handler.createit_accordion_form_handler');
        $result = $formHandler->handleFor((int) $createitAccordionId, $form);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', 'Successful update.');

            return $this->redirectToRoute('createit_accordion_index');
        }

        return $this->render(
            '@Modules/createit_accordion/views/templates/admin/create.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @param $createitAccordionId
     * @return RedirectResponse
     */
    public function deleteAction($createitAccordionId)
    {
        $repository = $this->get('prestashop.module.createit_accordion.createit_accordion_repository');

        try {
            $accordion = $repository->findOneById($createitAccordionId);
        } catch (EntityNotFoundException $e) {
            $accordion = null;
        }

        if (null !== $accordion) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->remove($accordion);
            $em->flush();

            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );
        } else {
            $this->addFlash(
                'error',
                'Cannot find accordion'
            );
        }

        return $this->redirectToRoute('createit_accordion_index');
    }

    private function getToolbarButtons()
    {
        return [
            'add' => [
                'desc' => 'Add new field',
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('createit_accordion_create'),
            ]
        ];
    }
}