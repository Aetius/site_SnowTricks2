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
            'label' => 'Is active ?'
            ])
            ->add('role', ChoiceType::class, [
                'choices'=>[
                   'User'=> 'ROLE_USER',
                   'Editor'=> 'ROLE_EDITOR',
                   'Administrator'=> 'ROLE_ADMIN'
                ],
               'invalid_message' => 'roles.invalidMessage',
               'label' => 'label_roles',
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
