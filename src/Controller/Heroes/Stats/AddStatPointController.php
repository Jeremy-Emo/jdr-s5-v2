<?php

namespace App\Controller\Heroes\Stats;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Scenario\Heroes\AddStatPointScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddStatPointController
 * @package App\Controller\Heroes\Stats
 * @Route("/heros/augmenter-statistique", name="buyStat")
 * @IsGranted("ROLE_USER")
 */
class AddStatPointController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public AddStatPointScenario $scenario;

    public function __invoke(Request $request): Response
    {
        $statId = $request->request->get('statId');
        $hero = $this->getUser()->getCurrentHero();

        return $this->scenario->handle($hero, $statId);
    }
}