<?php

namespace App\Repository;

use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\ArrayShape;

class LoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    #[ArrayShape([
        'items' => "mixed",
        'totalPageCount' => "float",
        'totalItems' => "int"
    ])]
    public function getLoans(array $params = [], int $page = 1, int $itemsPerPage = 10): array
    {
        $qb = $this->createQueryBuilder('loan');
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
            if ($key === 'bookId') {
                $qb->andWhere('loan.book = :bookId')
                    ->setParameter('bookId', $value);
                continue;
            }

            $ourKey = $key;
            $ourValue = $value;
            if (is_array($value)) {
                $ourKey = $key . ucfirst(array_key_first($value));
                $ourValue = $value[array_key_first($value)];
            }
            $qb->andWhere("loan.$key LIKE :$ourKey")
                ->setParameter($ourKey, '%' . $ourValue . '%');
        }
        return $qb;
    }
}
