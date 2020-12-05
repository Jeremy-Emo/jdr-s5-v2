<?php

namespace App\Controller\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotImplementedController
 * @package App\Controller\Others
 * @Route("/en-developpement", name="notImplemented")
 */
class NotImplementedController extends AbstractController implements ControllerInterface
{
    public function __invoke(): Response
    {
        return $this->render('others/notImplemented.html.twig');
    }
}