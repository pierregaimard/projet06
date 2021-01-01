<?php

namespace App\Form;

use App\Entity\AccountPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;
        $builder
            ->add('password', PasswordType::class, [
                'attr' => ['placeholder' => 'Current password'],
                'label' => 'Current password',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Password and Repeated password must match.',
                'first_options' => [
                    'attr' => ['placeholder' => 'Repeat password'],
                    'label' => 'New password',
                ],
                'second_options' => [
                    'attr' => ['placeholder' => 'Repeat password'],
                    'label' => 'Repeat password',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccountPassword::class,
        ]);
    }
}

