<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\CompletionRankRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListCompletionRanksController
 * @package App\Controller\Admin\Others
 * @Route("/admin/liste-des-rangs-de-completion", name="admin_listCompletionRanks")
 * @IsGranted("ROLE_ADMIN")
 */
class ListCompletionRanksController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public CompletionRankRepository $crRepository;

    public function __invoke(): Response
    {
        $crs = $this->crRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'completionRanks' => $crs
        ]);
    }
}