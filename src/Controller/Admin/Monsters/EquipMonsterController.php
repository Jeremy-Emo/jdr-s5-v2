<?php

namespace App\Controller\Admin\Monsters;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\StuffType;
use App\Interfaces\ControllerInterface;
use App\Repository\ItemSlotRepository;
use App\Repository\MonsterRepository;
use App\Scenario\Monster\EquipMonsterScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EquipMonsterController
 * @package App\Controller\Admin\Monsters
 * @Route("/admin/equiper-monstre/{id<\d+>}", name="admin_equipMonster")
 * @IsGranted("ROLE_ADMIN")
 */
class EquipMonsterController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public MonsterRepository $monsterRepository;

    /** @required  */
    public ItemSlotRepository $itemSlotRepository;

    /** @required  */
    public EquipMonsterScenario $scenario;

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

        $form = $this->createForm(StuffType::class, null, [
            'itemSlots' => $this->itemSlotRepository->findBy([
                'isForFamiliar' => false,
            ]),
            'existingStuff' => $monster->getFighterInfos()->getHeroItems(),
        ]);
        $form->handleRequest($request);

        return $this->scenario->handle($form, $monster->getFighterInfos());
    }
}