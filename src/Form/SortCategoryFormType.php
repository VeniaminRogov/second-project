<?php

namespace App\Form;

use App\Entity\Categories;
use App\Objects\SortCategoryObject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class,  [
            'label' => 'Category',
            'class' => Categories::class,
            'choice_label' => 'Name'
        ])
            ->add('page', HiddenType::class)
            ->add('sort', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortCategoryObject::class,
        ]);
    }
}