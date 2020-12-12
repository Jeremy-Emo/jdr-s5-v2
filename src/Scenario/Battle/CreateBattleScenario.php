<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
use App\Entity\Party;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateBattleScenario extends AbstractScenario
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
     * @param Party $party
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, Party $party): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Battle $battle */
            $battle = $form->getData();
            $battle->setParty($party);

            $this->manager->persist($battle);
            $this->manager->flush();

            //TODO : better redirect
            return $this->redirectToRoute('index');
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'create_battle'
        ]);
    }
}