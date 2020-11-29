<?php

namespace App\Controller\Admin\Skills;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveSkillTagType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateSkillTagController
 * @package App\Controller\Admin\Skills
 * @Route("/admin/nouvelle-famille-de-competences", name="admin_createSkillTag")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateSkillTagController extends AbstractController implements ControllerInterface
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
        $form = $this->createForm(SaveSkillTagType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_skill_tag', 'admin_listSkills');
    }
}