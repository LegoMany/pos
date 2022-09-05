<?php

namespace Pos\Repository;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Pos\Entity\Transaction;
use Throwable;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['date' => 'ASC']);
    }

    public function findAllOrders(): array
    {
        return $this->findBy(['type' => Transaction::TYPE_ORDER], ['date' => 'ASC']);
    }

    public function findAllSales(): array
    {
        return $this->findBy(['type' => Transaction::TYPE_SALE], ['date' => 'ASC']);
    }

    public function getSumForTypeGroupedByYear(): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 'select year(date) as year,
                       type,
                       round(sum(price), 2) as sum
                from transaction
                group by year(date), type';

        try {
            $stmt = $connection->prepare($sql);
            $result = $stmt->executeQuery();
            return $result->fetchAllAssociative();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function findByMonthAndYear(int $fromMonth, int $fromYear, int $toMonth, int $toYear): array
    {
        $fromDate = new DateTime();
        $fromDate->setDate($fromYear, $fromMonth, 1);
        $toDate = new DateTime();
        $toDate->setDate($toYear, $toMonth, 1);

        return $this->createQueryBuilder('t')
            ->where('t.date BETWEEN :from AND :to')
            ->setParameter('from', $fromDate, Types::DATE_MUTABLE)
            ->setParameter('to', $toDate, Types::DATE_MUTABLE)
            ->orderBy('t.date', 'ASC')
            ->getQuery()
            ->execute();
    }

    public function getGroupedByItemWithCount(int $year): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 'SELECT item, count(*) as count
                FROM transaction
                WHERE YEAR(date) = ' . $year . '
                GROUP BY item order by count desc';

        try {
            $stmt = $connection->prepare($sql);
            $result = $stmt->executeQuery();
            return $result->fetchAllAssociative();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getYears(): array
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 'SELECT YEAR(date) as year
                FROM transaction
                GROUP BY YEAR(date)';

        try {
            $stmt = $connection->prepare($sql);
            $result = $stmt->executeQuery();
            return $result->fetchFirstColumn();
        } catch (Throwable $e) {
            return [];
        }
    }
}
