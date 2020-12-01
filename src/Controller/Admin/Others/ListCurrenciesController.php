<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\CurrencyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListCurrenciesController
 * @package App\Controller\Admin\Others
 * @Route("/admin/liste-des-monnaies", name="admin_listMoney")
 * @IsGranted("ROLE_ADMIN")
 */
class ListCurrenciesController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public CurrencyRepository $currencyRepository;

    public function __invoke(): Response
    {
        $moneys = $this->currencyRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'moneys' => $moneys
        ]);
    }
}