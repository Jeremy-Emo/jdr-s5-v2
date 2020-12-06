<?php

namespace App\Controller\Admin\Monsters;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\MonsterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListMonstersController
 * @package App\Controller\Admin\Monsters
 * @Route("/admin/liste-des-monstres", name="admin_listMonsters")
 * @IsGranted("ROLE_ADMIN")
 */
class ListMonstersController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public MonsterRepository $monsterRepository;

    public function __invoke(): Response
    {
        $monsters = $this->monsterRepository->findAll();

        return $this->render('admin/listMonsters.html.twig', [
            'monsters' => $monsters,
        ]);
    }
}