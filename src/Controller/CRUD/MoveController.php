<?php

namespace App\Controller\CRUD;

use App\Repository\MoveRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'app_move_new', methods: ['GET', 'POST'])]
    public function new(Request         $request,
                        MoveRepository  $moveRepository,
                        LoggerInterface $logger,
                        Sql             $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $moveRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_move_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/move/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_move_show', methods: ['GET'])]
    public function show(MoveRepository  $moveRepository,
                         LoggerInterface $logger,
                         int             $id = 0): Response
    {
        return $this->render('CRUD/move/show.html.twig', [
            'records' => $moveRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_move_edit', methods: ['GET', 'POST'])]
    public function edit(Request         $request,
                         MoveRepository  $moveRepository,
                         LoggerInterface $logger,
                         Sql             $sql,
                         int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_move_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $moveRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_move_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/move/edit.html.twig', [
            'records' => $moveRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_move_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_move_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('move', ['id' => $id]);
        return $this->redirectToRoute('app_move_index', [], Response::HTTP_SEE_OTHER);
    }
}
