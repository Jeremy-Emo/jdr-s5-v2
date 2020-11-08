<?php

namespace App\Controller\Security;

use App\AbstractClass\AbstractController;
use App\Entity\Account;
use App\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class LoginController
 * @package App\Controller\Security
 * @Route("/login", name="app_login")
 */
class LoginController extends AbstractController implements ControllerInterface
{
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() instanceof Account) {
            return $this->redirectToRoute('index');
        }

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]
        );
    }
}