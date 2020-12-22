<?php

namespace App\Controller\Admin\Familiars;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\FighterManageSkillsType;
use App\Interfaces\ControllerInterface;
use App\Repository\FamiliarRepository;
use App\Scenario\Fighters\ManageSkillsScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GiveSkillsToFamiliarsController
 * @package App\Controller\Admin\Familiars
 * @Route("/admin/competences-familier/{id<\d+>}", name="admin_skillsFamiliar")
 * @IsGranted("ROLE_ADMIN")
 */
class GiveSkillsToFamiliarsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public FamiliarRepository $familiarRepository;

    /** @required  */
    public ManageSkillsScenario $scenario;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $familiar = $this->familiarRepository->find($id);
        if ($familiar === null) {
            throw new NotFoundHttpException("Familiar not found");
        }

        $form = $this->createForm(FighterManageSkillsType::class, $familiar->getFighterInfos());
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'familiar_skills', true);
    }
}