<?php

namespace App\Form\User;


use App\Form\User\DTO\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LostPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'form.login',
                 'constraints'=> new NotBlank(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => false,
            'data_class' => UserDTO::class,
            'translation_domain' => 'forms',
        ]);
    }
}
