<?php

namespace App\Controller\Heroes\Familiars;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Scenario\Familiar\ToggleFamiliarInvocationScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToggleFamiliarInvocationController
 * @package App\Controller\Heroes\Familiars
 * @Route("/familier/{id<\d+>}/changer-invocation", name="toggleFamiliarInvocation")
 * @IsGranted("ROLE_USER")
 */
class ToggleFamiliarInvocationController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public ToggleFamiliarInvocationScenario $scenario;

    /**
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function __invoke(int $id): Response
    {
        return $this->scenario->handle($id);
    }
}