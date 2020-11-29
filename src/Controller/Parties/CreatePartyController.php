<?php

namespace App\Controller\Parties;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SavePartyType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Party\CreatePartyScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreatePartyController
 * @package App\Controller\Parties
 * @Route("/creer-un-nouveau-groupe", name="createParty")
 * @IsGranted("ROLE_MJ")
 */
class CreatePartyController extends AbstractController implements ControllerInterface
{
    /** @required */
    public CreatePartyScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SavePartyType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, $this->getUser());
    }
}