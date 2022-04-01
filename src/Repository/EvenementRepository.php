<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

  
    public function getAll($keyWord)
    {
        
        $qb = $this->createQueryBuilder('e')
                     ->orderBy('e.datevent', 'ASC');
        if(isset($keyWord)) {
            $qb->where('e.nomeven LIKE :keyWord')
                ->setParameter('keyWord', '%' . $keyWord . '%');
        }

        return $qb->getQuery()->getResult();
    }

}
