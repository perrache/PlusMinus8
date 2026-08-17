<?php

namespace App\Controller\CRUD;

use App\Repository\TypeRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type')]
final class TypeController extends AbstractController
{
    #[Route(name: 'app_type_index', methods: ['GET'])]
    public function index(TypeRepository  $typeRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/type/index.html.twig', [
            'records' => $typeRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }
}
