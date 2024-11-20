<?php

namespace App\Form;

use App\Entity\Tasks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $options['is_admin'];

        $builder
            ->add('task', TextType::class, [
                'required' => false,
                'disabled' => !$isAdmin, 
            ])
            ->add('dateR', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'disabled' => !$isAdmin, 
            ])
            ->add('etat', TextType::class, [
                'required' => false,
            ])
            ->add('typeT', TextType::class, [
                'required' => false,
                'disabled' => !$isAdmin, 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
            'is_admin' => true, 
        ]);
    }
}
