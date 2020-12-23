<?php

namespace App\Controller\Heroes\Familiars;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\FamiliarRepository;
use App\Repository\FighterItemRepository;
use App\Scenario\Familiar\EquipFamiliarScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EquipMonsterController
 * @package App\Controller\Admin\Monsters
 * @Route("/familier/equiper/{id<\d+>}", name="equipFamiliar")
 * @IsGranted("ROLE_USER")
 */
class EquipFamiliarController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public FighterItemRepository $fItemRepository;

    /** @required  */
    public EquipFamiliarScenario $scenario;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function __invoke(Request $request, int $id): Response
    {
        $fItem = $this->fItemRepository->find($id);
        if ($fItem === null) {
            throw new NotFoundHttpException("Item not found");
        }

        return $this->scenario->handle($fItem);
    }
}