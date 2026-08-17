<?php

namespace App\Controller\CRUD;

use App\Repository\SaldoRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/saldo')]
final class SaldoController extends AbstractController
{
    #[Route(name: 'app_saldo_index', methods: ['GET'])]
    public function index(SaldoRepository $saldoRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/saldo/index.html.twig', [
            'records' => $saldoRepository->findAll($logger, ['dat' => 'desc', 'id' => 'desc']),
        ]);
    }
}
