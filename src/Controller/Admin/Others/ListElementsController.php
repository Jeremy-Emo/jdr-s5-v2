<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\ElementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListElementsController
 * @package App\Controller\Admin\Others
 * @Route("/admin/liste-des-elements", name="admin_listElements")
 * @IsGranted("ROLE_ADMIN")
 */
class ListElementsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public ElementRepository $elemRepository;

    public function __invoke(): Response
    {
        $elements = $this->elemRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'elements' => $elements
        ]);
    }
}