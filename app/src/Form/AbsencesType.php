<?php

namespace App\Form;

use App\Entity\Absences;
use App\Entity\Documents;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AbsencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('end_date', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn(User $u) => $u->getFirstname() . ' ' . $u->getLastname(),
                'property_path' => 'userId',
            ])
            ->add('document', EntityType::class, [
                'class' => Documents::class,
                'choice_label' => 'title',
                'required' => false,
                'property_path' => 'documentId',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absences::class,
        ]);
    }
}
