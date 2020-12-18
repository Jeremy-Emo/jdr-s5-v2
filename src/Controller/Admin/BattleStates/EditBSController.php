<?php

namespace App\Controller\Admin\BattleStates;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveBattleStateType;
use App\Interfaces\ControllerInterface;
use App\Repository\BattleStateRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditBSController
 * @package App\Controller\Admin\BattleStates
 * @Route("/admin/modifier-statut/{id<\d+>}", name="admin_editBS")
 * @IsGranted("ROLE_ADMIN")
 */
class EditBSController extends AbstractController implements ControllerInterface
{
    /** @required */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required */
    public BattleStateRepository $bsRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $bs = $this->bsRepository->find($id);
        if ($bs === null) {
            throw new NotFoundHttpException("Status not found");
        }

        $form = $this->createForm(SaveBattleStateType::class, $bs);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_bs', 'admin_listBS');
    }
}