<?php

namespace App\Controller\Account;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller\Account
 * @Route("/", name="index")
 */
class HomeController extends AbstractController implements ControllerInterface
{
    public function __invoke(): Response
    {
        if (empty($this->getUser())) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'account/home.html.twig'
        );
    }
}