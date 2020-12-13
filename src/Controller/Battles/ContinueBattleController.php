<?php

namespace App\Controller\Battles;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContinueBattleController
 * @package App\Controller\Battles
 * @Route("/mj/continuer-combat/{id<\d+>}", name="mj_continueBattle")
 * @IsGranted("ROLE_MJ")
 */
class ContinueBattleController extends AbstractController implements ControllerInterface
{
    public function __invoke(Request $request, int $id): Response
    {
        //TODO : add scenario which :
        /*
         * - Get current turn
         * - Get history turns
         * - Get Battle form
         * - Calculate all damages
         * - Save history
         */
    }
}