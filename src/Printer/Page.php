<?php

namespace Pos\Printer;

use Doctrine\Common\Collections\ArrayCollection;
use Pos\Entity\Transaction;

class Page
{
    protected ArrayCollection $rows;
    public int $number;
    public string $pageDate;
    public float $salesSum = 0;
    public float $ordersSum = 0;
    public float $startBalance;

    public function __construct(string $pageDate, float $startBalance)
    {
        $this->rows = new ArrayCollection();
        $this->startBalance = $startBalance;

        $this->pageDate = $pageDate;
        $this->salesSum = $startBalance;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if ($transaction->type === Transaction::TYPE_ORDER) {
            $this->ordersSum += $transaction->price;
        } elseif ($transaction->type === Transaction::TYPE_SALE) {
            $this->salesSum += $transaction->price;
        }
        $this->rows->add($transaction);
        return $this;
    }

    public function getRows(): ArrayCollection
    {
        return $this->rows;
    }

    public function fillRows(int $maxRowsAllowed)
    {
        $rowsToAdd = $maxRowsAllowed - $rowsCount = $this->rows->count();
        for ($i = 1; $i <= $rowsToAdd; $i++) {
            $this->rows->add([]);
        }
    }

    public function getFinishBalance(): float
    {
        return $this->salesSum - $this->ordersSum;
    }
}