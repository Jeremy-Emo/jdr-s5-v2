<?php

namespace App\Controller\Heroes\Items;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Manager\StuffManager;
use App\Repository\HeroRepository;
use App\Repository\ItemSlotRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InventoryController
 * @package App\Controller\Heroes\Items
 * @Route("/heros/{id<\d+>}/inventaire", name="heroInventory")
 * @IsGranted("ROLE_USER")
 */
class InventoryController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public HeroRepository $heroRepository;

    /** @required  */
    public ItemSlotRepository $itemSlotRepository;

    /**
     * @param int $id
     * @return Response
     */
    public function __invoke(int $id): Response
    {
        if (!empty(array_intersect(
            ['ROLE_MJ', 'ROLE_ADMIN'],
            $this->getUser()->getRoles())
        )) {
            $hero = $this->heroRepository->find($id);
        } else {
            $hero = $this->heroRepository->findOneBy([
                'account' => $this->getUser(),
                'id' => $id,
            ]);
        }

        if ($hero === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        $slots = $this->itemSlotRepository->findAll();

        return $this->render('heroes/inventory.html.twig', [
            'hero' => $hero,
            'stuff' => StuffManager::getStuffWithEmptySlots($hero->getFighterInfos(), $slots),
            'heroItems' => $hero->getFighterInfos()->getHeroItems(),
        ]);
    }
}