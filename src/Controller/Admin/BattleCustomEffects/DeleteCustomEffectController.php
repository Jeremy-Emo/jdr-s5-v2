<?php

namespace App\Controller\Admin\BattleCustomEffects;

use App\AbstractClass\AbstractController;
use App\Exception\ControllerException;
use App\Interfaces\ControllerInterface;
use App\Repository\CustomEffectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteCustomEffectController
 * @package App\Controller\Admin\BattleCustomEffects
 * @Route("/admin/supprimer-effet-personnalise/{id<\d+>}", name="admin_deleteCE")
 * @IsGranted("ROLE_ADMIN")
 */
class DeleteCustomEffectController extends AbstractController implements ControllerInterface
{
    /** @required */
    public CustomEffectRepository $ceRepo;

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
            $ce = $this->ceRepo->find($id);
            $this->em->remove($ce);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->error("Error when trying to delete CustomEffect", [
                'idTried' => $id,
                'exception' => $e->getMessage(),
                'method' => __METHOD__
            ]);
            throw new ControllerException($e->getMessage());
        }

        return $this->redirectToRoute('admin_listCE');
    }
}