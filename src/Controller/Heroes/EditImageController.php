<?php

namespace App\Controller\Heroes;

use App\AbstractClass\AbstractController;
use App\Exception\ScenarioException;
use App\Form\Type\EditImageType;
use App\Interfaces\ControllerInterface;
use App\Repository\HeroRepository;
use App\Scenario\Heroes\EditImageScenario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditImageController
 * @package App\Controller\Heroes
 * @Route("/heros/{id<\d+>}/changer-de-photo", name="editHeroPhoto")
 * @IsGranted("ROLE_USER")
 */
class EditImageController extends AbstractController implements ControllerInterface
{
    /** @required */
    public EditImageScenario $scenario;

    /** @required */
    public HeroRepository $heroRepository;

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ScenarioException
     */
    public function __invoke(Request $request, int $id): Response
    {
        $hero = $this->heroRepository->findOneBy([
            'account' => $this->getUser(),
            'id' => $id
        ]);

        if ($hero === null) {
            throw new NotFoundHttpException("Hero not found");
        }

        $form = $this->createForm(EditImageType::class, $hero);
        $form->handleRequest($request);

        return $this->scenario->handle($form);
    }
}