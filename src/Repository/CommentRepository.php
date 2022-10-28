<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use \Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function save(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithSearchQuery(?string $search = null, bool $deleted = false): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('c');
        if ($search){
            $queryBuilder
                ->andWhere('c.content LIKE :search OR c.authorName LIKE :search OR a.title LIKE :search OR a.title LIKE :search' )
                ->setParameter('search', "%$search%");
        }
        if($deleted){
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $queryBuilder
            ->innerJoin('c.article', 'a' )
            ->addSelect('a')
            ->innerJoin('a.tags', 't' )
            ->addSelect('t')
            ->orderBy('c.createdAt', 'DESC');
    }
}
