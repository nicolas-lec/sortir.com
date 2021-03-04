<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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

    public function getById(int $id)
    {
        return $this->createQueryBuilder('sortie')
            ->where('sortie.id = :id')->setParameter('id', $id)
            ->getQuery()->getSingleResult();
    }

    public function findSearch(SearchData $search)
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select()
            ->join()
            ->leftJoin('');

        if (strlen(trim ($search->getQ ()))>0) {
            $query
                ->andWhere('s.nom LIKE :q')
                ->setParameter('q', "%{$search->getQ ()}%");
        }
        if(!empty($search->getSite())) {
            $query->andWhere('s.site = :site')
                ->setParameter('site', $search->getSite());
    }
        return $query->getQuery ()->getResult ();
    }

}
