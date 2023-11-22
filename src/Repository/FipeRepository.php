<?php

namespace App\Repository;

use App\Entity\Fipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fipe>
 *
 * @method Fipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fipe[]    findAll()
 * @method Fipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fipe::class);
    }
    
    /**
     * Adiciona ao banco de dados.
     *
     * @param  mixed $entity
     * @param  mixed $flush
     * @return void
     */
    public function add(Fipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove do banco de dados.
     *
     * @param  mixed $entity
     * @param  mixed $flush
     * @return void
     */
    public function remove(Fipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Fipe[] Returns an array of Fipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fipe
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
