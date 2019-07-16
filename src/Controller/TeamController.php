<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Request\TeamInput;
use App\Service\TeamService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/team")
 */
class TeamController extends AbstractController
{
    /** @var TeamService */
    private $service;

    public function __construct(TeamService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("", methods={"POST"})
     * @ParamConverter(
     *     class="App\Model\Request\TeamInput",
     *     name="teamInput",
     *     options={"validate": true}
     *     )
     */
    public function create(TeamInput $teamInput): JsonResponse
    {
        return $this->json(
            $this->service->create($teamInput),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     * @ParamConverter(
     *     class="App\Model\Request\TeamInput",
     *     name="teamInput",
     *     options={"validate": true}
     *     )
     */
    public function replace(TeamInput $teamInput, int $id): JsonResponse
    {
        return $this->json($this->service->replace($teamInput, $id));
    }
}
