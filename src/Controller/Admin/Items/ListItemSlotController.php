<?php

namespace App\Controller\Admin\Items;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\ItemSlotRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListItemSlotController
 * @package App\Controller\Admin\Items
 * @Route("/admin/liste-des-slots-equipement", name="admin_listItemSlots")
 * @IsGranted("ROLE_ADMIN")
 */
class ListItemSlotController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public ItemSlotRepository $itemSlotRepository;

    public function __invoke(): Response
    {
        $itemSlots = $this->itemSlotRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'itemSlots' => $itemSlots
        ]);
    }
}