<?php

namespace App\Controller\Admin\Skills;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\SkillTagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListSkillTagsController
 * @package App\Controller\Admin\Skills
 * @Route("/admin/liste-des-tags", name="admin_listSkillTags")
 * @IsGranted("ROLE_ADMIN")
 */
class ListSkillTagsController extends AbstractController implements ControllerInterface
{
    /** @required */
    public SkillTagRepository $stRepository;

    public function __invoke(): Response
    {
        $tags = $this->stRepository->findAll();

        return $this->render('admin/globalOthers.html.twig', [
            'tags' => $tags
        ]);
    }
}