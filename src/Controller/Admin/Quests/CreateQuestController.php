<?php

namespace App\Controller\Admin\Quests;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\QuestType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Quest\SaveQuestScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateQuestController
 * @package App\Controller\Admin\Quests
 * @Route("/admin/creation-de-quete", name="admin_createQuest")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateQuestController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveQuestScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(QuestType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}