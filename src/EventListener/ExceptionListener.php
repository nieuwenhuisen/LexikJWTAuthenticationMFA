<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Exception\MultifactorAuthenticationNotVerifiedException;

class ExceptionListener implements EventSubscriberInterface 
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!$exception instanceof MultifactorAuthenticationNotVerifiedException) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'code' => 403,
            'message' => $exception->getMessage(),
        ], 403));
    }
}