<?php

namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\AccountSkillsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteAccountSkillController
 * @package App\Controller\Admin\Accounts
 * @Route("/admin/supprimer-competence-de-compte/{id<\d+>}", name="admin_deleteAccountSkill")
 * @IsGranted("ROLE_ADMIN")
 */
class DeleteAccountSkillController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public AccountSkillsRepository $accountSkillRepository;

    /** @required  */
    public EntityManagerInterface $em;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function __invoke(Request $request, int $id): Response
    {
        $as = $this->accountSkillRepository->find($id);
        if ($as === null) {
            throw new NotFoundHttpException("Account's skill not found");
        }

        $account = $as->getAccount();

        $this->em->remove($as);
        $this->em->flush();

        return $this->redirectToRoute('admin_listAccountSkills', ['id' => $account->getId()]);
    }
}