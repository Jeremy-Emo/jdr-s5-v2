<?php

namespace App\Controller\Parties;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Interfaces\ControllerInterface;
use App\Scenario\Party\ToggleActivePartyScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToggleActivePartyController
 * @package App\Controller\Parties
 * @Route("/mj/choisir-groupe-actif/{id<\d+>}", name="chooseParty")
 * @IsGranted("ROLE_MJ")
 */
class ToggleActivePartyController extends AbstractController implements ControllerInterface
{
    /** @required */
    public ToggleActivePartyScenario $scenario;

    /**
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(int $id): Response
    {
        return $this->scenario->handle($id, $this->getUser());
    }
}