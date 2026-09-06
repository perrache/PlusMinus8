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
        $sql1 = $sqlService->sqlArray[$tab]['sql1'];
        $sql2 = $sqlService->sqlArray[$tab]['sql2'];
        $sql3 = $sqlService->sqlArray[$tab]['sql3'];
        $sql5 = $sqlService->sqlArray[$tab]['sql5'];
        $sql5[1]['sql'] = $sql2;
        try {
            $records1 = $sql->dmlFetch($sql1, []);
        } catch (\Exception $e) {
            return $this->redirectToRoute('route_root_exception', ['exc' => $e->getMessage()], Response::HTTP_SEE_OTHER);
        }
        $callArray = [
            'records1' => $records1,
            'title' => sprintf('%02u - ', $tab) . $title,
            'tab' => empty($sql2) ? -1 : $tab,
            'subtab' => $subtab,
            'id' => $id,
            'sql3' => $sql3,
            'sql5' => $sql5,
        ];
        if ($id > 0) {
            try {
                $records5 = $sql->dmlFetch($sql5[$subtab]['sql'], [$id]);
            } catch (\Exception $e) {
                return $this->redirectToRoute('route_root_exception', ['exc' => $e->getMessage()], Response::HTTP_SEE_OTHER);
            }
            $callArray['records5'] = $records5;
        }
        return $this->render('table/index.html.twig', $callArray);
    }
}
