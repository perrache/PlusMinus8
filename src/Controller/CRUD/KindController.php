<?php

namespace App\Controller\CRUD;

use App\Repository\KindRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/kind')]
final class KindController extends AbstractController
{
    #[Route(name: 'app_kind_index', methods: ['GET'])]
    public function index(KindRepository $kindRepository): Response
    {
        return $this->render('CRUD/kind/index.html.twig', [
            'kinds' => $kindRepository->findAll(['name' => 'asc']),
        ]);
    }
}
