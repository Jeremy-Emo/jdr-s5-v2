<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\BattleTurn;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateCustomTurnScenario extends AbstractScenario
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
     * @param BattleTurn $bt
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, BattleTurn $bt): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $bt = $form->getData();

            $this->manager->persist($bt);
            $this->manager->flush();

            return $this->redirectToRoute('mj_continueBattle', [
                'id' => $bt->getBattle()->getId(),
            ]);
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'coucou'
        ]);
    }
}