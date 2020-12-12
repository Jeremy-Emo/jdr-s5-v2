<?php

namespace App\Controller\Battles;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\BattleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListBattlesController
 * @package App\Controller\Battles
 * @Route("/mj/liste-des-combats", name="mj_listBattles")
 * @IsGranted("ROLE_MJ")
 */
class ListBattlesController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public BattleRepository $battleRepository;

    public function __invoke(): Response
    {
        $battles = $this->battleRepository->findBy([
            'party' => $this->getUser()->getCurrentParty()
        ]);

        return $this->render('battle/listBattles.html.twig', [
            'battles' => $battles,
        ]);
    }
}