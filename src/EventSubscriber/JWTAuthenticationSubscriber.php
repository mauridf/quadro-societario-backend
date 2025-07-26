<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class JWTAuthenticationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_failure' => 'onAuthenticationFailure',
            'lexik_jwt_authentication.on_jwt_expired' => 'onJWTExpired',
            'lexik_jwt_authentication.on_jwt_invalid' => 'onJWTInvalid',
            'lexik_jwt_authentication.on_jwt_not_found' => 'onJWTNotFound',
        ];
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $response = new JsonResponse([
            'error' => 'Credenciais inválidas',
            'message' => 'Email ou senha incorretos'
        ], 401);

        $event->setResponse($response);
    }

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $response = new JsonResponse([
            'error' => 'Token expirado',
            'message' => 'Seu token expirou, faça login novamente'
        ], 401);

        $event->setResponse($response);
    }

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $response = new JsonResponse([
            'error' => 'Token inválido',
            'message' => 'Seu token é inválido'
        ], 401);

        $event->setResponse($response);
    }

    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $response = new JsonResponse([
            'error' => 'Token não encontrado',
            'message' => 'Token JWT não encontrado no cabeçalho Authorization'
        ], 401);

        $event->setResponse($response);
    }
}