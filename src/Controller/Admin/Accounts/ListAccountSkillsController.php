<?php

namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\AccountRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListAccountSkillsController
 * @package App\Controller\Admin\Accounts
 * @Route("/admin/liste-competences-de-compte/{id<\d+>}", name="admin_listAccountSkills")
 * @IsGranted("ROLE_ADMIN")
 */
class ListAccountSkillsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public AccountRepository $accountRepository;

    public function __invoke(int $id): Response
    {
        $account = $this->accountRepository->find($id);
        if ($account === null) {
            throw new NotFoundHttpException("Account not found");
        }

        return $this->render('admin/listAccountSkills.html.twig', [
            'accountSkills' => $account->getAccountSkills(),
            'account' => $account,
        ]);
    }
}