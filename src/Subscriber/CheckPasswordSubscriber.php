<?php

namespace App\Subscriber;

use App\AbstractClass\AbstractController;
use App\Controller\Account\NewPasswordController;
use App\Entity\Account;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CheckPasswordSubscriber implements EventSubscriberInterface
{
    protected ContainerInterface $container;
    protected UrlGeneratorInterface $router;

    /**
     * @param ContainerInterface $container
     * @param UrlGeneratorInterface $urlGenerator
     * @required
     */
    public function setInterfaces(
        ContainerInterface $container,
        UrlGeneratorInterface $urlGenerator
    ): void {
        $this->container = $container;
        $this->router = $urlGenerator;
    }

    protected function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return null;
        }

        if (!\is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    public function onRequest(ControllerEvent $event): ControllerEvent
    {
        if (
            !($event->getController() instanceof NewPasswordController)
            && $event->isMasterRequest()
        ) {
            /** @var Account $user */
            $user = $this->getUser();
            if ($user !== null && $user->getIsPasswordChangeNeeded()) {
                $event->setController(
                    function() {
                        return new RedirectResponse(
                            $this->router->generate('newPassword')
                        );
                    }
                );
            }
        }

        return $event;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::CONTROLLER => 'onRequest'];
    }
}