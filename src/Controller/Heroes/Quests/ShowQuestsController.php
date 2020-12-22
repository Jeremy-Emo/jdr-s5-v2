<?php

namespace App\Controller\Heroes\Quests;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowQuestsController
 * @package App\Controller\Heroes\Quests
 * @Route("/heros/{id<\d+>}/liste-des-quetes", name="listHeroQuests")
 * @IsGranted("ROLE_USER")
 */
class ShowQuestsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public HeroRepository $heroRepository;

    public function __invoke(int $id): Response
    {
        $hero = $this->heroRepository->find($id);
        if ($hero === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        $questsNumber = 0;
        $questsNumber += $hero->getQuests()->count();
        if ($hero->getParty() !== null) {
            $questsNumber += $hero->getParty()->getQuests()->count();
        }

        return $this->render('heroes/listQuests.html.twig', [
            'hero' => $hero,
            'questsNumber' => $questsNumber,
        ]);
    }
}