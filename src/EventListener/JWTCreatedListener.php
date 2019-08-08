<?php

namespace App\EventListener;

use App\Entity\User;
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

        /** @var User $user */
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $payload = $event->getData();
        $payload['mfa_enabled'] = $user->isMfaEnabled();
        $payload['mfa_verified'] = true === $request->attributes->get('mfa_verified', false);

        $event->setData($payload);
    }
}
