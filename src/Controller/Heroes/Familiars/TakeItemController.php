<?php

namespace App\Controller\Heroes\Familiars;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\FighterItemRepository;
use App\Scenario\Familiar\GiveItemToFamiliarScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TakeItemController
 * @package App\Controller\Heroes\Familiars
 * @Route("/heros/familier/prendre-objet/{id<\d+>}", name="takeItemOfFamiliar")
 * @IsGranted("ROLE_USER")
 */
class TakeItemController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public FighterItemRepository $fItemRepository;

    /** @required  */
    public GiveItemToFamiliarScenario $scenario;

    public function __invoke(int $id): Response
    {
        $fItem = $this->fItemRepository->find($id);
        if ($fItem === null || $fItem->getHero()->getFamiliar() === null) {
            throw new NotFoundHttpException("Item not found");
        }

        return $this->scenario->handle($fItem->getHero()->getFamiliar(), $fItem, false);
    }
}