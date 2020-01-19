<?php

namespace App\Form\Trick;

use App\Entity\Trick;
use App\Form\Trick\DTO\CreateDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);

        $builder
            ->add('description', TextareaType::class)
            ->add('title', TextType::class)
            ->add('filePicture', FileType::class, [
                'mapped'=>false,
                'multiple'=>true,



            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            'translation_domain'=>'forms',
        ]);
    }


}
