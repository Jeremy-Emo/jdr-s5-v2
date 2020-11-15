<?php

namespace App\Controller\Admin\BattleStates;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\BattleStateRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListBSController
 * @package App\Controller\Admin\BattleStates
 * @Route("/admin/liste-des-statuts", name="admin_listBS")
 * @IsGranted("ROLE_ADMIN")
 */
class ListBSController extends AbstractController implements ControllerInterface
{
    /** @required */
    public BattleStateRepository $bsRepo;

    public function __invoke(): Response
    {
        $states = $this->bsRepo->findAll();

        return $this->render('admin/listBS.html.twig', [
            'states' => $states
        ]);
    }
}