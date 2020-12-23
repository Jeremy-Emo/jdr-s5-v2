<?php

namespace App\Controller\Heroes\Familiars;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\FamiliarRepository;
use App\Repository\FighterItemRepository;
use App\Scenario\Familiar\GiveItemToFamiliarScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GiveItemToFamiliarController
 * @package App\Controller\Heroes\Familiars
 * @Route("/heros/familier/{familiarId<\d+>}/donner/{id<\d+>}", name="giveItemToFamiliar")
 * @IsGranted("ROLE_USER")
 */
class GiveItemToFamiliarController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public FighterItemRepository $fItemRepository;

    /** @required  */
    public FamiliarRepository $familiarRepository;

    /** @required  */
    public GiveItemToFamiliarScenario $scenario;

    public function __invoke(int $id, int $familiarId): Response
    {
        $fItem = $this->fItemRepository->find($id);
        if ($fItem === null || $fItem->getHero()->getHero() === null) {
            throw new NotFoundHttpException("Item not found");
        }

        $familiar = $this->familiarRepository->findOneBy([
            'id' => $familiarId,
            'master' => $fItem->getHero()->getHero(),
        ]);
        if ($familiar === null) {
            throw new NotFoundHttpException("Familiar not found");
        }

        return $this->scenario->handle($familiar, $fItem);
    }
}