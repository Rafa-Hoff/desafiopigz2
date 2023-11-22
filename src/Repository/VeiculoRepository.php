<?php

namespace App\Repository;

use App\Entity\Veiculo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Veiculo>
 *
 * @method Veiculo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Veiculo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Veiculo[]    findAll()
 * @method Veiculo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VeiculoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Veiculo::class);
    }
    
    /**
     * Adiciona ao banco de dados.
     *
     * @param  mixed $entity
     * @param  mixed $flush
     * @return void
     */
    public function add(Veiculo $entity, bool $flush = false): void
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
    public function remove(Veiculo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Veiculo[] Returns an array of Veiculo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Veiculo
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
