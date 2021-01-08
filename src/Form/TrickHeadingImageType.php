<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickImage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickHeadingImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('headingImage', EntityType::class, [
                'class' => TrickImage::class,
                'choices' => $options['image_choices'],
                'choice_label' => 'fileName'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'image_choices' => null
        ]);
    }
}
