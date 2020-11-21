<?php

namespace App\Controller\Heroes;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use App\Repository\SkillRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListSkillController
 * @package App\Controller\Heroes
 * @Route("/heros/{id<\d+>}/liste-des-competences", name="listHeroSkills")
 * @IsGranted("ROLE_USER")
 */
class ListSkillController extends AbstractController implements ControllerInterface
{
    /** @required */
    public HeroRepository $heroRepository;

    /** @required */
    public SkillRepository $skillRepository;

    public function __invoke(int $id): Response
    {
        $hero = $this->heroRepository->findOneBy([
            'account' => $this->getUser(),
            'id' => $id
        ]);

        if ($hero === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        return $this->render('heroes/listSkills.html.twig', [
            'heroSkills' => $hero->getFighterInfos()->getSkills(),
            'skills' => $this->skillRepository->findAll(),
            'skillPoints' => $hero->getFighterInfos()->getSkillPoints(),
            'heroId' => $hero->getId()
        ]);
    }
}