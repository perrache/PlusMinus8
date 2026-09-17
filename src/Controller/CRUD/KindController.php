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
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/kind/index.html.twig', [
            'records' => $kindRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }

    #[Route('/new', name: 'app_kind_new', methods: ['GET', 'POST'])]
    public function new(Request         $request,
                        KindRepository  $kindRepository,
                        LoggerInterface $logger,
                        Sql             $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $kindRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/kind/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
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

    #[Route('/edit/{id}', name: 'app_kind_edit', methods: ['GET', 'POST'])]
    public function edit(Request         $request,
                         KindRepository  $kindRepository,
                         LoggerInterface $logger,
                         Sql             $sql,
                         int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $kindRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/kind/edit.html.twig', [
            'records' => $kindRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_kind_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('kind', ['id' => $id]);
        return $this->redirectToRoute('app_kind_index', [], Response::HTTP_SEE_OTHER);
    }
}
