<?php

namespace App\Form\User;

use App\Form\User\DTO\EditUserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
                'required' => true
            ])
            ->add('login', TextType::class, [
                'required' => false,
                'label' => 'form.login'
            ])
            ->add('currentPassword', PasswordType::class, [
                'invalid_message' => 'currentPassword.invalidMessage',
                'required' => false,
                'label' => 'form.currentPassword'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "password.invalidMessage",
                'required' => false,
                'first_options' => ['label' => "form.password"],
                'validation_groups' => ["test"],
                'label' => 'form.password',
                'second_options' => ['label' => "form.passwordConfirm"]
            ])
            ->add('emailUser', EmailType::class, [
                'required' => false,
                'label' => 'form.email'
            ])
            ->add('picture', FileType::class, [
                "required" => false,
                'label' => 'form.picture',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
            'data_class' => EditUserDTO::class,

        ]);
    }
}
