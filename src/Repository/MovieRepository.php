<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearch($search)
    { 
        return $this->createQueryBuilder('m')
            ->where('m.title LIKE :search')
            ->setParameter('search', '%' . $search . '%')

            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByGenre($id)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.genres', 'g')
            ->where('g.id = :genre_id')
            ->setParameter('genre_id', $id)
            ->getQuery()
            ->getResult();
    }

}
