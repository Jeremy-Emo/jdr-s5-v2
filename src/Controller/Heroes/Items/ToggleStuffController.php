<?php

namespace App\Controller\Heroes\Items;

use App\AbstractClass\AbstractController;
use App\Exception\AuthorizationException;
use App\Interfaces\ControllerInterface;
use App\Repository\FighterItemRepository;
use App\Repository\HeroRepository;
use App\Scenario\Heroes\ToggleStuffScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToggleStuffController
 * @package App\Controller\Heroes\Items
 * @Route("/heros/{heroId<\d+>}/changer-equipement/{stuffId<\d+>}", name="heroToggleStuff")
 * @IsGranted("ROLE_USER")
 */
class ToggleStuffController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public HeroRepository $heroRepository;

    /** @required  */
    public FighterItemRepository $stuffRepository;

    /** @required  */
    public ToggleStuffScenario $scenario;

    /**
     * @param int $heroId
     * @param int $stuffId
     * @return Response
     * @throws AuthorizationException
     */
    public function __invoke(int $heroId, int $stuffId): Response
    {
        $hero = $this->heroRepository->find($heroId);
        $stuff = $this->stuffRepository->find($stuffId);
        if ($hero === null || $stuff === null) {
            throw new NotFoundHttpException("Incorrect values");
        }

        return $this->scenario->handle($hero, $stuff, $this->getUser());
    }
}