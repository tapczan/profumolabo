<?php

namespace PrestaShop\Module\CreateITCustomField\Controller\Admin;

use Doctrine\ORM\EntityNotFoundException;
use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfield;
use PrestaShop\Module\CreateITCustomField\Form\CreateitCustomFieldType;
use PrestaShop\Module\CreateITCustomField\Grid\Definition\Factory\CreateitCustomFieldDefinitionFactory;
use PrestaShop\Module\CreateITCustomField\Grid\Filters\CreateitCustomFieldFilters;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilder;
use PrestaShop\PrestaShop\Core\Grid\GridFactory;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\Request;

class CreateITCustomFieldController extends FrameworkBundleAdminController
{
    public function indexAction(CreateitCustomFieldFilters $createitCustomFieldFilters)
    {
        $customFieldgridFactory = $this->get('prestashop.module.createit_custom_field.grid.factory.custom_fields');

        $grid = $customFieldgridFactory->getGrid($createitCustomFieldFilters);

        return $this->render(
            '@Modules/createit_customfield/views/templates/admin/index.html.twig', [
                'grid' => $this->presentGrid($grid),
                'layoutTitle' => 'createIT Custom Field',
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
                'enableSidebar' => true,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function createAction(Request $request)
    {
        /**
         * @var $customFieldFormBuilder FormBuilder
         */
        $customFieldFormBuilder = $this->get('prestashop.module.createit_custom_field.form.identifiable_object.builder.createit_custom_field_form_builder');
        $form = $customFieldFormBuilder->getForm();
        $form->handleRequest($request);

        $customFieldFormHandler = $this->get('prestashop.module.createit_custom_field.form.identifiable_object.handler.createit_custom_field_handler');
        $result = $customFieldFormHandler->handle($form);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash('success', 'Successfully added a custom field.');

            return $this->redirectToRoute('createit_custom_field');
        }

        return $this->render(
            '@Modules/createit_customfield/views/templates/admin/create.html.twig',
        [
            'form' => $form->createView()
        ]);

    }

    /**
     * @param Request $request
     * @param CreateitProductCustomfield $createitProductCustomfield
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    public function editAction(Request $request, CreateitProductCustomfield $createitProductCustomfield)
    {
        /**
         * @var $customFieldFormBuilder FormBuilder
         */
        $customFieldFormBuilder = $this->get('prestashop.module.createit_custom_field.form.identifiable_object.builder.createit_custom_field_form_builder');

        $form = $customFieldFormBuilder->getFormFor($createitProductCustomfield->getId());
        $form->handleRequest($request);

        $customFieldFormHandler = $this->get('prestashop.module.createit_custom_field.form.identifiable_object.handler.createit_custom_field_handler');
        $result = $customFieldFormHandler->handleFor($createitProductCustomfield->getId(), $form);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', 'Successfully added a custom field.');

            return $this->redirectToRoute('createit_custom_field');
        }

        return $this->render(
            '@Modules/createit_customfield/views/templates/admin/edit.html.twig',
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param CreateitProductCustomfield $createitProductCustomfield
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(CreateitProductCustomfield $createitProductCustomfield)
    {

        $em = $this->get('doctrine.orm.entity_manager');

        $em->remove($createitProductCustomfield);
        $em->flush();

        $this->addFlash(
            'success',
            'Successful deletion.'
        );

        return $this->redirectToRoute('createit_custom_field');

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function searchAction(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
                $this->get('prestashop.module.createit_custom_field.grid.definition.factory.custom_fields'),
                $request,
                CreateitCustomFieldDefinitionFactory::GRID_ID,
                'createit_custom_field'
        );

    }

    private function getToolbarButtons()
    {
        return [
            'add' => [
                'desc' => 'Add new field',
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('createit_custom_field_create'),
            ]
        ];
    }
}