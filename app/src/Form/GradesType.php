<?php

namespace App\Form;

use App\Entity\Grades;
use App\Entity\Projects;
use App\Entity\User;
use App\Repository\ProjectsRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Enum\GradeStatus;

class GradesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $options['current_user'];

        $builder
            ->add('grade')
            ->add('comments', null, ['required' => false])
            ->add('submission', null, ['required' => false])
            ->add('status', EnumType::class, [
                'class' => GradeStatus::class,
                'required' => false,
            ])
            ->add('project', EntityType::class, [
                'class' => Projects::class,
                'choice_label' => 'title',
                'property_path' => 'projectId',
                'query_builder' => function (ProjectsRepository $er) use ($currentUser) {
                    $qb = $er->createQueryBuilder('p')
                        ->join('p.promotion', 'pr')
                        ->orderBy('p.title', 'ASC');

                    if ($currentUser && !in_array('ROLE_ADMIN', $currentUser->getRoles(), true)) {
                        $qb->where('pr.professor = :prof')
                           ->setParameter('prof', $currentUser);
                    }

                    return $qb;
                },
            ])
            ->add('student', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn(User $u) => $u->getFirstname() . ' ' . $u->getLastname(),
                'property_path' => 'studentId',
                'query_builder' => fn(UserRepository $er) => $er->createQueryBuilder('u')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%ROLE_STUDENT%')
                    ->orderBy('u.lastname', 'ASC'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Grades::class,
            'current_user' => null,
        ]);
        $resolver->setAllowedTypes('current_user', ['null', User::class]);
    }
}
