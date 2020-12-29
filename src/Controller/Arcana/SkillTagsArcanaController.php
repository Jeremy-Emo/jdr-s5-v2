<?php

namespace App\Controller\Arcana;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\SkillTagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SkillTagsArcanaController
 * @package App\Controller\Arcana
 * @Route("/arcana/archetypes-de-classe", name="arcana_listSkillTags")
 * @IsGranted("ROLE_USER")
 */
class SkillTagsArcanaController extends AbstractController implements ControllerInterface
{
    /** @required  */
    public SkillTagRepository $repository;

    public function __invoke(): Response
    {
        $skillTags = $this->repository->findBy([], [
            'name' => 'ASC',
        ]);

        return $this->render('arcana/skillTags.html.twig', [
            'skillTags' => $skillTags,
        ]);
    }
}