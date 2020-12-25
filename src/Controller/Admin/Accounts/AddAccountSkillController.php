<?php

namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Entity\AccountSkills;
use App\Exception\ScenarioException;
use App\Form\Type\AccountSkillType;
use App\Interfaces\ControllerInterface;
use App\Repository\AccountRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddAccountSkillController
 * @package App\Controller\Admin\Accounts
 * @Route("/admin/ajouter-competence-de-compte/{id<\d+>}", name="admin_createAccountSkill")
 * @IsGranted("ROLE_ADMIN")
 */
class AddAccountSkillController extends AbstractController implements ControllerInterface
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

        $accountSkill = (new AccountSkills())->setAccount($account);
        $form = $this->createForm(AccountSkillType::class, $accountSkill);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'add_account_skill', 'admin_listAccountSkills', ['id' => $id]);
    }
}