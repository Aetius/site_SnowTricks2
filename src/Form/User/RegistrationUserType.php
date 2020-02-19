<?php

namespace App\Form\User;


use App\Form\User\DTO\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'form.login',
                'constraints'=> new NotBlank(),
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'validator.password.invalidMessage',
                'required' => true,
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.passwordConfirm'],
                 'constraints'=> new NotBlank(),
            ])
            ->add('emailUser', EmailType::class, [
                'label' => 'form.email',
                'constraints'=> new NotBlank(),
            ])
            ->add('picture', FileType::class, [
                'label' => 'form.picture',
                'constraints'=> new NotBlank(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
            'translation_domain' => 'forms',
        ]);
    }
}
