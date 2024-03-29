<?php

namespace App\Repository;

use App\Entity\Hackathon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hackathon>
 *
 * @method Hackathon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hackathon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hackathon[]    findAll()
 * @method Hackathon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HackathonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hackathon::class);
    }

    public function save(Hackathon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hackathon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDate()
    {
       $query=$this->createQueryBuilder('h')
            ->orderBy('h.dateDebut', 'asc')
            ->getQuery();
       return $query->getResult();
    }

    public function nbInscrit($id)
    {
        $query=$this->createQueryBuilder('g')
            ->where('g.id=:key')
            ->setParameter('key', $id)->getQuery();
        return $query->getResult();
    }
    
    public function findLikeVille($searchHackathon)
    {
        $query=$this->createQueryBuilder('s')
            ->where('s.ville LIKE :key')
            ->orderBy('s.dateDebut', 'asc')
            ->setParameter('key', '%'.$searchHackathon.'%')->getQuery();
        return $query->getResult();
    }

// SELECT * FROM hackathon ha WHERE ha.id NOT IN (SELECT idHackathon FROM inscription WHERE inscription.idUtilisateur = 82)


//    /**
//     * @return Hackathon[] Returns an array of Hackathon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hackathon
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
