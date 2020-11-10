<?php


namespace App\Controller\Admin\Accounts;

use App\AbstractClass\AbstractController;
use App\Repository\AccountRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListAccountsController
 * @package App\Controller\Admin
 * @Route("/admin/liste-des-comptes", name="admin_listAccounts")
 * @IsGranted("ROLE_ADMIN")
 */
class ListAccountsController extends AbstractController
{
    /** @required */
    public AccountRepository $accountRepository;

    public function __invoke(): Response
    {
        $accounts = $this->accountRepository->findAll();

        return $this->render('admin/listAccounts.html.twig', [
            'accounts' => $accounts
        ]);
    }
}