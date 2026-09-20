<?php

namespace App\Controller\CRUD;

use App\Repository\OrganizationRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organization')]
final class OrganizationController extends AbstractController
{
    #[Route(name: 'app_organization_index', methods: ['GET'])]
    public function index(OrganizationRepository $organizationRepository,
                          LoggerInterface        $logger): Response
    {
        return $this->render('CRUD/organization/index.html.twig', [
            'records' => $organizationRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }

    #[Route('/new', name: 'app_organization_new', methods: ['GET', 'POST'])]
    public function new(Request                $request,
                        OrganizationRepository $organizationRepository,
                        LoggerInterface        $logger,
                        Sql                    $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $organizationRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/organization/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_organization_show', methods: ['GET'])]
    public function show(OrganizationRepository $organizationRepository,
                         LoggerInterface        $logger,
                         int                    $id = 0): Response
    {
        return $this->render('CRUD/organization/show.html.twig', [
            'records' => $organizationRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_organization_edit', methods: ['GET', 'POST'])]
    public function edit(Request                $request,
                         OrganizationRepository $organizationRepository,
                         LoggerInterface        $logger,
                         Sql                    $sql,
                         int                    $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $organizationRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/organization/edit.html.twig', [
            'records' => $organizationRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_organization_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('organization', ['id' => $id]);
        return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
    }
}
