<?php


namespace App\Controller\Battles;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\BattleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteBattleController
 * @package App\Controller\Battles
 * @Route("/mj/supprimer-combat/{id<\d+>}", name="mj_deleteBattle")
 * @IsGranted("ROLE_MJ")
 */
class DeleteBattleController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public BattleRepository $battleRepository;

    /** @required  */
    public EntityManagerInterface $manager;

    public function __invoke(int $id): Response
    {
        $battle = $this->battleRepository->find($id);
        if ($battle === null) {
            throw new NotFoundHttpException("Battle not found !");
        }

        $this->manager->remove($battle);
        $this->manager->flush();

        return $this->redirectToRoute('mj_listBattles');
    }
}