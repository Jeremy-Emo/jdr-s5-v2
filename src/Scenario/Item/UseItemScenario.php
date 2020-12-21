<?php

namespace App\Scenario\Item;

use App\AbstractClass\AbstractScenario;
use App\Entity\FighterItem;
use App\Manager\StatManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class UseItemScenario extends AbstractScenario
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
     * @param FighterItem $fItem
     * @return Response
     * @throws \Exception
     */
    public function handle(FighterItem $fItem): Response
    {
        $fighter = $fItem->getHero();
        $usableEffect = $fItem->getItem()->getConsumableEffect();

        if (!empty($usableEffect->getEditHP())) {
            $maxHP = explode(" / ", StatManager::returnMetaStat(StatManager::LABEL_HP, $fighter)['value'])[1];
            $fighter->setCurrentHP($fighter->getCurrentHP() + $usableEffect->getEditHP());
            if ($fighter->getCurrentHP() > $maxHP) {
                $fighter->setCurrentHP($maxHP);
            }
            if ($fighter->getCurrentHP() < 0) {
                $fighter->setCurrentHP(0);
            }
        }
        if (!empty($usableEffect->getEditMP())) {
            $maxMP = explode(" / ", StatManager::returnMetaStat(StatManager::LABEL_MP, $fighter)['value'])[1];
            $fighter->setCurrentMP($fighter->getCurrentMP() + $usableEffect->getEditMP());
            if ($fighter->getCurrentMP() > $maxMP) {
                $fighter->setCurrentMP($maxMP);
            }
            if ($fighter->getCurrentMP() < 0) {
                $fighter->setCurrentMP(0);
            }
        }
        if (!empty($usableEffect->getEditSP())) {
            $fighter->setCurrentSP($fighter->getCurrentSP() + $usableEffect->getEditSP());
        }
        if (!empty($usableEffect->getEditStatPoints())) {
            $fighter->setStatPoints($fighter->getStatPoints() + $usableEffect->getEditStatPoints());
        }
        if (!empty($usableEffect->getEditSkillPoints())) {
            $fighter->setSkillPoints($fighter->getSkillPoints() + $usableEffect->getEditSkillPoints());
        }

        $this->manager->persist($fighter);
        $this->manager->remove($fItem);
        $this->manager->flush();

        return $this->redirectToRoute('heroInventory', [
            'id' => $fighter->getHero()->getId(),
        ]);
    }
}