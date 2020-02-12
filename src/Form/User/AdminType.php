<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isActivate', null, [
            'label' => 'form.isActivate'
            ])
            ->add('role', ChoiceType::class, [
                'choices'=>[
                   'form.ROLE_USER'=> 'ROLE_USER',
                   'form.ROLE_EDITOR'=> 'ROLE_EDITOR',
                   'form.ROLE_ADMIN'=> 'ROLE_ADMIN'
                ],
               'invalid_message' => 'form.roles.invalidMessage',
               'label' => 'form.role',
            ]);
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
            'data_class' => User::class,

        ]);
    }
}
