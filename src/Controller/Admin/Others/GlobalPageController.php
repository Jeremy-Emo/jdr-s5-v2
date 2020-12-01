<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GlobalPageController
 * @package App\Controller\Admin\Others
 * @Route("/admin/autres", name="admin_globalOthers")
 * @IsGranted("ROLE_ADMIN")
 */
class GlobalPageController extends AbstractController implements ControllerInterface
{
    public function __invoke(): Response
    {
        return $this->render('admin/globalOthers.html.twig');
    }
}