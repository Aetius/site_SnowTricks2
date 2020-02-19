<?php


namespace App\Form\Comment;


use App\Form\Comment\DTO\CommentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'required' => true,
                'label' => "form.contentComment",
                'attr' =>
                    ['style' => 'height: 35px'],
                'constraints'=> new NotBlank(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentDTO::class,
            'translation_domain' => 'forms',
        ]);
    }
}