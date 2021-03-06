<?php
namespace App\Form;

use App\Entity\User;
use App\Form\DataTransformer\RolesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
           ->add('email', EmailType::class, [
               'label' => 'form.email'
           ])
           ->add('password', TextType::class, [
               'label' => 'form.password'
           ])
           ->add('roles', ChoiceType::class, [
               'choices' => [
                   'form.user_role.user' => 'ROLE_USER',
                   'form.user_role.manager' => 'ROLE_MANAGER',
                   'form.user_role.admin' => 'ROLE_ADMIN'
               ]
           ])
           ->add('is_verified', CheckboxType::class, [
               'label' => 'form.isVerified'
           ])
           ->add('save', SubmitType::class, [
               'label' => 'form.save'
           ]);

        $builder->get('roles')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => User::class,
            'translation_domain' => 'form'
        ]);
    }
}
