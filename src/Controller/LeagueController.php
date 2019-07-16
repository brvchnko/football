<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Request\LeagueInput;
use App\Service\LeagueService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/league")
 */
class LeagueController extends AbstractController
{
    /** @var LeagueService */
    private $service;

    public function __construct(LeagueService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("", methods={"POST"})
     * @ParamConverter(
     *     class="App\Model\Request\LeagueInput",
     *     name="leagueInput",
     *     options={"validate": true}
     *     )
     */
    public function create(LeagueInput $leagueInput): JsonResponse
    {
        return $this->json(
            $this->service->create($leagueInput),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/{id}/team", methods={"GET"})
     */
    public function list(int $id): JsonResponse
    {
        return $this->json($this->service->teamList($id));
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function remove(int $id): JsonResponse
    {
        $this->service->remove($id);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}