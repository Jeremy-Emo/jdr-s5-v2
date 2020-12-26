<?php

namespace App\Controller\Admin\Skills;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Interfaces\ControllerInterface;
use App\Scenario\Skill\ReleaseSkillsScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReleaseSkillsController
 * @package App\Controller\Admin\Skills
 * @Route("/admin/rendre-disponible-competences", name="admin_releaseSkills")
 * @IsGranted("ROLE_ADMIN")
 */
class ReleaseSkillsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public ReleaseSkillsScenario $scenario;

    /**
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(): Response
    {
        return $this->scenario->handle();
    }
}