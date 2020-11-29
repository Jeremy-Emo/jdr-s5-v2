<?php

namespace App\Scenario\Generic;

use App\AbstractClass\AbstractScenario;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class SaveFromGenericAdminFormScenario extends AbstractScenario
{
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    /**
     * @param FormInterface $form
     * @param string $title
     * @param string $redirect
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, string $title, string $redirect): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();

            $this->manager->persist($object);
            $this->manager->flush();

            return $this->redirectToRoute($redirect);
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => $title
        ]);
    }
}