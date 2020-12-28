<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;
        $builder
            ->add('username', TextType::class, ['attr' => ['placeholder' => 'username']])
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'attr' => ['placeholder' => 'password'],
                    'label' => 'Password',
                    'always_empty' => false
                ]
            )
            ->add('firstName', TextType::class, ['attr' => ['placeholder' => 'firstname']])
            ->add('lastName', TextType::class, ['attr' => ['placeholder' => 'lastname']])
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'email']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
