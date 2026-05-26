<?php

namespace App\Repository;

use App\Entity\Absences;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Absences>
 */
class AbsencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absences::class);
    }

    public function findByStudent(User $student): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :student')
            ->setParameter('student', $student)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByTeacher(User $teacher): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.user', 'u')
            ->join('u.promotionUsers', 'pu')
            ->join('pu.promotion', 'pr')
            ->where('pr.professor = :teacher')
            ->setParameter('teacher', $teacher)
            ->groupBy('a.id')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
