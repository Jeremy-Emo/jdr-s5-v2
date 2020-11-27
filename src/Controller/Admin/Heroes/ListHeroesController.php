<?php

namespace App\Controller\Admin\Heroes;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListHeroesController
 * @package App\Controller\Admin\Heroes
 * @Route("/admin/liste-des-heros", name="admin_listHeroes")
 * @IsGranted("ROLE_ADMIN")
 */
class ListHeroesController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public HeroRepository $heroRepository;

    public function __invoke(): Response
    {
        $heroes = $this->heroRepository->findAll();

        return $this->render('admin/listHeroes.html.twig', [
            'heroes' => $heroes
        ]);
    }
}