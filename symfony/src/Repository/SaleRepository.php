<?php

namespace Pos\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pos\Entity\Sale;

class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function findDebtNotes(): array
    {
        return $this->createQueryBuilder('n')
            ->leftjoin('n.client', 'c')
            ->andWhere('c.id is not null')
            ->getQuery()
            ->execute();
    }
}