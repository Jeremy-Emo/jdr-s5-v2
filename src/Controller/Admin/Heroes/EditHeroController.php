<?php

namespace App\Controller\Admin\Heroes;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\EditHeroAdminType;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use App\Scenario\Heroes\EditHeroAdminScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditHeroController
 * @package App\Controller\Admin\Heroes
 * @Route("/admin/modifier-heros/{id<\d+>}", name="admin_editHero")
 * @IsGranted("ROLE_ADMIN")
 */
class EditHeroController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public EditHeroAdminScenario $scenario;

    /** @required  */
    public HeroRepository $heroRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $hero = $this->heroRepository->find($id);

        if ($hero === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        $form = $this->createForm(EditHeroAdminType::class, $hero);
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}