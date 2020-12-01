<?php

namespace App\Controller\Admin\BattleCustomEffects;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\CustomEffectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListCustomEffectsController
 * @package App\Controller\Admin\BattleCustomEffects
 * @Route("/admin/liste-des-effets-custom", name="admin_listCE")
 * @IsGranted("ROLE_ADMIN")
 */
class ListCustomEffectsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public CustomEffectRepository $ceRepository;

    public function __invoke(): Response
    {
        $ce = $this->ceRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'customEffects' => $ce
        ]);
    }
}