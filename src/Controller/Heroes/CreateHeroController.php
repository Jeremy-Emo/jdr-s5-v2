<?php

namespace App\Controller\Heroes;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CreateHeroType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Heroes\CreateHeroScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateHeroController
 * @package App\Controller\Heroes
 * @Route("/creer-personnage", name="createHero")
 * @IsGranted("ROLE_USER")
 */
class CreateHeroController extends AbstractController implements ControllerInterface
{
    /** @required */
    public CreateHeroScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CreateHeroType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, $this->getUser());
    }
}