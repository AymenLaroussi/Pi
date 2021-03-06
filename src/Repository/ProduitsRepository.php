<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    // /**
    //  * @return Produits[] Returns an array of Produits objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    */

    /*
    public function findOneBySomeField($value): ?Produits
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function listProduitsByCategories($id)
    {
        return $this->createQueryBuilder('s')
            ->join('s.categories', 'c')
            ->addSelect('c')
            ->where('c.id=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    public function listCommentaireByProduit($id)
    {
        return $this->createQueryBuilder('s')
            ->join('s.commentaires','c')
            ->addSelect('c')
            ->where('c.id=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    public function RechercheProduit($titre)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.titre LIKE :x')
            ->setParameter('x', '%'.$titre.'%')
            ->getQuery()
            ->execute();
    }


    public function orderByPrixBas()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.prix', 'ASC')
            ->getQuery()->getResult();
    }

    public function orderByPrixHaut()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.prix', 'DESC')
            ->getQuery()->getResult();
    }

    public function orderByFlash()
    {
        $qb = $this->createQueryBuilder('s');
        $qb->where('s.flash=:flash');
        $qb->setParameter('flash', true);
        return $qb->getQuery()->getResult();
    }
    

}
