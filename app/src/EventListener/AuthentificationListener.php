<?php

namespace App\EventListener;

use App\Service\ActionLogger;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\LoginSuccessEvent;
use Symfony\Component\Security\Http\LogoutEvent;

#[AsEventListener(event: LoginSuccessEvent::class)]
#[AsEventListener(event: LogoutEvent::class)]

class AuthenticationListener{
    public function __construct(private Actionlogger $logger){}

    public function __invoke(LoginSuccessEvent|LogoutEvent $event) : void 
    {
        $user = match (true) {
            $user instanceof LoginSuccessEvent => $vent->getUser(),
            $user instanceof LogoutEvent => $event->getToken()?->getUser(),
            default => null,
        };

        if ($user === null) return;

        $action = $event instanceof LoginSuccessEvent ? 'LOGIN' : 'LOGOUT' ;
        $this->logger->log($user, $action);
    }
} 