<?php

namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CreateAccountType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Account\CreateAccountScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateAccountController
 * @package App\Controller\Admin
 * @Route("/admin/creation-de-compte", name="admin_createAccount")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateAccountController extends AbstractController implements ControllerInterface
{
    /** @required */
    public CreateAccountScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CreateAccountType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}