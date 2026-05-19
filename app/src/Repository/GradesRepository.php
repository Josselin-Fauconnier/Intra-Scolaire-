<?php

namespace App\Repository;

use App\Entity\Grades;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grades>
 */
class GradesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grades::class);
    }

    public function findByStudent(User $student): array
    {
        return $this->createQueryBuilder('g')
            ->where('g.student = :student')
            ->setParameter('student', $student)
            ->orderBy('g.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByTeacher(User $teacher): array
    {
        return $this->createQueryBuilder('g')
            ->join('g.project', 'p')
            ->join('p.promotion', 'pr')
            ->where('pr.professor = :teacher')
            ->setParameter('teacher', $teacher)
            ->orderBy('g.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
