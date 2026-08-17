<?php

namespace App\Controller\CRUD;

use App\Repository\MinusRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/minus')]
final class MinusController extends AbstractController
{
    #[Route(name: 'app_minus_index', methods: ['GET'])]
    public function index(MinusRepository $minusRepository,
                          Sql             $sql,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/minus/index.html.twig', [
            'records' => $minusRepository->findAll($logger, ['dat' => 'desc', 'id' => 'desc']),
            'cols' => $sql->columnArray('minus'),
        ]);
    }
}
