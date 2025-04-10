<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

class PublisherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }

    #[ArrayShape([
        'items' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getPublishers(array $params = [], int $page = 1, int $itemsPerPage = 10): array
    {
        $queryBuilder = $this->createQueryBuilder('publisher');
        $queryBuilder = $this->mapParams($queryBuilder, $params);
        $query = $queryBuilder->getQuery();
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

    private function mapParams(QueryBuilder $queryBuilder, array $params): QueryBuilder
    {
        foreach ($params as $key => $value) {
            $ourKey = $key;
            $ourValue = $value;
            if (is_array($value)) {
                $ourKey = $key . ucfirst(array_key_first($value));
                $ourValue = $value[array_key_first($value)];
            }
            $queryBuilder
                ->andWhere("publisher.$key LIKE :$ourKey")
                ->setParameter($ourKey, '%' . $ourValue . '%');
        }
        return $queryBuilder;
    }
}
