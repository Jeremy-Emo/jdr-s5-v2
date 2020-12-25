<?php

namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\AccountSkillType;
use App\Interfaces\ControllerInterface;
use App\Repository\AccountSkillsRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditAccountSkillController
 * @package App\Controller\Admin\Accounts
 * @Route("/admin/modifier-competence-de-compte/{id<\d+>}", name="admin_editAccountSkill")
 * @IsGranted("ROLE_ADMIN")
 */
class EditAccountSkillController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required  */
    public AccountSkillsRepository $accountSkillRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $as = $this->accountSkillRepository->find($id);
        if ($as === null) {
            throw new NotFoundHttpException("Account's skill not found");
        }

        $form = $this->createForm(AccountSkillType::class, $as);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_account_skill', 'admin_listAccountSkills', ['id' => $as->getAccount()->getId()]);
    }
}