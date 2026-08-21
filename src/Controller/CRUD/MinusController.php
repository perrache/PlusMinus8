<?php

namespace App\Controller\CRUD;

use App\Repository\MinusRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/minus')]
final class MinusController extends AbstractController
{
    #[Route(name: 'app_minus_index', methods: ['GET'])]
    public function index(MinusRepository $minusRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/minus/index.html.twig', [
            'records' => $minusRepository->findAll($logger, ['dat' => 'desc', 'id' => 'desc']),
        ]);
    }

    #[Route('/new', name: 'app_minus_new', methods: ['GET', 'POST'])]
    public function new(Request $request,
                        Sql     $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $sql->dml('insert into minus (comment) values ($1)', [$request->getPayload()->get('kind_name', 'default')]);
            return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/minus/new.html.twig');
    }

    #[Route('/{id}', name: 'app_minus_show', methods: ['GET'])]
    public function show(MinusRepository $minusRepository,
                         LoggerInterface $logger,
                         int             $id = 0): Response
    {
        return $this->render('CRUD/minus/show.html.twig', [
            'records' => $minusRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }
}
