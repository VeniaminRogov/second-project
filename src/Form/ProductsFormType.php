<?php

namespace App\Form;


use App\Entity\Categories;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoryId', EntityType::class, [
                'label' => 'Category',
                'class' => Categories::class,
                'choice_label' => 'Name'
            ])
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('price', IntegerType::class)
            ->add('quantity', IntegerType::class)
            ->add('isAvailable', CheckboxType::class, [
                'label' => 'Is active?',
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class
        ]);
    }
}