<?php

namespace App\Controller;

use App\Service\SqlService;
use App\SimpleSQL\Sql;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TableController extends AbstractController
{
    #[Route('/sql/{tab}/{id}/{subtab}', name: 'route_sql', requirements: ['tab' => '\d+', 'id' => '\d+', 'subtab' => '\d+'], methods: ['GET'])]
    public function table(Sql        $sql,
                          SqlService $sqlService,
                          int        $tab = 0,
                          int        $id = 0,
                          int        $subtab = 1): Response
    {
        if ($tab <= 0) return $this->redirectToRoute('route_root', [], Response::HTTP_SEE_OTHER);
        $title = $sqlService->sqlArray[$tab]['title'];
        $primary = $sqlService->sqlArray[$tab]['primary'];
//        $sql2 = $sqlService->sqlArray[$tab]['sql2'];
        $additional = $sqlService->sqlArray[$tab]['additional'];
        $list = $sqlService->sqlArray[$tab]['list'];
//        $list[1]['sql'] = $sql2;
        try {
            $records1 = $sql->dmlFetch($primary, []);
        } catch (\Exception $e) {
            return $this->redirectToRoute('route_root_exception', ['exc' => $e->getMessage()], Response::HTTP_SEE_OTHER);
        }
        $callArray = [
            'records1' => $records1,
            'title' => sprintf('%02u - ', $tab) . $title,
            'tab' => empty($list[1]['sql']) ? -1 : $tab,
            'subtab' => $subtab,
            'id' => $id,
            'additional' => $additional,
            'list' => $list,
        ];
        if ($id > 0) {
            try {
                $records5 = $sql->dmlFetch($list[$subtab]['sql'], [$id]);
            } catch (\Exception $e) {
                return $this->redirectToRoute('route_root_exception', ['exc' => $e->getMessage()], Response::HTTP_SEE_OTHER);
            }
            $callArray['records5'] = $records5;
        }
        return $this->render('table/index.html.twig', $callArray);
    }
}
