<?php

namespace App\Controller\Arcana;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\SkillTagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SkillsByTagController
 * @package App\Controller\Arcana
 * @Route("/arcana/archetype-de-classe/{id<\d+>}", name="arcana_skillTag")
 * @IsGranted("ROLE_USER")
 */
class SkillsByTagController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SkillTagRepository $repository;

    public function __invoke(int $id): Response
    {
        $skillTag = $this->repository->find($id);
        if ($skillTag === null) {
            throw new NotFoundHttpException("Skilltag not found");
        }

        return $this->render('arcana/showSkillsByTag.html.twig', [
            'skillTag' => $skillTag,
        ]);
    }
}