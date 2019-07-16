<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Request\UserInput;
use App\Service\UserService;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/register")
 */
class RegisterController extends AbstractController
{
    /** @var UserService */
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("", methods={"POST"})
     * @ParamConverter(
     *     class="App\Model\Request\UserInput",
     *     name="userInput",
     *     options={"validate": true}
     *     )
     */
    public function register(UserInput $userInput, UserManagerInterface $userManager): JsonResponse
    {
        return $this->json(
            $this->service->create($userInput, $userManager),
            JsonResponse::HTTP_CREATED
        );
    }
}
