<?php

namespace App\Controller\CRUD;

use App\Repository\TransactionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transaction')]
final class TransactionController extends AbstractController
{
    #[Route(name: 'app_transaction_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository,
                          LoggerInterface       $logger): Response
    {
        return $this->render('CRUD/transaction/index.html.twig', [
            'records' => $transactionRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }
}
