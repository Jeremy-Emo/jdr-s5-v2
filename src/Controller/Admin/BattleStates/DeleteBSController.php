<?php

namespace App\Controller\Admin\BattleStates;

use App\AbstractClass\AbstractController;
use App\Exception\ControllerException;
use App\Interfaces\ControllerInterface;
use App\Repository\BattleStateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteBSController
 * @package App\Controller\Admin\BattleStates
 * @Route("/admin/supprimer-statut/{id<\d+>}", name="admin_deleteBS")
 * @IsGranted("ROLE_ADMIN")
 */
class DeleteBSController extends AbstractController implements ControllerInterface
{
    /** @required */
    public BattleStateRepository $bsRepo;

    /** @required */
    public EntityManagerInterface $em;

    /**
     * @param int $id
     * @return Response
     * @throws ControllerException
     */
    public function __invoke(int $id): Response
    {
        try {
            $bs = $this->bsRepo->find($id);
            $this->em->remove($bs);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->error("Error when trying to delete BattleState", [
                'idTried' => $id,
                'exception' => $e->getMessage(),
                'method' => __METHOD__
            ]);
            throw new ControllerException($e->getMessage());
        }

        return $this->redirectToRoute('admin_listBS');
    }
}