<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\WeaponTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListWeaponTypesController
 * @package App\Controller\Admin\Others
 * @Route("/admin/liste-des-types-armes", name="admin_listWeaponTypes")
 * @IsGranted("ROLE_ADMIN")
 */
class ListWeaponTypesController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public WeaponTypeRepository $wtRepository;

    public function __invoke(): Response
    {
        $wts = $this->wtRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'weaponTypes' => $wts
        ]);
    }
}