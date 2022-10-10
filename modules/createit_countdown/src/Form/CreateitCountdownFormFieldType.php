<?php

namespace PrestaShop\Module\CreateitCountdown\Form;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateitCountdownFormFieldType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount_value', NumberType::class, [
                'label' => 'Free shipping starts at',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('background_color', ColorType::class, [
                'label' => 'Background Color',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('border_color', ColorType::class, [
                'label' => 'Border Color',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('text_color', ColorType::class, [
                'label' => 'Text Color',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}