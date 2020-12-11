<?php

namespace App\Controller\Admin\Quests;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\GiveQuestType;
use App\Interfaces\ControllerInterface;
use App\Repository\QuestRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GiveQuestController
 * @package App\Controller\Admin\Quests
 * @Route("/admin/donner-quete/{id<\d+>}", name="admin_giveQuest")
 * @IsGranted("ROLE_ADMIN")
 */
class GiveQuestController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required  */
    public QuestRepository $questRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $quest = $this->questRepository->find($id);
        if ($quest === null) {
            throw new NotFoundHttpException("Quest not found");
        }

        $form = $this->createForm(GiveQuestType::class, $quest);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'give_quest', 'admin_listQuests');
    }
}