<?php

namespace App\Controller\Admin\Familiars;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\FamiliarRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListFamiliarsController
 * @package App\Controller\Admin\Familiars
 * @Route("/admin/liste-des-familiers", name="admin_listFamiliars")
 * @IsGranted("ROLE_ADMIN")
 */
class ListFamiliarsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public FamiliarRepository $familiarRepository;

    public function __invoke(): Response
    {
        $familiars = $this->familiarRepository->findAll();

        return $this->render('admin/listFamiliars.html.twig', [
            'familiars' => $familiars,
        ]);
    }
}