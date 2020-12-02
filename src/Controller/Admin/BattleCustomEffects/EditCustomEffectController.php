<?php

namespace App\Controller\Admin\BattleCustomEffects;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\BattleCustomEffectType;
use App\Interfaces\ControllerInterface;
use App\Repository\CustomEffectRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditCustomEffectController
 * @package App\Controller\Admin\BattleCustomEffects
 * @Route("/admin/modifier-effet-custom/{id<\d+>}", name="admin_editCustomEffect")
 * @IsGranted("ROLE_ADMIN")
 */
class EditCustomEffectController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required  */
    public CustomEffectRepository $ceRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $ce = $this->ceRepository->find($id);
        if ($ce === null) {
            throw new NotFoundHttpException("Custom Effect not found");
        }

        $form = $this->createForm(BattleCustomEffectType::class, $ce);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_skill_custom_effect', 'admin_listSkills');
    }
}