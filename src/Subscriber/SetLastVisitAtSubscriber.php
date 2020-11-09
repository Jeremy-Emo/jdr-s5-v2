<?php

namespace App\Subscriber;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SetLastVisitAtSubscriber implements EventSubscriberInterface
{
    protected EntityManagerInterface $manager;

    /**
     * @param EntityManagerInterface $entityManager
     * @required
     */
    public function setInterfaces(EntityManagerInterface $entityManager): void
    {
        $this->manager = $entityManager;
    }

    public function onLogin(InteractiveLoginEvent $event): InteractiveLoginEvent
    {
        /** @var Account $user */
        $user = $event->getAuthenticationToken()->getUser();
        $user->setLastVisitAt(new \DateTime('now'));

        $this->manager->flush();
        return $event;
    }

    public static function getSubscribedEvents(): array
    {
        return [SecurityEvents::INTERACTIVE_LOGIN => 'onLogin'];
    }
}