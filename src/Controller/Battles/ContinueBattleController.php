<?php

namespace App\Controller\Battles;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Interfaces\ControllerInterface;
use App\Repository\BattleRepository;
use App\Scenario\Battle\ContinueBattleScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    /** @required  */
    public BattleRepository $battleRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $battle = $this->battleRepository->find($id);
        if ($battle === null) {
            throw new NotFoundHttpException("Battle not found !");
        }

        $form = $this->scenario->setBattle($battle)->getForm();
        $form->handleRequest($request);
        return $this->scenario->handle($form);
    }
}