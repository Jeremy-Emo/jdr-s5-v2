<?php

namespace App\Controller\Heroes\Items;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use App\Scenario\Heroes\ToggleInventoryScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToggleInventoryController
 * @package App\Controller\Heroes\Items
 * @Route("/heros/{heroId<\d+>}/changer-inventaire/{type}/{stuffId<\d+>}", name="heroToggleInventory")
 * @IsGranted("ROLE_USER")
 */
class ToggleInventoryController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public HeroRepository $heroRepository;

    /** @required  */
    public ToggleInventoryScenario $scenario;

    /**
     * @param string $type
     * @param int $heroId
     * @param int $stuffId
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(string $type, int $heroId, int $stuffId): Response
    {
        if (
            !empty(
                array_intersect(
                    ['ROLE_MJ', 'ROLE_ADMIN'],
                    $this->getUser()->getRoles()
                )
            )
        ) {
            $hero = $this->heroRepository->find($heroId);
        } else {
            $hero = $this->heroRepository->findOneBy([
                'account' => $this->getUser(),
                'id' => $heroId,
            ]);
        }
        if ($hero === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        return $this->scenario->handle($hero, $stuffId, $type);
    }
}