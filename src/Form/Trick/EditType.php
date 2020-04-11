<?php

namespace App\Form\Trick;


use App\Entity\TrickGroup;
use App\Form\Trick\DTO\TrickDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('id', HiddenType::class, [
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'form.description',
                "required" => false
            ])
            ->add('title', TextType::class, [
                'label' => 'form.title',
                "required" => false
            ])
            ->add('pictureFiles', FileType::class, [
                'multiple' => true,
                'label' => 'form.picture',
                "required" => false,
                'attr' => ['placeholder' => 'form.selectFile'],
            ])
            ->add('trickGroup', EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label' => 'name',
                'label' => 'form.trickGroup',
                'multiple' => false,
                'required' => true,
                'attr' => ['is_selected' => 'id'],
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => TextType::class,
                'label' => 'form.videos',
                'required' => false,
                'allow_add' => true,
                'data' => ["required" => []],
                'entry_options' => [
                    'attr' => [
                        'class' => 'video-box',
                        'placeholder' => 'form.selectFile'],
                ],
                'prototype' => true,
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickDTO::class,
            'translation_domain' => 'forms',
            'validation_groups' => ['edit']
        ]);
    }


}
