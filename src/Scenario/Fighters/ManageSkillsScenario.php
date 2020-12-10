<?php

namespace App\Scenario\Fighters;

use App\AbstractClass\AbstractScenario;
use App\Entity\FighterInfos;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ManageSkillsScenario extends AbstractScenario
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
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, $title = ''): Response
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FighterInfos $fighter */
            $fighter = $form->getData();

            foreach ($fighter->getSkills() as $skill) {
                $skill->setFighter($fighter);
                $fighter->addSkill($skill);
            }
            $this->manager->persist($fighter);
            $this->manager->flush();

            return $this->redirectToRoute('admin_listMonsters');
        }

        return $this->renderNewResponse('admin/manageMonsterSkills.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
            'specificJS' => 'collectionType',
        ]);
    }
}