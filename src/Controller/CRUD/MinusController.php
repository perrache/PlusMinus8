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
    public function new(Request         $request,
                        MinusRepository $minusRepository,
                        LoggerInterface $logger,
                        Sql             $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $minusRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/minus/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
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

    #[Route('/edit/{id}', name: 'app_minus_edit', methods: ['GET', 'POST'])]
    public function edit(Request         $request,
                         MinusRepository $minusRepository,
                         LoggerInterface $logger,
                         Sql             $sql,
                         int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $minusRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/minus/edit.html.twig', [
            'records' => $minusRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_minus_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('minus', ['id' => $id]);
        return $this->redirectToRoute('app_minus_index', [], Response::HTTP_SEE_OTHER);
    }
}
