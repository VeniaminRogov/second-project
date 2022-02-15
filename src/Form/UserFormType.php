<?php
namespace App\Form;

use App\Entity\User;
use App\Form\DataTransformer\RolesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function __construct(
        private RolesTransformer $transformer
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('email', EmailType::class)
           ->add('password', TextType::class)
           ->add('roles', ChoiceType::class, [
               'choices' => [
                   'user' => 'ROLE_USER',
                   'manager' => 'ROLE_MANAGER',
                   'admin' => 'ROLE_ADMIN'
               ]
           ])
           ->add('save', SubmitType::class);

        $builder->get('roles')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => User::class
        ]);
    }
}
