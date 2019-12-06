<?php

    namespace App\Form;

    use App\Entity\Trick;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class TrickPublicType extends TrickType  //ne pas utiliser : pb avec le jeton csrf
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('description', TextType::class)
                ->add('title', TextType::class)

            ;
        }


    }
