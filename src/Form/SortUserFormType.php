<?php

namespace App\Form;

use App\Form\DataTransformer\RolesTransformer;
use App\Objects\SortUserObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortUserFormType extends AbstractType
{

    public function __construct(
        private RolesTransformer $transformer
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class,[
                'required' => false,
                'label' => 'form.email'
            ])
            ->add('page', HiddenType::class)
            ->add('sort', SubmitType::class, [
                'label' => 'form.sort'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
               'data_class' => SortUserObject::class,
                'translation_domain' => 'form'
            ]);
    }
}