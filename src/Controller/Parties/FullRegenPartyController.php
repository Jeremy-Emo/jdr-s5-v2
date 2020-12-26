<?php

namespace App\Controller\Parties;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\PartyRepository;
use App\Scenario\Party\FullRegenPartyScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FullRegenPartyController
 * @package App\Controller\Parties
 * @Route("/mj/soigner-groupe/{id<\d+>}", name="fullHealParty")
 * @IsGranted("ROLE_MJ")
 */
class FullRegenPartyController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public FullRegenPartyScenario $scenario;

    /** @required  */
    public PartyRepository $partyRepository;

    /**
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function __invoke(int $id): Response
    {
        $party = $this->partyRepository->find($id);
        if ($party === null) {
            throw new NotFoundHttpException("Party not found");
        }

        return $this->scenario->handle($party);
    }
}