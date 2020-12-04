<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CurrencyType;
use App\Interfaces\ControllerInterface;
use App\Repository\CurrencyRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditCurrencyController
 * @package App\Controller\Admin\Others
 * @Route("/admin/modifier-monnaie/{id<\d+>}", name="admin_editMoney")
 * @IsGranted("ROLE_ADMIN")
 */
class EditCurrencyController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required  */
    public CurrencyRepository $currencyRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $currency = $this->currencyRepository->find($id);
        if ($currency === null) {
            throw new NotFoundHttpException("Currency not found");
        }

        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_currency', 'admin_globalOthers');
    }
}