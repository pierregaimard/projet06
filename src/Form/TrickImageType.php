<?php

namespace App\Form;

use App\Entity\TrickImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = null;
        $builder
            ->add('uploadedFile', FileType::class, [
                'data_class' => UploadedFile::class,
                'auto_initialize' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickImage::class,
        ]);
    }
}
