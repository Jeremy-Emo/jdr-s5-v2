<?php

namespace App\Controller\Heroes;

use App\AbstractClass\AbstractController;
use App\Exception\ControllerException;
use App\Interfaces\ControllerInterface;
use App\Scenario\Heroes\ToggleActiveHeroScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToggleActiveHeroController
 * @package App\Controller\Heroes
 * @Route("/choisir-personnage-actif/{id<\d+>}", name="chooseHero")
 * @IsGranted("ROLE_USER")
 */
class ToggleActiveHeroController extends AbstractController implements ControllerInterface
{
    /** @required */
    public ToggleActiveHeroScenario $scenario;

    /**
     * @param int $id
     * @return Response
     * @throws ControllerException
     */
    public function __invoke(int $id): Response
    {
        return $this->scenario->handle($id, $this->getUser());
    }
}