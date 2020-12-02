<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\StatBonusPercentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListSBPController
 * @package App\Controller\Admin\Others
 * @Route("/admin/liste-des-bonus-de-stats", name="admin_listSBP")
 * @IsGranted("ROLE_ADMIN")
 */
class ListSBPController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public StatBonusPercentRepository $sbpRepository;

    public function __invoke(): Response
    {
        $sbps = $this->sbpRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'statBonusPercents' => $sbps
        ]);
    }
}