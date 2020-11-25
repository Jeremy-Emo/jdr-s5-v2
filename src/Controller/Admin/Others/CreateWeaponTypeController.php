<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\WeaponTypeType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Generic\CreateFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateWeaponTypeController
 * @package App\Controller\Admin\Others
 * @Route("/admin/nouveau-type-arme", name="admin_createWT")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateWeaponTypeController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public CreateFromGenericAdminFormScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(WeaponTypeType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_weaponType', 'index');
    }
}