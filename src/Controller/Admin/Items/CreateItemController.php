<?php

namespace App\Controller\Admin\Items;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveItemType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Item\SaveItemScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateItemController
 * @package App\Controller\Admin\Items
 * @Route("/admin/creer-nouvel-objet", name="admin_createItem")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateItemController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveItemScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SaveItemType::class);
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}