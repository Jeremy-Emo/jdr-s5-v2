<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
use App\Entity\BattleTurn;
use App\Entity\FighterSkill;
use App\Entity\Hero;
use App\Entity\PartyItem;
use App\Exception\ScenarioException;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EndBattleScenario extends AbstractScenario
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
     * @param Battle $battle
     * @param bool $isCancelled
     * @return Response
     * @throws ScenarioException
     */
    public function handle(Battle $battle, bool $isCancelled = false): Response
    {
        /** @var BattleTurn $activeTurn */
        $activeTurn = $battle->getTurns()->last();
        $fighters = $activeTurn->getBattleState()['fighters'];
        $party = $battle->getParty();

        //MAJ héros
        /** @var Hero $member */
        foreach ($party->getHeroes() as $member) {
            $dbFighter = $member->getFighterInfos();
            foreach ($fighters as $fighter) {
                if ((int)$fighter['id'] === $dbFighter->getId()) {
                    $dbFighter
                        ->setCurrentSP($fighter['currentSP'])
                        ->setCurrentHP($fighter['currentHP'])
                        ->setCurrentMP($fighter['currentMP'])
                        ->setCurrentShieldValue($fighter['currentShieldValue'])
                    ;
                    if ($fighter['currentHP'] <= 0) {
                        $member->setIsDead(true);
                    } else {
                        if (!empty($fighter['gainSkills'])) {
                            foreach ($fighter['gainSkills'] as $skillId) {
                                $dbSkill = $this->skillRepository->find($skillId);
                                if ($dbSkill === null) {
                                    throw new ScenarioException("Skill not found");
                                }
                                $currentSkill = false;
                                foreach ($member->getFighterInfos()->getSkills() as $fSkill) {
                                    if ($fSkill->getSkill() === $dbSkill) {
                                        $fSkill->setLevel($fSkill->getLevel() + 1);
                                        $currentSkill = true;
                                    }
                                }
                                if (!$currentSkill) {
                                    $newSkill = (new FighterSkill())
                                        ->setFighter($member->getFighterInfos())
                                        ->setSkill($dbSkill)
                                        ->setLevel(1)
                                    ;
                                    $this->manager->persist($newSkill);
                                }

                            }
                        }
                    }
                }

                //MAJ Familiers
                foreach ($member->getFamiliars() as $familiar) {
                    $familiarFighter = $familiar->getFighterInfos();
                    if ((int)$fighter['id'] === $familiarFighter->getId()) {
                        $familiarFighter
                            ->setCurrentSP($fighter['currentSP'])
                            ->setCurrentHP($fighter['currentHP'])
                            ->setCurrentMP($fighter['currentMP'])
                            ->setCurrentShieldValue($fighter['currentShieldValue'])
                        ;
                        if ($fighter['currentHP'] <= 0) {
                            $familiar->setMaster(null);
                            $familiarFighter
                                ->setCurrentSP(0)
                                ->setCurrentHP($fighter['maxHP'])
                                ->setCurrentMP($fighter['maxMP'])
                                ->setCurrentShieldValue(0)
                            ;
                        }
                    }
                    $this->manager->persist($familiar);
                }
            }
            $this->manager->persist($member);
        }


        //MAJ loot
        if (!$isCancelled) {
            foreach ($battle->getMonsters() as $monster) {
                foreach ($monster->getFighterInfos()->getHeroItems() as $item) {
                    $pItem = (new PartyItem())
                        ->setParty($battle->getParty())
                        ->setItem($item->getItem())
                        ->setDurability($item->getDurability())
                    ;
                    $party->addPartyItem($pItem);
                    $this->manager->persist($pItem);
                }
            }
        }

        $battle->setIsFinished(true);

        $this->manager->persist($battle);
        $this->manager->flush();

        return $this->redirectToRoute('mj_listBattles');
    }
}