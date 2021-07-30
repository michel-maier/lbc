<?php

namespace App\EventSubscriber;

use App\Core\DomainException;
use App\Core\InfrastructureException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

final class ApiExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['translateException', 255]],
        ];
    }

    public function translateException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        var_dump($exception);
        if ($exception instanceof DomainException || $exception instanceof InfrastructureException) {
            $event->setResponse(new JsonResponse(['message' => $exception->getMessage()], $exception->getCode()));
        }
        if ($exception instanceof NotEncodableValueException) {
            $event->setResponse(new JsonResponse(['message' => 'Malformed json request'], 400));
        }
        if ($exception instanceof MissingConstructorArgumentsException) {
            $event->setResponse(new JsonResponse(['message' => 'Missing some mandatories'], 400));
        }
    }
}