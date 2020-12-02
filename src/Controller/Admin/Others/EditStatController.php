<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveStatType;
use App\Interfaces\ControllerInterface;
use App\Repository\StatRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditStatController
 * @package App\Controller\Admin\Others
 * @Route("/admin/modifier-statistique/{id<\d+>}", name="admin_editStat")
 * @IsGranted("ROLE_ADMIN")
 */
class EditStatController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required  */
    public StatRepository $statRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $stat = $this->statRepository->find($id);
        if ($stat === null) {
            throw new NotFoundHttpException("Stat not found");
        }

        $form = $this->createForm(SaveStatType::class, $stat, [
            'isEdit' => true,
        ]);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_stat', 'admin_globalOthers');
    }
}