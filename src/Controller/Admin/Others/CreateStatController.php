<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CreateStatType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Stat\CreateStatScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateStatController
 * @package App\Controller\Admin\Others
 * @Route("/admin/nouvelle-stat", name="admin_createStat")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateStatController extends AbstractController implements ControllerInterface
{
    /** @required */
    public CreateStatScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CreateStatType::class);
        $form->handleRequest($request);
        return $this->scenario->handle($form);
    }
}