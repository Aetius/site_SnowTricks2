<?php

namespace App\Form\Picture;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);

        $builder
            ->add('selectedPicture', ChoiceType::class, [
                'choices'=>[
                    'Yes'=> 1,
                    'No'=> 0
                ],
                'label'=>'form.selectedPicture',
            ])
            ->add('filePicture', FileType::class, [
                'mapped'=>false,
                'label'=>'form.filePicture',
                'required'=>false,
                'constraints'=>
                [new Image( ['maxSize'=>"3M"])]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain'=>'forms',
        ]);
    }


}
