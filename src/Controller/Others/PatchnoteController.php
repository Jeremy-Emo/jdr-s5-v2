<?php

namespace App\Controller\Others;

use App\AbstractClass\AbstractController;
use App\Interfaces\ControllerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PatchnoteController
 * @package App\Controller\Others
 * @Route("/patchnote/{patch}", name="patchnote")
 * @IsGranted("ROLE_USER")
 */
class PatchnoteController extends AbstractController implements ControllerInterface
{
    public function __invoke(string $patch): Response
    {
        try {
            return $this->render('patchnote/' . $patch . '.html.twig');
        } catch (\Exception $e) {
            throw new NotFoundHttpException("Patchnote not found");
        }
    }
}