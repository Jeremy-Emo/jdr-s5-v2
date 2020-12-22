<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\WeaponTypeType;
use App\Interfaces\ControllerInterface;
use App\Repository\WeaponTypeRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditWeaponTypeController
 * @package App\Controller\Admin\Others
 * @Route("/admin/modifier-type-arme/{id<\d+>}", name="admin_editWT")
 * @IsGranted("ROLE_ADMIN")
 */
class EditWeaponTypeController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public WeaponTypeRepository $wtRepository;

    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $wt = $this->wtRepository->find($id);
        if ($wt === null) {
            throw new NotFoundHttpException("WeaponType not found");
        }

        $form = $this->createForm(WeaponTypeType::class, $wt);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_wt', 'admin_listWeaponTypes');
    }
}