<?php

namespace App\Controller\Parties;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SavePartyItemType;
use App\Interfaces\ControllerInterface;
use App\Repository\PartyRepository;
use App\Scenario\Party\SavePartyItemScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddPartyItemController
 * @package App\Controller\Parties
 * @Route("/mj/groupe/{id<\d+>}/ajouter-item", name="mj_partyAddItem")
 * @IsGranted("ROLE_MJ")
 */
class AddPartyItemController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SavePartyItemScenario $scenario;

    /** @required  */
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

        $form = $this->createForm(SavePartyItemType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, $party);
    }
}