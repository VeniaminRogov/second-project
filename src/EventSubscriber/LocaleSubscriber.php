<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private string $defaultLocale = 'en'
    )
    {}

    public function setLocale(RequestEvent $event)
    {
        $request = $event->getRequest();
        $request->setLocale($this->defaultLocale);

        if($request->cookies->has('lang'))
        {
            $locale = $request->cookies->get('lang');
            $request->setLocale($locale);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['setLocale', 20]]
        ];
    }
}