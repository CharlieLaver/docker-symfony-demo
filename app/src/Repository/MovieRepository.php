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

    public function save(Movie $entity, string $id, bool $flush = false): void
    {
		if(sizeof($this->findExisting($id))) {
			$this->getEntityManager()->merge($entity);
		} else {
			$this->getEntityManager()->persist($entity);
		}

        if($flush) {
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
	
	public function fetch($title)
	{
		return $this->createQueryBuilder('m')
			->where('m.title = :title')
			->setParameter('title', $title)
			->getQuery()
            ->getArrayResult();
	}
	
	public function fetchAll()
	{
		$movieRows = $this->createQueryBuilder('m')
			->getQuery()
			->getArrayResult();

        foreach($movieRows as &$movie) {
            $this->decodeKeywords($movie);
        }

        return $movieRows;
	}
	
	public function findExisting($id)
	{
		return $this->createQueryBuilder('m')
			->where('m.imdb_id = :imdb_id')
			->setParameter('imdb_id', $id)
			->getQuery()
			->getArrayResult();
	}

    private function decodeKeywords(&$movie)
    {
        if(isset($movie['keywords'])) {
            $movie['keywords'] = json_decode($movie['keywords'], true);
        }
    }
}