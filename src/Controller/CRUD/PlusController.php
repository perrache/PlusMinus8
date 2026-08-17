<?php

namespace App\Controller\CRUD;

use App\Repository\PlusRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/plus')]
final class PlusController extends AbstractController
{
    #[Route(name: 'app_plus_index', methods: ['GET'])]
    public function index(PlusRepository  $plusRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/plus/index.html.twig', [
            'records' => $plusRepository->findAll($logger, ['dat' => 'desc', 'id' => 'desc']),
        ]);
    }
}
