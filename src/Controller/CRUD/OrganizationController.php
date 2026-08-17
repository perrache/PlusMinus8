<?php

namespace App\Controller\CRUD;

use App\Repository\OrganizationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
