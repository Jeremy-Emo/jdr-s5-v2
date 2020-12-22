<?php

namespace App\Controller\Parties;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\PartyItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeletePartyItemController
 * @package App\Controller\Parties
 * @Route("/mj/groupe/supprimer-item/{id<\d+>}", name="mj_partyRemoveItem")
 * @IsGranted("ROLE_MJ")
 */
class DeletePartyItemController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public PartyItemRepository $partyItemRepository;

    /** @required  */
    public EntityManagerInterface $em;

    public function __invoke(int $id): Response
    {
        $item = $this->partyItemRepository->find($id);
        if ($item === null) {
            throw new NotFoundHttpException("Item not found");
        }
        $party = $item->getParty();

        $this->em->remove($item);
        $this->em->flush();

        return $this->redirectToRoute('mj_partyInventory', [
            'id' => $party->getId(),
        ]);
    }
}