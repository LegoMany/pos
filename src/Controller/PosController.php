<?php

namespace Pos\Controller;

use Pos\Entity\Transaction;
use Pos\Form\PrintType;
use Pos\Printer\Printer;
use Pos\Repository\TransactionRepository;
use Pos\Utility\ArrayUtility;
use Pos\Utility\NumberUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PosController extends AbstractController
{
    protected TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function index(): Response
    {
        $printForm = $this->createForm(PrintType::class);

        return $this->render('pos/index.html.twig', [
            'sales' => $this->transactionRepository->findAllSales(),
            'orders' => $this->transactionRepository->findAllOrders(),
            'printForm' => $printForm->createView(),
        ]);
    }

    public function printMonths(Request $request): Response
    {
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $transactions = $this->transactionRepository->findByMonthAndYear(
            (int)$from['month'],
            (int)$from['year'],
            (int)$to['year'],
            (int)$to['year']
        );
        $groupedTransactions = [];
        /** @var Transaction $transaction */
        foreach ($transactions as $transaction) {
            $groupedTransactions
            [$transaction->date->format('Y')]
            [$transaction->date->format('m')][] = $transaction;
        }

        ArrayUtility::ksortRecursive($groupedTransactions);
        $printer = new Printer();
        $startBalance = NumberUtility::commaToFloat($request->query->get('startBalance'));
        $printer->initialize($groupedTransactions, $startBalance);

        return $this->render('pos/printMonths.html.twig', [
            'printer' => $printer,
        ]);
    }
}