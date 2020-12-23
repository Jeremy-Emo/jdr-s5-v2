<?php

namespace App\Controller\Heroes\Familiars;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Manager\StuffManager;
use App\Repository\FamiliarRepository;
use App\Repository\ItemSlotRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EquipmentController
 * @package App\Controller\Heroes\Familiars
 * @Route("/familier/{id<\d+>}/equipement", name="stuffFamiliar")
 * @IsGranted("ROLE_USER")
 */
class EquipmentController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public FamiliarRepository $familiarRepository;

    /** @required  */
    public ItemSlotRepository $itemSlotRepository;

    public function __invoke(int $id): Response
    {
        $familiar = $this->familiarRepository->find($id);
        if ($familiar === null) {
            throw new NotFoundHttpException("Familiar not found");
        }

        $items = $familiar->getFighterInfos()->getHeroItems();

        $slots = $this->itemSlotRepository->findBy([
            'isForFamiliar' => true,
        ]);

        return $this->render("heroes/familiarInventory.html.twig", [
            'familiar' => $familiar,
            'stuff' => StuffManager::getStuffWithEmptySlots($familiar->getFighterInfos(), $slots),
            'familiarItems' => $items,
        ]);
    }
}