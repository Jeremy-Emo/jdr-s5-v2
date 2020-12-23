<?php

namespace App\Controller\Heroes\Familiars;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Manager\StatManager;
use App\Repository\HeroRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ManageFamiliarsController
 * @package App\Controller\Heroes\Familiars
 * @Route("/heros/{id<\d+>}/familiers", name="heroFamiliars")
 * @IsGranted("ROLE_USER")
 */
class ManageFamiliarsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public HeroRepository $heroRepository;

    public function __invoke(int $id): Response
    {
        $hero = $this->heroRepository->find($id);
        if ($hero === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        $leadership = StatManager::returnTotalStat(StatManager::LEADERSHIP, $hero->getFighterInfos())['value'];
        $usedLeaderShip = 0;
        foreach ($hero->getFamiliars() as $object) {
            if ($object->getIsInvoked()) {
                $usedLeaderShip += $object->getNeedLeaderShip();
            }
        }

        return $this->render('heroes/manageFamiliars.html.twig', [
            'hero' => $hero,
            'leadership' => $leadership,
            'usedLS' => $usedLeaderShip,
        ]);
    }
}