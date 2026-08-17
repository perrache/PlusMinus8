<?php

namespace App\Controller\CRUD;

use App\Repository\CurrencyRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/currency')]
final class CurrencyController extends AbstractController
{
    #[Route(name: 'app_currency_index', methods: ['GET'])]
    public function index(CurrencyRepository $currencyRepository,
                          LoggerInterface    $logger): Response
    {
        return $this->render('CRUD/currency/index.html.twig', [
            'records' => $currencyRepository->findAll($logger, ['code' => 'asc']),
        ]);
    }
}
