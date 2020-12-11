<?php

namespace App\Controller\Admin\Quests;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\CompleteQuestType;
use App\Interfaces\ControllerInterface;
use App\Repository\QuestRepository;
use App\Scenario\Quest\CompleteQuestScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompleteQuestController
 * @package App\Controller\Admin\Quests
 * @Route("/admin/completer-quete/{id<\d+>}", name="admin_completeQuest")
 * @IsGranted("ROLE_ADMIN")
 */
class CompleteQuestController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public CompleteQuestScenario $scenario;

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

        $form = $this->createForm(CompleteQuestType::class, $quest);
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}