<?php

namespace App\Controller\CRUD;

use App\Repository\KindRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/kind')]
final class KindController extends AbstractController
{
    #[Route(name: 'app_kind_index', methods: ['GET'])]
    public function index(KindRepository  $kindRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/kind/index.html.twig', [
            'records' => $kindRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }

    #[Route('/new', name: 'app_kind_new', methods: ['GET', 'POST'])]
    public function new(Request         $request,
                        KindRepository  $kindRepository,
                        LoggerInterface $logger): Response
    {
        if ($request->getMethod() === 'POST') {
            $kindRepository->sqlInsert($logger);
            return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/kind/new.html.twig');
    }

    #[Route('/{id}', name: 'app_kind_show', methods: ['GET'])]
    public function show(KindRepository  $kindRepository,
                         LoggerInterface $logger,
                         int             $id = 0): Response
    {
        return $this->render('CRUD/kind/show.html.twig', [
            'records' => $kindRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }
}
