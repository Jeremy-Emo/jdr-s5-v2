<?php

namespace App\Controller\Security;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LogoutController
 * @package App\Controller\Security
 * @Route("/logout", name="app_logout")
 */
class LogoutController extends AbstractController implements ControllerInterface
{
    public function __invoke()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}