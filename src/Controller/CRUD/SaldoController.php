<?php

namespace App\Controller\CRUD;

use App\Repository\SaldoRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/saldo')]
final class SaldoController extends AbstractController
{
    #[Route(name: 'app_saldo_index', methods: ['GET'])]
    public function index(SaldoRepository $saldoRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/saldo/index.html.twig', [
            'records' => $saldoRepository->findAll($logger, ['dat' => 'desc', 'id' => 'desc']),
        ]);
    }

    #[Route('/new', name: 'app_saldo_new', methods: ['GET', 'POST'])]
    public function new(Request         $request,
                        SaldoRepository $saldoRepository,
                        LoggerInterface $logger,
                        Sql             $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $saldoRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_saldo_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/saldo/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_saldo_show', methods: ['GET'])]
    public function show(SaldoRepository $saldoRepository,
                         LoggerInterface $logger,
                         int             $id = 0): Response
    {
        return $this->render('CRUD/saldo/show.html.twig', [
            'records' => $saldoRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_saldo_edit', methods: ['GET', 'POST'])]
    public function edit(Request         $request,
                         SaldoRepository $saldoRepository,
                         LoggerInterface $logger,
                         Sql             $sql,
                         int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_saldo_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $saldoRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_saldo_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/saldo/edit.html.twig', [
            'records' => $saldoRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_saldo_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_saldo_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('saldo', ['id' => $id]);
        return $this->redirectToRoute('app_saldo_index', [], Response::HTTP_SEE_OTHER);
    }
}
