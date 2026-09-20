<?php

namespace App\Controller\CRUD;

use App\Repository\SourceRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'app_source_new', methods: ['GET', 'POST'])]
    public function new(Request          $request,
                        SourceRepository $sourceRepository,
                        LoggerInterface  $logger,
                        Sql              $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $sourceRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_source_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/source/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_source_show', methods: ['GET'])]
    public function show(SourceRepository $sourceRepository,
                         LoggerInterface  $logger,
                         int              $id = 0): Response
    {
        return $this->render('CRUD/source/show.html.twig', [
            'records' => $sourceRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_source_edit', methods: ['GET', 'POST'])]
    public function edit(Request          $request,
                         SourceRepository $sourceRepository,
                         LoggerInterface  $logger,
                         Sql              $sql,
                         int              $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_source_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $sourceRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_source_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/source/edit.html.twig', [
            'records' => $sourceRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_source_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_source_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('source', ['id' => $id]);
        return $this->redirectToRoute('app_source_index', [], Response::HTTP_SEE_OTHER);
    }
}
