<?= "<?php\n" ?>

namespace App\Controller\CRUD;

use App\Repository\TypeRepository;
use App\SimpleSQL\Sql;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type')]
final class TypeController extends AbstractController
{
    #[Route(name: 'app_type_index', methods: ['GET'])]
    public function index(TypeRepository  $typeRepository,
                          LoggerInterface $logger): Response
    {
        return $this->render('CRUD/type/index.html.twig', [
            'records' => $typeRepository->findAll($logger, ['name' => 'asc']),
        ]);
    }

    #[Route('/new', name: 'app_type_new', methods: ['GET', 'POST'])]
    public function new(Request         $request,
                        TypeRepository  $typeRepository,
                        LoggerInterface $logger,
                        Sql             $sql): Response
    {
        if ($request->getMethod() === 'POST') {
            $typeRepository->sqlInsert($request, $logger);
            return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/type/new.html.twig', [
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/{id}', name: 'app_type_show', methods: ['GET'])]
    public function show(TypeRepository  $typeRepository,
                         LoggerInterface $logger,
                         int             $id = 0): Response
    {
        return $this->render('CRUD/type/show.html.twig', [
            'records' => $typeRepository->findBy($logger, ['id' => '= ' . $id], []),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request         $request,
                         TypeRepository  $typeRepository,
                         LoggerInterface $logger,
                         Sql             $sql,
                         int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
        if ($request->getMethod() === 'POST') {
            $typeRepository->sqlUpdate($request, $logger, $id);
            return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('CRUD/type/edit.html.twig', [
            'records' => $typeRepository->findBy($logger, ['id' => '= ' . $id], []),
            'kinds' => $sql->dmlFetch('select id, name from kind order by name'),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_type_delete', methods: ['GET'])]
    public function delete(LoggerInterface $logger,
                           Sql             $sql,
                           int             $id = 0): Response
    {
        if ($id <= 0) return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
        $logger->debug('###dmlDelete### id = ' . $id);
        $sql->dmlDelete('type', ['id' => $id]);
        return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
