<?php

namespace App\Controller\Admin\Items;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CreateItemType;
use App\Interfaces\ControllerInterface;
use App\Repository\ItemRepository;
use App\Scenario\Item\CreateItemScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditItemController
 * @package App\Controller\Admin\Items
 * @Route("/admin/modifier-objet/{id<\d+>}", name="admin_editItem")
 * @IsGranted("ROLE_ADMIN")
 */
class EditItemController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public ItemRepository $itemRepository;

    /** @required  */
    public CreateItemScenario $scenario;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $item = $this->itemRepository->find($id);
        if ($item === null) {
            throw new NotFoundHttpException("Item not found");
        }

        $form = $this->createForm(CreateItemType::class, $item, [
            'isEdit' => true
        ]);
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}