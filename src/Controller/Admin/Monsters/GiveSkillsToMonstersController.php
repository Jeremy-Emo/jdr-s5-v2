<?php

namespace App\Controller\Admin\Monsters;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\FighterManageSkillsType;
use App\Interfaces\ControllerInterface;
use App\Repository\MonsterRepository;
use App\Scenario\Fighters\ManageSkillsScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GiveSkillsToMonstersController
 * @package App\Controller\Admin\Monsters
 * @Route("/admin/competences-monstre/{id<\d+>}", name="admin_skillsMonster")
 * @IsGranted("ROLE_ADMIN")
 */
class GiveSkillsToMonstersController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public MonsterRepository $monsterRepository;

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
        $monster = $this->monsterRepository->find($id);
        if ($monster === null) {
            throw new NotFoundHttpException("Monster not found");
        }

        $form = $this->createForm(FighterManageSkillsType::class, $monster->getFighterInfos());
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'monster_skills');
    }
}