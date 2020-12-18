<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
use App\Entity\BattleTurn;
use App\Entity\Hero;
use App\Entity\PartyItem;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EndBattleScenario extends AbstractScenario
{
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    public function handle(Battle $battle): Response
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
                    }
                }
            }
            $this->manager->persist($member);
        }

        //MAJ loot
        foreach ($battle->getMonsters() as $monster) {
            foreach ($monster->getFighterInfos()->getHeroItems() as $item) {
                $pItem = (new PartyItem())
                    ->setParty($battle->getParty())
                    ->setItem($item->getItem())
                    ->setDurability($item->getDurability())
                ;
                $party->addPartyItem($pItem);
                $this->manager->persist($pItem);
                $this->manager->remove($item);
            }
        }

        $battle->setIsFinished(true);

        $this->manager->persist($battle);
        $this->manager->flush();

        return $this->redirectToRoute('mj_listBattles');
    }
}