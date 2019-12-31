<?php

namespace App\Form\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Validator\LoginNotExist;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5]),
                    new LoginNotExist()
                    /*new UniqueEntity(
                        ['entityClass'=>User::class, 'fields'=> 'login' ]
                    )*/
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'DJEHZHG',
                'required' => true,
                'first_options' => ['label' => 'label.password'],
                'second_options' => ['label' => 'Confirmation du mot de passe']
            ])
            ->add('emailUser', EmailType::class)
           /* ->add('email', EmailType::class)/*       ->add('email', EntityType::class, [
                'class'=> CreateEmailType::class
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          /*  'constraints'=>[
                new UniqueEntity(['entityClass'=>User::class, 'fields'=>['login']])
            ],*/
            'translation_domain' => 'forms',
        ]);
    }
}
