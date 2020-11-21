<?php

namespace App\Controller\Heroes\Skills;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Scenario\Skill\BuySkillScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuyRandomSkillController
 * @package App\Controller\Heroes
 * @Route("/heros/acheter-competence-aleatoire", name="buyRandomHeroSkill")
 * @IsGranted("ROLE_USER")
 */
class BuyRandomSkillController extends AbstractController implements ControllerInterface
{
    /** @required */
    public BuySkillScenario $scenario;

    public function __invoke(Request $request): Response
    {
        $heroId = $request->request->get('heroId');
        return $this->scenario->handle($heroId, null, $this->getUser(), true);
    }
}