<?php

namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Exception\ControllerException;
use App\Exception\ScenarioException;
use App\Form\Type\NewPasswordType;
use App\Interfaces\ControllerInterface;
use App\Repository\AccountRepository;
use App\Scenario\Account\ChangePasswordScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ChangePasswordController
 * @package App\Controller\Admin\Accounts
 * @Route("/admin/reinitialiser-mot-de-passe/{id<\d+>}", name="admin_resetPassword")
 * @IsGranted("ROLE_ADMIN")
 */
class ChangePasswordController extends AbstractController implements ControllerInterface
{
    /** @required */
    public ChangePasswordScenario $scenario;

    /** @required */
    public AccountRepository $accountRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException|ControllerException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $user = $this->accountRepository->find($id);
        if ($user === null) {
            $this->logger->warning("Error when trying to reset password for Account", [
                'idTried' => $id,
                'exception' => "User not found",
                'method' => __METHOD__
            ]);
            throw new ControllerException("User not found");
        }
        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);

        return $this->scenario->handle($user, $form, 'admin_listAccounts');
    }
}