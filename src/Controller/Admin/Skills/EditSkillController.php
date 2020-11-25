<?php

namespace App\Controller\Admin\Skills;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CreateSkillType;
use App\Interfaces\ControllerInterface;
use App\Repository\SkillRepository;
use App\Scenario\Skill\CreateSkillScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditSkillController
 * @package App\Controller\Admin\Skills
 * @Route("/admin/modifier-competence/{id<\d+>}", name="admin_editSkill")
 * @IsGranted("ROLE_ADMIN")
 */
class EditSkillController extends AbstractController implements ControllerInterface
{
    /** @required */
    public CreateSkillScenario $scenario;

    /** @required */
    public SkillRepository $skillRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $skill = $this->skillRepository->find($id);
        if ($skill === null) {
            throw new NotFoundHttpException("Skill not found");
        }

        $form = $this->createForm(CreateSkillType::class, $skill);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_skill');
    }
}