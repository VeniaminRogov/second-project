<?php

namespace App\Form;


use App\Entity\Category;
use App\Entity\Products;
use App\Repository\CategoryRepository;
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
            ->add('category', EntityType::class, [
                'label' => 'form.category',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('name', TextType::class, [
                'label' => 'form.name'
            ])
            ->add('description', TextType::class, [
                'label' => 'form.description'
            ])
            ->add('price', IntegerType::class, [
                'label' => 'form.price'
            ])
            ->add('quantity', IntegerType::class,[
                'label' => 'form.quantity'
            ])
            ->add('isAvailable', CheckboxType::class, [
                'label' => 'form.isActive',
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'label' => 'form.image',
                'mapped' => false,
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'form.add'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
            'translation_domain' => 'form'
        ]);
    }
}