<?php

namespace App\Controller\Heroes\Items;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\FighterItemRepository;
use App\Scenario\Item\UseItemScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UseItemController
 * @package App\Controller\Heroes\Items
 * @Route("/inventaire/utiliser-objet/{id<\d+>}", name="heroUseItem")
 * @IsGranted("ROLE_USER")
 */
class UseItemController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public UseItemScenario $scenario;

    /** @required  */
    public FighterItemRepository $fItemRepository;

    /**
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function __invoke(int $id): Response
    {
        $fItem = $this->fItemRepository->find($id);
        if (
            $fItem === null
            || !$fItem->getItem()->getIsConsumable()
            || $fItem->getItem()->getConsumableEffect() === null
        ) {
            throw new NotFoundHttpException("Item not found");
        }

        return $this->scenario->handle($fItem);
    }
}