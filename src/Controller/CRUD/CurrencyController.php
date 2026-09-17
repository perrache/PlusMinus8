<?php

namespace App\Controller\CRUD;

use App\Repository\CurrencyRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/currency')]
final class CurrencyController extends AbstractController
{
    #[Route(name: 'app_currency_index', methods: ['GET'])]
    public function index(CurrencyRepository $currencyRepository,
                          LoggerInterface    $logger): Response
    {
        return $this->render('CRUD/currency/index.html.twig', [
            'records' => $currencyRepository->findAll($logger, ['code' => 'asc']),
        ]);
    }

    #[Route('/new', name: 'app_currency_new', methods: ['GET', 'POST'])]
    public function new(Request            $request,
                        CurrencyRepository $currencyRepository,
                        LoggerInterface    $logger,
                        Sql                $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $currencyRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_currency_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/currency/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_currency_show', methods: ['GET'])]
    public function show(CurrencyRepository $currencyRepository,
                         LoggerInterface    $logger,
                         int                $id = 0): Response
    {
        return $this->render('CRUD/currency/show.html.twig', [
            'records' => $currencyRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_currency_edit', methods: ['GET', 'POST'])]
    public function edit(Request            $request,
                         CurrencyRepository $currencyRepository,
                         LoggerInterface    $logger,
                         Sql                $sql,
                         int                $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_currency_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $currencyRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_currency_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/currency/edit.html.twig', [
            'records' => $currencyRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_currency_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_currency_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('currency', ['id' => $id]);
        return $this->redirectToRoute('app_currency_index', [], Response::HTTP_SEE_OTHER);
    }
}
