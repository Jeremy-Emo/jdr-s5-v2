<?php

namespace App\Controller\Heroes;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Scenario\Skill\BuySkillScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuySkillController
 * @package App\Controller\Heroes
 * @Route("/heros/acheter-competence", name="buyHeroSkill")
 * @IsGranted("ROLE_USER")
 */
class BuySkillController extends AbstractController implements ControllerInterface
{
    /** @required */
    public BuySkillScenario $scenario;

    public function __invoke(Request $request): Response
    {
        $heroId = $request->request->get('heroId');
        $skillId = $request->request->get('skillId');
        return $this->scenario->handle($heroId, $skillId, $this->getUser());
    }
}