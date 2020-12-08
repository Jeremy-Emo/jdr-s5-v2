<?php

namespace App\Controller\Admin\Monsters;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveMonsterType;
use App\Interfaces\ControllerInterface;
use App\Repository\MonsterRepository;
use App\Scenario\Monster\SaveMonsterScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditMonsterController
 * @package App\Controller\Admin\Monsters
 * @Route("/admin/modifier-monstre/{id<\d+>}", name="admin_editMonster")
 * @IsGranted("ROLE_ADMIN")
 */
class EditMonsterController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveMonsterScenario $scenario;

    /** @required  */
    public MonsterRepository $monsterRepository;

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

        $form = $this->createForm(SaveMonsterType::class, $monster);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_monster');
    }
}