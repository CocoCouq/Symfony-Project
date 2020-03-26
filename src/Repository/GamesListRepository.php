<?php

namespace App\Repository;

use App\Entity\GamesList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GamesList|null find($id, $lockMode = null, $lockVersion = null)
 * @method GamesList|null findOneBy(array $criteria, array $orderBy = null)
 * @method GamesList[]    findAll()
 * @method GamesList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamesListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GamesList::class);
    }

    /**
     * @return GamesList[]
     */
    public function no_materiel(): array
    {
        return $this->createQueryBuilder('g')
            ->where('g.materiel = false')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return GamesList[]
     */
    public function last_games(): array
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return GamesList[] Returns an array of GamesList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GamesList
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
