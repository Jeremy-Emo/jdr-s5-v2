<?php

namespace App\Controller\Parties;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SavePartyType;
use App\Interfaces\ControllerInterface;
use App\Repository\PartyRepository;
use App\Scenario\Party\SavePartyScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditPartyController
 * @package App\Controller\Parties
 * @Route("/mj/modifier-groupe/{id<\d+>}", name="editParty")
 * @IsGranted("ROLE_MJ")
 */
class EditPartyController extends AbstractController implements ControllerInterface
{
    /** @required */
    public SavePartyScenario $scenario;

    /** @required */
    public PartyRepository $partyRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $party = $this->partyRepository->find($id);
        if ($party === null) {
            throw new NotFoundHttpException("Party not found");
        }

        $form = $this->createForm(SavePartyType::class, $party, [
            'id' => $this->getUser()->getId()
        ]);
        $form->handleRequest($request);

        return $this->scenario->handle($form, $this->getUser(), true);
    }
}