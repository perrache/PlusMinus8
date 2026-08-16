<?php

namespace App\Controller\CRUD;

use App\Repository\KindRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/kind')]
final class OrganizationController extends AbstractController
{
    #[Route(name: 'app_kind_index', methods: ['GET'])]
    public function index(KindRepository  $kindRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/kind/index.html.twig', [
            'kinds' => $kindRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }
}
