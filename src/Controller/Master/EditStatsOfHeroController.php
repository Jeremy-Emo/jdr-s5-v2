<?php

namespace App\Controller\Master;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\EditStatsOfHeroType;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use App\Scenario\Generic\SaveFromGenericAdminFormScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditStatsOfHeroController
 * @package App\Controller\Master
 * @Route("/mj/modifier-etat-héros/{id<\d+>}", name="editHeroMJStats")
 * @IsGranted("ROLE_MJ")
 */
class EditStatsOfHeroController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SaveFromGenericAdminFormScenario $scenario;

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

        $form = $this->createForm(EditStatsOfHeroType::class, $hero->getFighterInfos());
        $form->handleRequest($request);

        return $this->scenario->handle($form, 'edit_hero_mj', 'listParties');
    }
}