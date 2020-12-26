<?php

namespace App\Scenario\Skill;

use App\AbstractClass\AbstractScenario;
use App\Exception\ScenarioException;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ReleaseSkillsScenario extends AbstractScenario
{
    /** @required  */
    public SkillRepository $skillRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    /**
     * @return Response
     * @throws ScenarioException
     */
    public function handle(): Response
    {
        $skillsToRelease = $this->skillRepository->findBy([
            'isNotReleased' => true,
        ]);

        foreach ($skillsToRelease as $skill) {
            $skill->setIsNotReleased(false);
            $this->manager->persist($skill);
        }
        $this->manager->flush();

        return $this->renderNewResponse('admin/releaseSkills.html.twig', [
            'skills' => $skillsToRelease,
        ]);
    }
}