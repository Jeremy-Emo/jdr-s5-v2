<?php

namespace App\Scenario\Familiar;

use App\AbstractClass\AbstractScenario;
use App\Entity\FighterItem;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EquipFamiliarScenario extends AbstractScenario
{
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    public function handle(FighterItem $fItem): Response
    {
        if ($fItem->getIsEquipped()) {
            $fItem->setIsEquipped(false);
        } else {
            /** @var FighterItem $existingEquippedStuff */
            foreach ($fItem->getHero()->getEquippedSlots() as $existingEquippedStuff) {
                if (
                    $existingEquippedStuff->getIsEquipped()
                    && $existingEquippedStuff->getItem()->getItemSlot() === $fItem->getItem()->getItemSlot()
                ) {
                    $existingEquippedStuff->setIsEquipped(false);
                    $this->manager->persist($existingEquippedStuff);
                }
            }
            $fItem->setIsEquipped(true);
        }

        $this->manager->persist($fItem);
        $this->manager->flush();

        return $this->redirectToRoute('stuffFamiliar', ['id' => $fItem->getHero()->getFamiliar()->getId()]);
    }
}