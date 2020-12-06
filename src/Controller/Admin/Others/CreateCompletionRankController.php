<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveCompletionRankType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateCompletionRankController
 * @package App\Controller\Admin\Others
 * @Route("/admin/nouveau-rang-de-completion", name="admin_createCompletionRank")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateCompletionRankController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SaveCompletionRankType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_completion_rank', 'admin_globalOthers');
    }
}