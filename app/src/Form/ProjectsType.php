<?php

namespace App\Form;

use App\Entity\Projects;
use App\Entity\Promotions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProjectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('visibility', CheckboxType::class, [
                'required' => false,
            ])
            ->add('description', null, ['required' => false])
            ->add('google_drive', null, ['required' => false])
            ->add('due_date', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('promotion', EntityType::class, [
                'class' => Promotions::class,
                'choice_label' => 'name',
                'property_path' => 'promotionId',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
        ]);
    }
}
