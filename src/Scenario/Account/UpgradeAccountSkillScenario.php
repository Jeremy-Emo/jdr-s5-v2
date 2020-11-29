<?php

namespace App\Scenario\Account;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\AccountSkills;
use App\Entity\Skill;
use App\Repository\AccountSkillsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class UpgradeAccountSkillScenario extends AbstractScenario
{
    /** @required */
    public AccountSkillsRepository $asRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    public function handle(Skill $skill, Account $account): void
    {
        $accountSkill = $this->asRepository->findOneBy([
            'skill' => $skill,
            'account' => $account
        ]);
        if ($accountSkill === null) {
            $accountSkill = (new AccountSkills())
                ->setSkill($skill)
                ->setLevel(1)
                ->setAccount($account)
            ;
        } else {
            $accountSkill->setLevel($accountSkill->getLevel() + 1);
        }
        $this->manager->persist($accountSkill);
        $this->manager->flush();
    }
}