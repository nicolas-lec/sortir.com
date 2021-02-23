<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;


class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    public function getAll (int $page)
    {
        return $this->createQueryBuilder('sortieA')
            ->setMaxResults(15)
            ->setFirstResult((abs($page) - 1) * 9)
            ->orderBy('sortieA.dateHeureDebut', 'desc')->getQuery()->getResult();
    }

}
