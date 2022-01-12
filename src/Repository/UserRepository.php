<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Group;
use App\Data\SearchData;
use Doctrine\ORM\Query\Expr\Join;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getSearchQuery(SearchData $search){
        $query = $this->createQueryBuilder('r');
        $entityManager = $this->getEntityManager();

        if(!empty($search->qFirstName)){
            $query = $query->andWhere('r.firstName LIKE :qFirstName')
                           ->setParameter('qFirstName', "%{$search->qFirstName}%")
                            ;
        }
        if(!empty($search->qLastName)){
            $query = $query->andWhere('r.lastName LIKE :qLastName')
                           ->setParameter('qLastName', "%{$search->qLastName}%")
                            ;
        }
        if(!empty($search->qGroupName)){
            $query = $query ->addSelect('g')
                            ->leftJoin('r.group_name', 'g')
                            ->andWhere('g.name LIKE :qGroupName')
                            ->setParameter('qGroupName', "%{$search->qGroupName}%")
             ;
        }
        return $query;
    }


    public function findQueryResult(SearchData $search,PaginatorInterface $paginator)
    {
        $query =  $this->getSearchQuery($search)->getQuery();

        return $paginator->paginate(
            $query,
            $search->page,
            10
        );
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
