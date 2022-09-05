<?php
declare(strict_types=1);

namespace Pos\Domain;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Pos\Entity\Sale;
use Pos\Entity\Transaction;
use Pos\Repository\TransactionRepository;

class Register
{
    private EntityManagerInterface $em;
    private TransactionRepository $transactionRepository;

    public function __construct(EntityManagerInterface $em, TransactionRepository $transactionRepository)
    {
        $this->em = $em;
        $this->transactionRepository = $transactionRepository;
    }

    public function closeSale(Sale $note): void
    {
        if ($note->getTotal() > 0) {
            $todaysDate = new DateTime();

            $existingTransaction = $this->transactionRepository->findOneBy([
                'date' => $todaysDate,
                'type' => Transaction::TYPE_SALE,
            ]);

            if ($existingTransaction instanceof Transaction) {
                $existingTransaction->price += $note->getTotal();
                $this->em->flush();
            } else {
                $newTransaction = new Transaction();
                $newTransaction->type = Transaction::TYPE_SALE;
                $newTransaction->item = 'Kasiert';
                $newTransaction->price = $note->getTotal();
                $newTransaction->date = $todaysDate;
                $this->em->persist($newTransaction);
            }

            $note->client = null;
        }

        $this->em->remove($note);
        $this->em->flush();
    }
}