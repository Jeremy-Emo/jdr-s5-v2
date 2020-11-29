<?php

namespace App\Controller\Admin\Skills;

use App\AbstractClass\AbstractController;
use App\Entity\FightingSkillInfo;
use App\Entity\Skill;
use App\Exception\ScenarioException;
use App\Form\Type\SaveSkillType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Skill\SaveSkillScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateSkillController
 * @package App\Controller\Admin\Skills
 * @Route("/admin/creation-de-competence", name="admin_createSkill")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateSkillController extends AbstractController implements ControllerInterface
{
    /** @required */
    public SaveSkillScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SaveSkillType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}