<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\StatRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListStatsController
 * @package App\Controller\Admin\Others
 * @Route("/admin/liste-des-statistiques", name="admin_listStats")
 * @IsGranted("ROLE_ADMIN")
 */
class ListStatsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public StatRepository $statRepository;

    public function __invoke(): Response
    {
        $stats = $this->statRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'stats' => $stats
        ]);
    }
}