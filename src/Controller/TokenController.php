<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\TokenException;
use App\Model\Request\TokenInput;
use App\Service\TokenService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    private $service;

    public function __construct(TokenService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/token", methods={"POST"})
     *
     * @ParamConverter(
     *     class="App\Model\Request\TokenInput",
     *     name="tokenInput",
     *     options={"validate": true}
     *     )
     */
    public function createAction(TokenInput $tokenInput): Response
    {
        try {
            $token = $this->service->create($tokenInput);
        } catch (TokenException $e) {
            throw new BadRequestHttpException('Invalid login.', $e, Response::HTTP_BAD_REQUEST);
        }

        return new Response($token);
    }
}