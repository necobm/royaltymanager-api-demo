<?php

namespace App\Repository;

use App\Entity\Episode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Episode>
 *
 * @method Episode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Episode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Episode[]    findAll()
 * @method Episode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Episode $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Episode $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Check if a given Episode guid exists in database
     * 
     * @param string $guid
     * @return bool
     * @throws NoResultException
     */
    public function existInDatabase(string $guid): bool
    {
        $query = $this->_em->createQuery("select count(ep.id) from Episode ep where ep.guid = :guid ");

        try{
            $count = $query->setParameter('guid', $guid)->getSingleScalarResult();

            return $count > 0;
        }
        catch(NoResultException $e){
            return false;
        }
        
    }
}
