<?php

namespace App\Controller\CRUD;

use App\Repository\PlusRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'app_plus_new', methods: ['GET', 'POST'])]
    public function new(Request         $request,
                        PlusRepository  $plusRepository,
                        LoggerInterface $logger,
                        Sql             $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $plusRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_plus_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/plus/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_plus_show', methods: ['GET'])]
    public function show(PlusRepository  $plusRepository,
                         LoggerInterface $logger,
                         int             $id = 0): Response
    {
        return $this->render('CRUD/plus/show.html.twig', [
            'records' => $plusRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_plus_edit', methods: ['GET', 'POST'])]
    public function edit(Request         $request,
                         PlusRepository  $plusRepository,
                         LoggerInterface $logger,
                         Sql             $sql,
                         int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_plus_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $plusRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_plus_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/plus/edit.html.twig', [
            'records' => $plusRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_plus_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_plus_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('plus', ['id' => $id]);
        return $this->redirectToRoute('app_plus_index', [], Response::HTTP_SEE_OTHER);
    }
}
