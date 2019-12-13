<?php

namespace App\Form\User;

use App\Entity\Email;
use App\Entity\User;
use App\Form\Email\CreateEmailType;
use App\Form\Email\EmailType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('password')
            ->add('email', CollectionType::class, [
                'entry_type'=>CreateEmailType::class,
                'allow_add'=>true,
                'prototype'=> true,
                'allow_delete'=>true,
                'by_reference'=>false,


            ])
     /*       ->add('email', EntityType::class, [
                'class'=> CreateEmailType::class
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain'=>'forms',
        ]);
    }
}
