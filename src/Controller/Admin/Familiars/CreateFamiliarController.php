<?php

namespace App\Controller\Admin\Familiars;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveFamiliarType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Familiar\SaveFamiliarScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateFamiliarController
 * @package App\Controller\Admin\Familiars
 * @Route("/admin/creer-nouveau-familier", name="admin_createFamiliar")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateFamiliarController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFamiliarScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SaveFamiliarType::class, $this->scenario->generateBaseFamiliar());
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}