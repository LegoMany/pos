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

        foreach ($note->items as $item) {
            $this->em->remove($item);
        }
        $this->em->remove($note);
        $this->em->flush();
    }
}