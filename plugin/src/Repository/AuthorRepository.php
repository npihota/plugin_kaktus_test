<?php

declare(strict_types=1);


namespace App\Repository;


use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AuthorRepository
 *
 * @package App\Repository
 * @author Polvanov Igor <igor@zima.kg>
 * @copyright 2021 (c) Zima
 */
class AuthorRepository extends ServiceEntityRepository
{
    /**
     * AuthorRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param string $name
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return int|mixed|string
     */
    public function hasAuthor(string $name): bool
    {
        return $this->createQueryBuilder('author')
            ->select('COUNT(author.id) as ids')
            ->where('author.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    /**
     * @param string $name
     *
     * @return Author
     */
    public function getAuthorByName(string $name): Author
    {
        return $this->findOneBy(['name' => $name]);
    }

}