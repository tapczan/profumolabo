<?php

namespace PrestaShop\Module\CreateITCustomField\Form;



use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateitCustomFieldType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_name', TextType::class, [
                'label' => 'Field Name',
                'help' => 'Field name (e.g. Custom text field). Must be in snake case. (snake_case)',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('field_type', ChoiceType::class,
                [
                    'choices' => [
                        'Text' => 0,
                        'Textarea' => 1
                    ],
                    'label' => 'Field Type',
                    'help' => 'Type of field (e.g. Input Box, Text area).',
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
            ->add('label_name', TranslatableType::class,[
                'label' => 'Label Name',
                'help' => 'Name of label (e.g. Custom field).',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class)
            ;
    }
}