<?php

namespace App\Scenario\Familiar;

use App\AbstractClass\AbstractScenario;
use App\Entity\Familiar;
use App\Entity\FighterItem;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class GiveItemToFamiliarScenario extends AbstractScenario
{
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    public function handle(Familiar $familiar, FighterItem $fighterItem, ?bool $toFamiliar = true): Response
    {
        if ($toFamiliar) {
            $fighterItem->setHero($familiar->getFighterInfos());

            $this->persistItem($fighterItem);
            return $this->redirectToRoute('heroInventory', ['id' => $familiar->getMaster()->getId()]);
        } else {
            $fighterItem->setHero($familiar->getMaster()->getFighterInfos());

            $this->persistItem($fighterItem);
            return $this->redirectToRoute('stuffFamiliar', ['id' => $familiar->getId()]);
        }
    }

    private function persistItem(FighterItem $fItem): void
    {
        $fItem->setIsEquipped(false);

        $this->manager->persist($fItem);
        $this->manager->flush();
    }
}