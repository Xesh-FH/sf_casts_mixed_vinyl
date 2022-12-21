<?php

namespace App\Repository;

use App\Entity\VinylMix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VinylMix>
 *
 * @method VinylMix|null find($id, $lockMode = null, $lockVersion = null)
 * @method VinylMix|null findOneBy(array $criteria, array $orderBy = null)
 * @method VinylMix[]    findAll()
 * @method VinylMix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinylMixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VinylMix::class);
    }

    public function save(VinylMix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VinylMix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createOrderedByVotesQueryBuilder(string $genre = null): QueryBuilder
    {

        $qb =  $this->addOrderByVotesQueryBuilder();

        if ($genre) {
            $qb
                ->andWhere('mix.genre = :genre')
                ->setParameter('genre', $genre);
        }

        return $qb;
    }

    private function addOrderByVotesQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        $qb = $qb ?? $this->createQueryBuilder(('mix'));
        $qb->orderBy('mix.votes', 'DESC');

        return $qb;
    }
}
