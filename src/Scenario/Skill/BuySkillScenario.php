<?php

namespace App\Scenario\Skill;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\FighterInfos;
use App\Entity\FighterSkill;
use App\Entity\Skill;
use App\Repository\FighterInfosRepository;
use App\Repository\FighterSkillRepository;
use App\Repository\HeroRepository;
use App\Repository\SkillRepository;
use App\Scenario\Account\UpgradeAccountSkillScenario;
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
    public FighterInfosRepository $fighterRepository;

    /** @required */
    public FighterSkillRepository $fsRepository;

    /** @required */
    public UpgradeAccountSkillScenario $upgradeASScenario;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    public function handle(int $heroId, ?int $skillId, Account $account, bool $isRandom = false): Response
    {
        if (
            !empty(
                array_intersect(
                    ['ROLE_MJ', 'ROLE_ADMIN'],
                    $account->getRoles()
                )
            )
        ) {
            $hero = $this->heroRepository->find($heroId);
        } else {
            $hero = $this->heroRepository->findOneBy([
                'account' => $account,
                'id' => $heroId,
            ]);
        }

        if ($isRandom && $hero !== null) {
            $skill = $this->pickRandomSkill($hero->getFighterInfos());
        } else {
            $skill = $this->skillRepository->find($skillId);
        }

        if ($skill === null || $hero === null) {
            return (new JsonResponse([
                'success' => false,
                'message' => "Données envoyées incorrectes"
            ], 404))->setStatusCode(404);
        }

        if ($skill->getCost() > $hero->getFighterInfos()->getSkillPoints()) {
            return (new JsonResponse([
                'success' => false,
                'message' => "Nombre de points insuffisant"
            ], 400))->setStatusCode(400);
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
                //Attention => persist & flush !
                $this->upgradeASScenario->handle($skill, $account);
            }
        }
        $this->manager->persist($existingSkill);

        $cost = $isRandom ? ceil($skill->getCost() / 2) : $skill->getCost();
        $hero->getFighterInfos()->setSkillPoints(
            $hero->getFighterInfos()->getSkillPoints() - $cost
        );
        $this->manager->persist($hero);

        $this->manager->flush();

        if ($isRandom) {
            return new JsonResponse([
                'success' => true,
                'data' => [
                    'id' => $skill->getId(),
                    'cost' => $cost
                ]
            ]);
        }
        return new JsonResponse([
            'success' => true
        ]);
    }

    private function pickRandomSkill(FighterInfos $fighter): ?Skill
    {
        $availableSkills = $this->fighterRepository->findAllSkillsAvailable($fighter);
        $skills = [];
        /** @var Skill $potentialSkill */
        foreach ($availableSkills as $potentialSkill) {
            if ($potentialSkill->getCost() <= $fighter->getStatPoints()) {
                $skills[] = $potentialSkill;
            }
        }

        return (count($skills) > 0) ? $skills[array_rand($skills)] : null;
    }
}