<?php

namespace App\Controller\Admin\BattleCustomEffects;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\BattleCustomEffectType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Generic\CreateFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateCustomEffectController
 * @package App\Controller\Admin\BattleCustomEffects
 * @Route("/admin/creer-effet-personnalisé", name="adminCreateCE")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateCustomEffectController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public CreateFromGenericAdminFormScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(BattleCustomEffectType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_skill_custom_effect', 'admin_listSkills');
    }
}