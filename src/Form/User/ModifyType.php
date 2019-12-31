<?php

namespace App\Form\User;



use App\Entity\User;
use App\Form\Email\ModifyEmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emails', CollectionType::class, [
                'entry_type'   => ModifyEmailType::class,
                'allow_add'    => true
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'La confirmation du mot de passe est incorrecte',
                'required' => false,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe']
            ])
            /*->add('email', EmailType::class)*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>User::class,
            'translation_domain'=>'forms'
        ]);
    }
}
