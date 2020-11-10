<?php

namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Exception\ControllerException;
use App\Interfaces\ControllerInterface;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteAccountController
 * @package App\Controller\Admin
 * @Route("/admin/suppression-de-compte/{id<\d+>}", name="admin_deleteAccount")
 * @IsGranted("ROLE_ADMIN")
 */
class DeleteAccountController extends AbstractController implements ControllerInterface
{
    /** @required */
    public AccountRepository $accountRepository;

    /** @required */
    public EntityManagerInterface $em;

    /**
     * @param int $id
     * @return Response
     * @throws ControllerException
     */
    public function __invoke(int $id): Response
    {
        try {
            $user = $this->accountRepository->find($id);
            $this->em->remove($user);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->error("Error when trying to delete Account", [
                'idTried' => $id,
                'exception' => $e->getMessage(),
                'method' => __METHOD__
            ]);
            throw new ControllerException($e->getMessage());
        }

        return $this->redirectToRoute('admin_listAccounts');
    }
}