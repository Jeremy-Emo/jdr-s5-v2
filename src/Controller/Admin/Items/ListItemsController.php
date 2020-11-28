<?php

namespace App\Controller\Admin\Items;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\ItemRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListItemsController
 * @package App\Controller\Admin\Items
 * @Route("/admin/liste-des-objets", name="admin_listItems")
 * @IsGranted("ROLE_ADMIN")
 */
class ListItemsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public ItemRepository $itemRepository;

    public function __invoke(): Response
    {
        $items = $this->itemRepository->findAll();

        return $this->render('admin/listItems.html.twig', [
            'items' => $items
        ]);
    }
}