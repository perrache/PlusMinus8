<?php

namespace App\Controller\CRUD;

use App\Repository\SourceRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/source')]
final class SourceController extends AbstractController
{
    #[Route(name: 'app_source_index', methods: ['GET'])]
    public function index(SourceRepository $sourceRepository,
                          LoggerInterface  $logger): Response
    {
        return $this->render('CRUD/source/index.html.twig', [
            'records' => $sourceRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }
}
