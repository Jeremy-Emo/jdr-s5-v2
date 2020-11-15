<?php

namespace App\Scenario\Skill;

use App\AbstractClass\AbstractScenario;
use App\Entity\FightingSkillInfo;
use App\Entity\Skill;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateSkillScenario extends AbstractScenario
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
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Skill $skill */
            $skill = $form->getData();

            /** @var FightingSkillInfo|null $fightingSkill */
            $fightingSkill = $form->get('fightingSkillInfo')->getData();

            if ($skill->getIsUsableInBattle() && $fightingSkill !== null) {
                $fightingSkill->setSkill($skill);
                $skill->setFightingSkillInfo($fightingSkill);
            }

            $this->manager->persist($skill);
            $this->manager->flush();

            return $this->redirectToRoute('admin_listSkills');
        }

        return $this->renderNewResponse('admin/createSkill.html.twig', [
            'form' => $form->createView(),
            'title' => 'create_skill',
            'specificJS' => 'collectionType'
        ]);
    }
}