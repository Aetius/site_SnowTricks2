<?php

namespace App\Form\User;

use App\Entity\User;
use App\Validator\LoginNotExist;
use App\Validator\PasswordValid;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AdminCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
           $builder
            /*   ->get('login')*/
               ->add('users', CollectionType::class, [
                   'entry_type' => AdminType::class
            ]);



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',

        ]);
    }
}
