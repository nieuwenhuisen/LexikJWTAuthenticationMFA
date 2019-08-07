<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener implements EventSubscriberInterface 
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED => 'onJWTCreated'
        ];
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        $payload = $event->getData();
        $payload['mfa_enabled'] = true;
        $payload['mfa_verified'] = ('api_mfa_verify' === $request->get('_route'));

        $event->setData($payload);
    }
}