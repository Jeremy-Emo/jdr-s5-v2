<?php

namespace App\Controller\Account;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\NewPasswordType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Account\ChangePasswordScenario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller\Account
 * @Route("/changer-mot-de-passe", name="newPassword")
 */
class NewPasswordController extends AbstractController implements ControllerInterface
{
    protected ChangePasswordScenario $scenario;

    /**
     * @param ChangePasswordScenario $changePassword
     * @required
     */
    public function setControllerInterfaces(ChangePasswordScenario $changePassword): void
    {
        $this->scenario = $changePassword;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($this->getUser(), $form);
    }
}