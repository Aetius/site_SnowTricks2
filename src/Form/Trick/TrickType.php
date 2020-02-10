<?php

namespace App\Form\Trick;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'=>'form.title',
            ])
            ->add('datePublication', TextType::class, [
                'required'=>false])
            ->add('description', TextareaType::class, [
                'label'=>'form.description',
            ])
            ->add('publicated', CheckboxType::class, [
                'required'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'translation_domain'=>'forms'
        ]);
    }
}
