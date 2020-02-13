<?php

namespace App\Form\Trick;

use App\Entity\TrickGroup;
use App\Form\Trick\DTO\TrickDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('description', TextareaType::class,  [
                'required'=> true,
                'label'=>'form.description',
            ])
            ->add('title', TextType::class, [
                'required'=> true,
                'data'=>[],
                'label'=>'form.title',
            ])
            ->add('pictureFiles', FileType::class, [
                'required'=>true,
                'multiple'=>true,
                'label'=>'form.picture',
            ])
            ->add('trickGroup', EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label' => 'name',
                'label'=> 'form.trickGroup',
                'multiple' => false,
                'required' => true,
            ])

            ->add('videos', CollectionType::class, [
                'entry_type'=> TextType::class,
                'label'=>'form.videos',
                'required'=>true,
                'allow_add'=>true,
                'data'=>["required"=>[]],
                'entry_options' => [
                    'attr' => ['class' => 'video-box'],
                ],
                'prototype'=>true,
            ])

        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickDTO::class,
            'translation_domain'=>'forms',
            'error_mapping'=>[
                'videos'=>'videos.required',
            ]
        ]);
    }


}
