<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/league")
 */
class LeagueController extends AbstractController
{
    /**
     * @Route("", methods={"POST"})
     */
    public function create(): JsonResponse
    {

        return $this->json(null);
    }

    public function delete()
    {

    }
}