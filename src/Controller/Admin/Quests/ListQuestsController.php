<?php

namespace App\Controller\Admin\Quests;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\QuestRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListQuestsController
 * @package App\Controller\Admin\Quests
 * @Route("/admin/liste-des-quetes", name="admin_listQuests")
 * @IsGranted("ROLE_ADMIN")
 */
class ListQuestsController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public QuestRepository $questRepository;

    public function __invoke(): Response
    {
        $quests = $this->questRepository->findAll();

        return $this->render('admin/listQuests.html.twig', [
            'quests' => $quests,
        ]);
    }
}