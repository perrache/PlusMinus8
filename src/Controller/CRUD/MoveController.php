<?php

namespace App\Controller\CRUD;

use App\Repository\MoveRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/move')]
final class MoveController extends AbstractController
{
    #[Route(name: 'app_move_index', methods: ['GET'])]
    public function index(MoveRepository  $moveRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/move/index.html.twig', [
            'records' => $moveRepository->findAll($logger, ['dat' => 'desc', 'id' => 'desc']),
        ]);
    }
}
