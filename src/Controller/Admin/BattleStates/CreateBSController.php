<?php

namespace App\Controller\Admin\BattleStates;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveBattleStateType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateBSController
 * @package App\Controller\Admin\BattleStates
 * @Route("/admin/creation-de-statut", name="admin_createBS")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateBSController extends AbstractController implements ControllerInterface
{
    /** @required */
    public SaveFromGenericAdminFormScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SaveBattleStateType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_bs', 'admin_listBS');
    }
}