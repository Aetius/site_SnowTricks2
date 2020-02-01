<?php

namespace App\Form\Trick;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Form\Trick\DTO\TrickDTO;
use App\Form\TrickGroup\TrickGroupType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('description', TextareaType::class,  [
                'required'=> true,
            ])
            ->add('title', TextType::class, [
                'required'=> true,
            ])
            ->add('pictureFiles', FileType::class, [
                'required'=>true,
                'multiple'=>true,
            ])
            ->add('trickGroup', EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label' => 'name',
                'label'=> 'Choose one of the group',
                'multiple' => false,
                'required' => false,
            ])
            ->add('trickGroupAdd', TextType::class, [
                'label'=> 'Or create a new group',
                'required' => false,
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickDTO::class,
            'translation_domain'=>'forms',
        ]);
    }


}
