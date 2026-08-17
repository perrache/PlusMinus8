<?php

namespace App\Controller\CRUD;

use App\Repository\AccountRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account')]
final class AccountController extends AbstractController
{
    #[Route(name: 'app_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository,
                          LoggerInterface   $logger): Response
    {
        return $this->render('CRUD/account/index.html.twig', [
            'records' => $accountRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }
}
