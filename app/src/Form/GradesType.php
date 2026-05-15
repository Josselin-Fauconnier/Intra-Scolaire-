<?php

namespace App\Form;

use App\Entity\Grades;
use App\Entity\Projects;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Enum\GradeStatus;

class GradesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grade')
            ->add('comments', null, ['required' => false])
            ->add('submission', null, ['required' => false])
            ->add('update_history', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('status', EnumType::class, [
                'class' => GradeStatus::class,
                'required' => false,
            ])
            ->add('project', EntityType::class, [
                'class' => Projects::class,
                'choice_label' => 'title',
                'property_path' => 'projectId',
            ])
            ->add('student', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn(User $u) => $u->getFirstname() . ' ' . $u->getLastname(),
                'property_path' => 'studentId',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Grades::class,
        ]);
    }
}
