<?php

namespace App\Controller\CRUD;

use App\Repository\TransactionRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transaction')]
final class TransactionController extends AbstractController
{
    #[Route(name: 'app_transaction_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository,
                          LoggerInterface       $logger): Response
    {
        return $this->render('CRUD/transaction/index.html.twig', [
            'records' => $transactionRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }

    #[Route('/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request               $request,
                        TransactionRepository $transactionRepository,
                        LoggerInterface       $logger,
                        Sql                   $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $transactionRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/transaction/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(TransactionRepository $transactionRepository,
                         LoggerInterface       $logger,
                         int                   $id = 0): Response
    {
        return $this->render('CRUD/transaction/show.html.twig', [
            'records' => $transactionRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request               $request,
                         TransactionRepository $transactionRepository,
                         LoggerInterface       $logger,
                         Sql                   $sql,
                         int                   $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $transactionRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/transaction/edit.html.twig', [
            'records' => $transactionRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_transaction_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('transaction', ['id' => $id]);
        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
