<?php

namespace App\Form\Trick;

use App\Entity\Trick;
use App\Form\Trick\DTO\CreateDTO;
use App\Form\Trick\DTO\TrickDTO;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Unique;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);

        $builder
            ->add('id', HiddenType::class, [
                'required'=>true
            ])
            ->add('description', TextareaType::class, [
                "required"=>false
            ])
            ->add('title', TextType::class, [
                "required"=>false
            ])
            ->add('filePicture', FileType::class, [
                'multiple'=>true,
                "required"=>false
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> TrickDTO::class,
            'translation_domain'=>'forms',
        ]);
    }


}
