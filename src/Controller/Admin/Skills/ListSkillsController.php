<?php

namespace App\Controller\Admin\Skills;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use App\Repository\SkillRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListSkillsController
 * @package App\Controller\Admin\Skills
 * @Route("/admin/liste-des-competences", name="admin_listSkills")
 * @IsGranted("ROLE_ADMIN")
 */
class ListSkillsController extends AbstractController implements ControllerInterface
{
    /** @required */
    public SkillRepository $skillRepository;

    public function __invoke(): Response
    {
        $skills = $this->skillRepository->findAll();

        return $this->render('admin/listSkills.html.twig', [
            'skills' => $skills
        ]);
    }
}