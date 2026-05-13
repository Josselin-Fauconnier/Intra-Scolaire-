<?php

namespace App\Form;

use App\Entity\Documents;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class DocumentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('title')
            ->add('user', EntityType::class, [ // Souvent le champ s'appelle 'user' et non 'user_id' dans l'entité
                'class' => User::class, // Avec un U majuscule !
                'choice_label' => 'email', // C'est plus sympa d'afficher l'email que l'ID dans la liste
            ])
            ->add('attachment', FileType::class, [
                'label' => 'Fichier du cours (PDF)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '5M', // Argument nommé (PHP 8)
                        mimeTypes: [
                            'application/pdf',
                            'application/x-pdf',

                        ],
                        mimeTypesMessage: "Merci d'uploader un document valide (PDF )"
                    )
                ],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
