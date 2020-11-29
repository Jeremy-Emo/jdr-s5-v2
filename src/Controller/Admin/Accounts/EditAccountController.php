<?php

namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\AdminEditAccountType;
use App\Interfaces\ControllerInterface;
use App\Repository\AccountRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditAccountController
 * @package App\Controller\Admin\Accounts
 * @Route("/admin/modifier-compte/{id<\d+>}", name="admin_editAccount")
 * @IsGranted("ROLE_ADMIN")
 */
class EditAccountController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required  */
    public AccountRepository $accountRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $account = $this->accountRepository->find($id);
        if ($account === null) {
            throw new NotFoundHttpException("Account not found");
        }

        $form = $this->createForm(AdminEditAccountType::class, $account);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_account', 'admin_listAccounts');
    }
}