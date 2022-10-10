<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Form;

use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateitAccordionType extends TranslatorAwareType
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