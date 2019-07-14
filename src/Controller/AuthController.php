<?php

declare(strict_types=1);

namespace App\Controller;

use App\Manager\UserManager;
use App\Model\Request\UserInput;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 */
class AuthController extends AbstractController
{
    private $manager;

    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/register", methods={"POST"})
     * @ParamConverter(
     *     class="App\Model\Request\UserInput",
     *     name="userInput",
     *     options={"validate": true}
     *     )
     */
    public function register(UserInput $userInput, UserManagerInterface $userManager): JsonResponse
    {
        return $this->json(
            $this->manager->create($userInput, $userManager),
            JsonResponse::HTTP_CREATED
        );
    }
}