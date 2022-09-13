<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CreateitAccordionDefinitionFactory extends AbstractGridDefinitionFactory
{

    const GRID_ID = 'createit_accordion';

    protected function getId()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return 'CreateIT Accordion Grid';
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new BulkActionColumn('bulk'))
                ->setOptions([
                    'bulk_field' => 'id_createit_accordion',
                ])
            )
            ->add((new DataColumn('id_createit_accordion'))
                ->setName('Id')
                ->setOptions([
                    'field' => 'id_createit_accordion'
                ])
            )
            ->add((new DataColumn('field_name'))
                ->setName('Field Name')
                ->setOptions([
                    'field' => 'field_name'
                ])
            )
            ->add((new ActionColumn('actions'))
                ->setName('Actions')
                ->setOptions([
                    'actions' => (new RowActionCollection())
                        ->add((new LinkRowAction('edit'))
                            ->setName('Edit')
                            ->setIcon('edit')
                            ->setOptions([
                                'route' => 'createit_accordion_edit',
                                'route_param_name' => 'createitAccordionId',
                                'route_param_field' => 'id_createit_accordion',
                                'clickable_row' => true,
                            ])
                        )
                        ->add((new SubmitRowAction('delete'))
                            ->setName('Delete')
                            ->setIcon('delete')
                            ->setOptions([
                                'method' => 'DELETE',
                                'route' => 'createit_accordion_delete',
                                'route_param_name' => 'createitAccordionId',
                                'route_param_field' => 'id_createit_accordion',
                                'confirm_message' => 'Delete selected item?',
                            ])
                        ),
                ])
            )

            ;
    }

    public function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter('id_createit_accordion', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'ID'
                    ],
                ])
                ->setAssociatedColumn('id_createit_accordion')
            )
            ->add((new Filter('field_name', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Field Name'
                    ],
                ])
                ->setAssociatedColumn('field_name')
            )
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'reset_route' => 'admin_common_reset_search_by_filter_id',
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID,
                    ],
                    'redirect_route' => 'createit_accordion_index',
                ])
                ->setAssociatedColumn('actions')
            )
            ;
    }
}