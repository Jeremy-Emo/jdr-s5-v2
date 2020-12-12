<?php

namespace App\Controller\Battles;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CreateBattleType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Battle\CreateBattleScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateBattleController
 * @package App\Controller\Battles
 * @Route("/mj/creation-de-combat", name="mj_createBattle")
 * @IsGranted("ROLE_MJ")
 */
class CreateBattleController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public CreateBattleScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CreateBattleType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, $this->getUser()->getCurrentParty());
    }
}