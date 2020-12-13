<?php

namespace App\Controller\Battles;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Scenario\Battle\ContinueBattleScenario;
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
    /** @required  */
    public ContinueBattleScenario $scenario;

    public function __invoke(Request $request, int $id): Response
    {
        //TODO : create form
        //TODO : handle form by scenario
    }
}