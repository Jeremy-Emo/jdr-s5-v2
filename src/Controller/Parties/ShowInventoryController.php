<?php

namespace App\Controller\Parties;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\PartyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowInventoryController
 * @package App\Controller\Parties
 * @Route("/mj/groupe/{id<\d+>}/inventaire", name="mj_partyInventory")
 * @IsGranted("ROLE_MJ")
 */
class ShowInventoryController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public PartyRepository $partyRepository;

    public function __invoke(int $id): Response
    {
        $party = $this->partyRepository->find($id);
        if ($party === null) {
            throw new NotFoundHttpException("Party not found");
        }

        return $this->render('party/inventory.html.twig', [
            'partyItems' => $party->getPartyItems()
        ]);
    }
}