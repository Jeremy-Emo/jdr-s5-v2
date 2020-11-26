<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SBPType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Generic\CreateFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateStatBonusPercentController
 * @package App\Controller\Admin\Others
 * @Route("/admin/nouveau-bonus-de-stat", name="admin_createSBP")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateStatBonusPercentController extends AbstractController implements ControllerInterface
{
    /** @required */
    public CreateFromGenericAdminFormScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SBPType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_sbp', 'admin_listSkills');
    }
}