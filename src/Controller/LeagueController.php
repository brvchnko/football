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

    public function delete()
    {

    }
}