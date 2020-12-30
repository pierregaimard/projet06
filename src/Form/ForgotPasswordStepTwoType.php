<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForgotPasswordStepTwoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;
        $builder
            ->add('username', TextType::class, ['attr' => ['placeholder' => 'Username', 'autofocus' => true,]])
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'attr' => ['placeholder' => 'password'],
                    'label' => 'New password',
                    'always_empty' => false
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['forgot_password_step2']
        ]);
    }
}
