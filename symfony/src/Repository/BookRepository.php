<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    #[ArrayShape([
        'items' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getBooks(array $params = [], int $page = 1, int $itemsPerPage = 10): array
    {
        $qb = $this->createQueryBuilder('book');
        $qb = $this->mapParams($qb, $params);

        $query = $qb->getQuery();
        $paginatorForCount = new Paginator($query);
        $totalItems = count($paginatorForCount);
        $pagesCount = ceil($totalItems / $itemsPerPage);

        $query->setFirstResult($itemsPerPage * ($page - 1))
            ->setMaxResults($itemsPerPage);

        $paginator = new Paginator($query);

        return [
            'items' => $paginator->getQuery()->getResult(),
            'totalPageCount' => $pagesCount,
            'totalItems' => $totalItems,
        ];
    }

    private function mapParams(QueryBuilder $qb, array $params): QueryBuilder
    {
        foreach ($params as $key => $value) {
            if ($key === 'genreId') {
                $qb->andWhere('book.genre = :genreId')
                    ->setParameter('genreId', $value);
                continue;
            }

            if ($key === 'authorId') {
                $qb->andWhere('book.author = :authorId')
                    ->setParameter('authorId', $value);
                continue;
            }

            if ($key === 'publisherId') {
                $qb->andWhere('book.publisher = :publisherId')
                    ->setParameter('publisherId', $value);
                continue;
            }

            $ourKey = $key;
            $ourValue = $value;
            if (is_array($value)) {
                $ourKey = $key . ucfirst(array_key_first($value));
                $ourValue = $value[array_key_first($value)];
            }

            $qb->andWhere("book.$key LIKE :$ourKey")
                ->setParameter($ourKey, '%' . $ourValue . '%');
        }

        return $qb;
    }
}
