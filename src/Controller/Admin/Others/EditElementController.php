<?php

namespace App\Controller\Admin\Others;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveElementType;
use App\Interfaces\ControllerInterface;
use App\Repository\ElementRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditElementController
 * @package App\Controller\Admin\Others
 * @Route("/admin/modifier-element/{id<\d+>}", name="admin_editElement")
 * @IsGranted("ROLE_ADMIN")
 */
class EditElementController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

    /** @required  */
    public ElementRepository $elementRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $elem = $this->elementRepository->find($id);
        if ($elem === null) {
            throw new NotFoundHttpException("Element not found");
        }

        $form = $this->createForm(SaveElementType::class, $elem);
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_element', 'admin_globalOthers');
    }
}