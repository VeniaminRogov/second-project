<?php

namespace App\Form;

use App\Command\CreateUserCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.email'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'data' => '',
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.repeat']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'form.save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => \App\Messenger\CreateUserCommand::class,
            'translation_domain' => 'form'
        ]);
    }
}