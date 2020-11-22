<?php

namespace App\Controller\Heroes;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Manager\StatManager;
use App\Repository\HeroRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowHeroController
 * @package App\Controller\Heroes
 * @Route("/heros/{id<\d+>}", name="showHero")
 * @IsGranted("ROLE_USER")
 */
class ShowHeroController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public HeroRepository $heroRepository;

    /**
     * @param int $id
     * @return Response
     */
    public function __invoke(int $id): Response
    {
        $hero = $this->heroRepository->findOneBy([
            'account' => $this->getUser(),
            'id' => $id
        ]);

        if ($hero === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        return $this->render('heroes/show.html.twig', [
            'hero' => $hero,
            'stats' => StatManager::returnTotalStats($hero->getFighterInfos()),
            'metaStats' => StatManager::returnMetaStats($hero->getFighterInfos())
        ]);
    }
}