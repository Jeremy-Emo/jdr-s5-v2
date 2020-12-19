<?php

namespace App\Controller\Admin\Items;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveItemSlotType;
use App\Interfaces\ControllerInterface;
use App\Repository\ItemSlotRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditItemSlotController
 * @package App\Controller\Admin\Items
 * @Route("/admin/modifier-slot-objet/{id<\d+>}", name="admin_editItemSlot")
 * @IsGranted("ROLE_ADMIN")
 */
class EditItemSlotController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public ItemSlotRepository $itemSlotRepository;

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
        $itemSlot = $this->itemSlotRepository->find($id);
        if ($itemSlot === null) {
            throw new NotFoundHttpException("ItemSlot not found");
        }

        $form = $this->createForm(SaveItemSlotType::class, $itemSlot);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_itemSlot', 'admin_listItemSlots');
    }
}