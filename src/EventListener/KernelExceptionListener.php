<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class KernelExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getException();
        $message = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        $response = new JsonResponse($message);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->add($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}
