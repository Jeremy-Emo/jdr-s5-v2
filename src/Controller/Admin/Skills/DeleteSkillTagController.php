<?php

namespace App\Controller\Admin\Skills;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\SkillTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteSkillTagController
 * @package App\Controller\Admin\Skills
 * @Route("/admin/supprimer-tag/{id<\d+>}", name="admin_deleteSkillTag")
 * @IsGranted("ROLE_ADMIN")
 */
class DeleteSkillTagController extends AbstractController implements ControllerInterface
{
    /** @required */
    public EntityManagerInterface $em;

    /** @required */
    public SkillTagRepository $repository;

    public function __invoke(int $id): Response
    {
        $skillTag = $this->repository->find($id);
        if ($skillTag === null) {
            throw new NotFoundHttpException("Skill Tag not found");
        }

        $this->em->remove($skillTag);
        $this->em->flush();

        return $this->redirectToRoute('admin_listSkillTags');
    }
}