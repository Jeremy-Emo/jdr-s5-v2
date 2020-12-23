<?php

namespace App\Controller\Admin\Familiars;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\GiveFamiliarType;
use App\Interfaces\ControllerInterface;
use App\Repository\FamiliarRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GiveFamiliarController
 * @package App\Controller\Admin\Familiars
 * @Route("/admin/donner-familier/{id<\d+>}", name="admin_giveFamiliar")
 * @IsGranted("ROLE_ADMIN")
 */
class GiveFamiliarController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public FamiliarRepository $familiarRepository;

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
        $familiar = $this->familiarRepository->find($id);
        if ($familiar === null) {
            throw new NotFoundHttpException("Familiar not found");
        }

        $form = $this->createForm(GiveFamiliarType::class, $familiar);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'give_familiar', 'admin_listFamiliars');
    }
}