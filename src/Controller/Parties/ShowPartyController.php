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
 * Class ShowPartyController
 * @package App\Controller\Parties
 * @Route("/mj/voir-groupe/{id<\d+>}", name="showParty")
 * @IsGranted("ROLE_MJ")
 */
class ShowPartyController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public PartyRepository $partyRepository;

    public function __invoke(int $id): Response
    {
        $party = $this->partyRepository->findOneBy([
            'id' => $id,
            'mj' => $this->getUser()
        ]);
        if ($party === null) {
            throw new NotFoundHttpException("Party not found");
        }

        return $this->render('party/show.html.twig', [
            'party' => $party,
            'fluid' => true,
        ]);
    }
}