<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Exception\MultifactorAuthenticationNotVerifiedException;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTDecodedListener implements EventSubscriberInterface 
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_DECODED => 'onJWTDecoded'
        ];
    }

    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = $event->getPayload();

        if ('api_mfa_verify' === $request->get('_route')) {
            return;
        }
        
        if ($payload['mfa_enabled'] && !$payload['mfa_verified']) {
            throw new MultifactorAuthenticationNotVerifiedException('Multifactor Authentication not verified.');
        }
    }
}
