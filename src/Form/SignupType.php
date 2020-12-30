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
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;
        $builder
            ->add('firstName', TextType::class, ['attr' => ['placeholder' => 'firstname', 'autofocus' => true]])
            ->add('lastName', TextType::class, ['attr' => ['placeholder' => 'lastname']])
            ->add('username', TextType::class, ['attr' => ['placeholder' => 'username']])
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'email']])
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'attr' => ['placeholder' => 'password'],
                    'label' => 'Password',
                    'always_empty' => false
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['signup']
        ]);
    }
}
