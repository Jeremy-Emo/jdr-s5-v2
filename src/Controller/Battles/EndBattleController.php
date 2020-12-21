<?php

namespace App\Controller\Battles;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Interfaces\ControllerInterface;
use App\Repository\BattleRepository;
use App\Scenario\Battle\EndBattleScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EndBattleController
 * @package App\Controller\Battles
 * @Route("/mj/terminer-combat/{id<\d+>}", name="mj_endBattle")
 * @IsGranted("ROLE_MJ")
 */
class EndBattleController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public EndBattleScenario $scenario;

    /** @required  */
    public BattleRepository $battleRepository;

    /**
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(int $id): Response
    {
        $battle = $this->battleRepository->findOneBy([
            'id' => $id,
            'isFinished' => false,
        ]);
        if ($battle === null) {
            throw new NotFoundHttpException("Battle not found");
        }

        return $this->scenario->handle($battle);
    }
}