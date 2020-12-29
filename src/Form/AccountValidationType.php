<?php

namespace App\Form;

use App\Entity\AccountValidation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;
        $builder
            ->add('username', TextType::class, ['attr' => ['placeholder' => 'username', 'autofocus' => true]])
            ->add('password', PasswordType::class, ['attr' => ['placeholder' => 'password']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccountValidation::class,
        ]);
    }
}
