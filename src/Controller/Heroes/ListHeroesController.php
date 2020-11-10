<?php

namespace App\Controller\Heroes;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListHeroesController
 * @package App\Controller\Heroes
 * @Route("/liste-des-personnages", name="listHeroes")
 * @IsGranted("ROLE_USER")
 */
class ListHeroesController extends AbstractController implements ControllerInterface
{
    /** @required */
    public HeroRepository $heroRepository;

    public function __invoke(): Response
    {
        $heroes = $this->heroRepository->findBy([
            'account' => $this->getUser()
        ]);

        return $this->render('heroes/list.html.twig', [
            'heroes' => $heroes,
        ]);
    }
}