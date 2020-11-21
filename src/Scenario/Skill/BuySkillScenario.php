<?php

namespace App\Scenario\Skill;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\AccountSkills;
use App\Entity\FighterSkill;
use App\Repository\AccountSkillsRepository;
use App\Repository\FighterSkillRepository;
use App\Repository\HeroRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class BuySkillScenario extends AbstractScenario
{
    /** @required */
    public HeroRepository $heroRepository;

    /** @required */
    public SkillRepository $skillRepository;

    /** @required */
    public FighterSkillRepository $fsRepository;

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

    public function handle(int $heroId, int $skillId, Account $account): Response
    {
        $hero = $this->heroRepository->findOneBy([
            'account' => $account,
            'id' => $heroId
        ]);
        $skill = $this->skillRepository->find($skillId);

        if ($skill === null || $hero === null ) {
            return (new JsonResponse([
                'success' => false,
                'message' => "Données envoyées incorrectes"
            ], 404))->setStatusCode(404);
        }

        //Check existing skill
        $existingSkill = $this->fsRepository->findOneBy([
            'skill' => $skill,
            'fighter' => $hero->getFighterInfos()
        ]);

        if ($existingSkill === null) {
            $existingSkill = (new FighterSkill())
                ->setSkill($skill)
                ->setLevel(1)
                ->setFighter($hero->getFighterInfos())
            ;
        } else {
            $existingSkill->setLevel($existingSkill->getLevel() + 1);
            //AccountSkill check
            if ($existingSkill->getLevel() % 10 === 0) {
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
            }
        }
        $this->manager->persist($existingSkill);

        $hero->getFighterInfos()->setSkillPoints(
            $hero->getFighterInfos()->getSkillPoints() - $skill->getCost()
        );
        $this->manager->persist($hero);

        $this->manager->flush();

        return new JsonResponse([
            'success' => true
        ]);
    }
}