<?php

namespace App\Controller\Admin\Monsters;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\SaveMonsterType;
use App\Interfaces\ControllerInterface;
use App\Scenario\Monster\CreateMonsterScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateMonsterController
 * @package App\Controller\Admin\Monsters
 * @Route("/admin/creer-nouveau-monstre", name="admin_createMonster")
 * @IsGranted("ROLE_ADMIN")
 */
class CreateMonsterController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public CreateMonsterScenario $scenario;

    /**
     * @param Request $request
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SaveMonsterType::class, $this->scenario->generateBaseMonster());
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}