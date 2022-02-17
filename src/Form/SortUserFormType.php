<?php

namespace App\Form;

use App\Form\DataTransformer\RolesTransformer;
use App\Objects\SortUserObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('email', EmailType::class,[
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'user' => 'ROLE_USER',
                    'manager' => 'ROLE_MANAGER',
                    'admin' => 'ROLE_ADMIN'
                ]
            ])
            ->add('sort', SubmitType::class);

        $builder->get('roles')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
               'data_class' => SortUserObject::class
            ]);
    }
}