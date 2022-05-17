<?php

namespace PrestaShop\Module\CreateITCustomField\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollectionInterface;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CreateitCustomFieldDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'custom_field';

    protected function getId()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return 'CreateitCustomGrid';
    }

    protected function getColumns()
    {
         return (new ColumnCollection())
             ->add((new BulkActionColumn('bulk'))
                 ->setOptions([
                     'bulk_field' => 'id_createit_products_customfield',
                 ])
             )

             ->add((new DataColumn('id_createit_products_customfield'))
                 ->setName('Id')
                 ->setOptions([
                     'field' => 'id_createit_products_customfield'
                 ])
             )

             ->add((new DataColumn('field_name'))
                 ->setName('Field Name')
                 ->setOptions([
                     'field' => 'field_name'
                 ])
             )

//             ->add((new DataColumn('label_name'))
//                 ->setName('Label Name')
//                 ->setOptions([
//                     'field' => 'label_name'
//                 ])
//             )
             ->add((new ActionColumn('actions'))
                 ->setName('Actions')
                 ->setOptions([
                     'actions' => (new RowActionCollection())
                         ->add((new LinkRowAction('edit'))
                             ->setName('Edit')
                             ->setIcon('edit')
                             ->setOptions([
                                 'route' => 'createit_custom_field_edit',
                                 'route_param_name' => 'createitProductCustomfield',
                                 'route_param_field' => 'id_createit_products_customfield',
                                 'clickable_row' => true,
                             ])
                         )

                         ->add((new SubmitRowAction('delete'))
                             ->setName('Delete')
                             ->setIcon('delete')
                             ->setOptions([
                                 'method' => 'DELETE',
                                 'route' => 'createit_custom_field_dete',
                                 'route_param_name' => 'createitProductCustomfield',
                                 'route_param_field' => 'id_createit_products_customfield',
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
            ->add((new Filter('id_createit_products_customfield', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'ID'
                    ],
                ])
                ->setAssociatedColumn('id_createit_products_customfield')
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

//            ->add((new Filter('label_name', TextType::class))
//                ->setTypeOptions([
//                    'required' => false,
//                    'attr' => [
//                        'placeholder' => 'Label Name'
//                    ],
//                ])
//                ->setAssociatedColumn('label_name')
//            )


            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'reset_route' => 'admin_common_reset_search_by_filter_id',
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID,
                    ],
                    'redirect_route' => 'createit_custom_field',
                ])
                ->setAssociatedColumn('actions')
            )



            ;

    }

}