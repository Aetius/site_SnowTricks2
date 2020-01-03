<?php

namespace App\Form\User;

use App\Entity\User;
use App\Validator\LoginNotExist;
use App\Validator\PasswordValid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'required' => false,
                'label' => 'form.login',
                'constraints' => [
                    new Length(['min' => 3]),
                    new LoginNotExist()

                ]
            ])
            ->add('currentPassword', PasswordType::class, [
                'invalid_message' => 'currentPassword.invalidMessage',
                'required' => false,
                'label' => 'label.currentPassword',
                'constraints' => [
                    new PasswordValid()
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "password.invalidMessage",
                'required' => false,
                'first_options' => ['label' => "label.password"],
                'second_options' => ['label' => "label.passwordConfirm"]
            ])
            ->add('emailUser', EmailType::class, [
                'required' => false,
                'label' => 'form.email'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',

        ]);
    }
}
