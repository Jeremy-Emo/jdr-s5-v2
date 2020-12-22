<?php

namespace App\Controller\Admin\Familiars;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveFamiliarType;
use App\Interfaces\ControllerInterface;
use App\Repository\FamiliarRepository;
use App\Scenario\Familiar\SaveFamiliarScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditMonsterController
 * @package App\Controller\Admin\Monsters
 * @Route("/admin/modifier-familier/{id<\d+>}", name="admin_editFamiliar")
 * @IsGranted("ROLE_ADMIN")
 */
class EditFamiliarController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFamiliarScenario $scenario;

    /** @required  */
    public FamiliarRepository $familiarRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $familiar = $this->familiarRepository->find($id);
        if ($familiar === null) {
            throw new NotFoundHttpException("Familiar not found");
        }

        $form = $this->createForm(SaveFamiliarType::class, $familiar);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_familiar');
    }
}