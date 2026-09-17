<?php

namespace App\Controller\CRUD;

use App\Repository\AccountRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account')]
final class AccountController extends AbstractController
{
    #[Route(name: 'app_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository,
                          LoggerInterface   $logger): Response
    {
        return $this->render('CRUD/account/index.html.twig', [
            'records' => $accountRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }

    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(Request           $request,
                        AccountRepository $accountRepository,
                        LoggerInterface   $logger,
                        Sql               $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $accountRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/account/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(AccountRepository $accountRepository,
                         LoggerInterface   $logger,
                         int               $id = 0): Response
    {
        return $this->render('CRUD/account/show.html.twig', [
            'records' => $accountRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request           $request,
                         AccountRepository $accountRepository,
                         LoggerInterface   $logger,
                         Sql               $sql,
                         int               $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $accountRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/account/edit.html.twig', [
            'records' => $accountRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_account_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('account', ['id' => $id]);
        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
