<?php

namespace Pos\Domain\Printer;

use Doctrine\Common\Collections\ArrayCollection;
use Pos\Entity\Transaction;

class Printer
{
    const ROWS_ALLOWED_PER_PAGE = 20;

    /**
     * @var ArrayCollection<Page>
     */
    public ArrayCollection $pages;

    protected int $transactionCount = 0;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

    public function initialize(array $transactions, float $startBalance)
    {
        foreach ($transactions as $year => $months) {
            foreach ($months as $month => $monthTransactions) {
                if (false === $this->pages->isEmpty()) {
                    $startBalance = $this->pages->last()->getFinishBalance();
                }
                $this->addTransactions($monthTransactions, $month, $year, $startBalance);
            }
        }
    }

    protected function addTransactions(array $transactions, int $month, int $year, float $startBalance)
    {
        if (count($transactions) > 0) {
            if (is_null($startBalance)) {
                $startBalance = $this->pages->last()->getTotalSum();
            }
            $newPage = new Page($month . ' / ' . $year, $startBalance);
            $leftoverRows = [];
            /** @var Transaction $transaction */
            foreach ($transactions as $transaction) {
                if ($newPage->getRows()->count() < self::ROWS_ALLOWED_PER_PAGE) {
                    $this->transactionCount++;
                    $transaction->receiptNumber = $this->transactionCount;
                    $newPage->addTransaction($transaction);
                } else {
                    $leftoverRows[] = $transaction;
                }
            }
            $newPage->fillRows(self::ROWS_ALLOWED_PER_PAGE);
            $this->pages->add($newPage);
            $this->addTransactions($leftoverRows, $month, $year, $newPage->getFinishBalance());
        }
    }
}