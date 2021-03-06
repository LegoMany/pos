<?php

namespace Pos\Repository;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Types;
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
            $stmt->execute();
            return $stmt->fetchAll();
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

    public function getPreviousTransaction(Transaction $transaction): ?Transaction
    {
        $fromDate = new DateTime();
        $fromDate->setDate(
            $transaction->date->format('Y'),
            1,
            1
        );
        $toDate = new DateTime();
        $toDate->setDate(
            $transaction->date->format('Y'),
            12,
            31
        );

        $row = $this->createQueryBuilder('t')
            ->where('t.date BETWEEN :from AND :to')
            ->setParameter('from', $fromDate, Types::DATE_MUTABLE)
            ->setParameter('to', $toDate, Types::DATE_MUTABLE)
            ->orderBy('t.receiptNumber', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();

        if (false === empty($row)) {
            return $row[0];
        }
        return null;
    }
}
