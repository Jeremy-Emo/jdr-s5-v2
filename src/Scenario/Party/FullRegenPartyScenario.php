<?php

namespace App\Scenario\Party;

use App\AbstractClass\AbstractScenario;
use App\Entity\Party;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class FullRegenPartyScenario extends AbstractScenario
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
     * @param Party $party
     * @return Response
     * @throws \Exception
     */
    public function handle(Party $party): Response
    {
        foreach ($party->getHeroes() as $hero) {
            $fighter = $hero->getFighterInfos();
            $fighter
                ->setCurrentSP(0)
                ->setCurrentMP(explode(" / ", $fighter->getFullMP())[1])
                ->setCurrentHP(explode(" / ", $fighter->getFullHP())[1])
            ;
            $this->manager->persist($fighter);
        }
        $this->manager->flush();

        return $this->redirectToRoute('showParty', ['id' => $party->getId()]);
    }
}