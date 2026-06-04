<?php

namespace App\Form;

use App\Entity\AttendanceSignature;
use App\Enum\AttendanceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceSignatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Je déclare',
                'choices' => [
                    'Je suis présent(e)' => AttendanceType::PENDING_PRESENT->value,
                    'Je suis en retard' => AttendanceType::PENDING_LATE->value,
                    'Je suis absent(e)' => AttendanceType::PENDING_ABSENT->value,
                ],
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'data' => $options['initial_status'],
                'choice_value' => fn(?string $value) => $value,
                'choice_label' => fn(string $value) => $value === AttendanceType::PENDING_PRESENT->value ? 'Je suis présent(e)' : ($value === AttendanceType::PENDING_LATE->value ? 'Je suis en retard' : 'Je suis absent(e)'),
                'choice_translation_domain' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire (optionnel)',
                'required' => false,
                'attr' => ['rows' => 3],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AttendanceSignature::class,
            'initial_status' => null,
        ]);
    }
}
