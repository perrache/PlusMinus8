<?php

namespace App\Controller\CRUD;

use App\Repository\CurrencyRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'app_currency_new', methods: ['GET', 'POST'])]
    public function new(Request            $request,
                        CurrencyRepository $currencyRepository,
                        LoggerInterface    $logger): Response
    {
        if ($request->getMethod() === 'POST') {
            $currencyRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_currency_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/currency/new.html.twig');
    }

    #[Route('/{id}', name: 'app_currency_show', methods: ['GET'])]
    public function show(CurrencyRepository $currencyRepository,
                         LoggerInterface    $logger,
                         int                $id = 0): Response
    {
        return $this->render('CRUD/currency/show.html.twig', [
            'records' => $currencyRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }
}
