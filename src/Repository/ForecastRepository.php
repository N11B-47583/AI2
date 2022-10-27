<?php

namespace App\Repository;

use App\Entity\Forecast;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forecast>
 *
 * @method Forecast|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forecast|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forecast[]    findAll()
 * @method Forecast[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForecastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forecast::class);
    }

    public function save(Forecast $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Forecast $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByLocation(Location $location)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.location = :location')
            ->setParameter('location', $location)
            ->andWhere('f.date > :now')
            ->setParameter('now', date('Y-m-d'));
            #->join('f.Location', 'l')
            #->andWhere('l.Name = :name')
            #->setParameter('name','Szczecin');
        
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function findByLocationByName($name)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('l.name = :name')
            ->setParameter('name',$name)
            ->join('f.location', 'l');
            
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;


    }

//    /**
//     * @return Forecast[] Returns an array of Forecast objects
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

//    public function findOneBySomeField($value): ?Forecast
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
