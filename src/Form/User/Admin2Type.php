<?php

namespace App\Form\User;

use App\Entity\User;
use App\Validator\LoginNotExist;
use App\Validator\PasswordValid;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;


class Admin2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*   ->get('login')*/
            ->add('isActivate', CheckboxType::class, [
                'label' => 'Is actif ?',
                'required'=>false
            ])
            ->add('roles', ChoiceType::class, [
                'choices'=>[
                    'User'=> 'ROLE_USER',
                    'Editor'=> 'ROLE_EDITOR',
                    'Administrator'=> 'ROLE_ADMIN'
                ],
               /* 'multiple'=> true,*/
                'invalid_message' => 'roles.invalidMessage',
                'required' => true,
                /*'label' => 'label_roles',*/
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
            /*'data_class' => User::class*/
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'update_admin',
           /* 'data_class'=>User::class,*/

        ]);
    }
}
