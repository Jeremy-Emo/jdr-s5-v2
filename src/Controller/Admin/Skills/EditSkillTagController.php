<?php

namespace App\Controller\Admin\Skills;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveSkillTagType;
use App\Interfaces\ControllerInterface;
use App\Repository\SkillTagRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditSkillTagController
 * @package App\Controller\Admin\Skills
 * @Route("/admin/modifier-tag/{id<\d+>}", name="admin_editSkillTag")
 * @IsGranted("ROLE_ADMIN")
 */
class EditSkillTagController extends AbstractController implements ControllerInterface
{
    /** @required */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required */
    public SkillTagRepository $stRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $skillTag = $this->stRepository->find($id);
        if ($skillTag === null) {
            throw new NotFoundHttpException("Skill tag not found");
        }

        $form = $this->createForm(SaveSkillTagType::class, $skillTag);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_skill_tag', 'admin_listSkillTags');
    }
}