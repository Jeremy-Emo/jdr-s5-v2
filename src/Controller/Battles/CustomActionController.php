<?php

namespace App\Controller\Battles;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CustomActionType;
use App\Interfaces\ControllerInterface;
use App\Repository\BattleTurnRepository;
use App\Scenario\Battle\CreateCustomTurnScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomActionController
 * @package App\Controller\Battles
 * @Route("/mj/combat/action-personnalisee/{id<\d+>}", name="mj_customActionBattle")
 * @IsGranted("ROLE_MJ")
 */
class CustomActionController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public BattleTurnRepository $btRepository;

    /** @required  */
    public CreateCustomTurnScenario $scenario;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $bt = $this->btRepository->find($id);
        if ($id === null) {
            throw new NotFoundHttpException("BattleTurn not found");
        }

        $form = $this->createForm(CustomActionType::class, $bt);
        $form->handleRequest($request);

        return $this->scenario->handle($form, $bt);
    }
}