<?php

namespace App\Controller\Admin\Items;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveItemSlotType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateItemSlotController
 * @package App\Controller\Admin\Items
 * @Route("/admin/creer-nouveau-slot-objet", name="admin_createItemSlot")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateItemSlotController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SaveItemSlotType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'create_itemSlot', 'admin_listItemSlots');
    }
}