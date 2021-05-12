<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ArticleRepository
 *
 * @package App\Repository
 * @author Polvanov Igor <igor@zima.kg>
 * @copyright 2021 (c) Zima
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getArticlesByAuthorId($id)
    {
        return $this->createQueryBuilder('article')
            ->select()
            ->innerJoin('article.authors', 'author')
            ->where('author.id = :id')
            ->setParameter('id', $id)
            ->orderBy('article.createdAt')
            ->getQuery()
            ->getResult()
        ;
    }
}
