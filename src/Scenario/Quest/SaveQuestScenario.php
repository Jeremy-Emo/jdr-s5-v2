<?php

namespace App\Scenario\Quest;

use App\AbstractClass\AbstractScenario;
use App\Entity\Quest;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class SaveQuestScenario extends AbstractScenario
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
     * @param string|null $title
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, ?string $title = 'create_quest'): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Quest $quest */
            $quest = $form->getData();

            $this->manager->persist($quest);
            $this->manager->flush();

            return $this->redirectToRoute('admin_listQuests');
        }

        return $this->renderNewResponse('admin/createQuest.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
            'specificJS' => 'collectionType',
        ]);
    }
}