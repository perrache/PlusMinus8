<?php

namespace App\Controller\CRUD;

use App\Repository\KindRepository;
use App\SimpleSQL\Sql;
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
                          Sql             $sql,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/kind/index.html.twig', [
            'records' => $kindRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }

    #[Route('/new', name: 'app_kind_new', methods: ['GET', 'POST'])]
    public function new(Request $request,
                        Sql     $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $sql->dml('insert into kind (name) values ($1)', [$request->getPayload()->get('kind_name', 'default')]);
            return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/kind/new.html.twig');
    }
}
