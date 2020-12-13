<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\BattleTurn;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateTurnScenario extends AbstractScenario
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
     * Convertir chaque fighter en array
     * @param array $fighters
     * @param int|null $actualTurn
     * @param string|null $action
     * @return BattleTurn
     */
    public function handle(array $fighters, ?int $actualTurn = null, ?string $action = ''): BattleTurn
    {
        $battleTurn = (new BattleTurn())->setBattleState($fighters);
        if ($actualTurn === null) {
            $battleTurn->setTurnNumber(0);
            $action = "Début du combat";
        } else {
            $battleTurn->setTurnNumber($actualTurn + 1);
        }
        $battleTurn->setAction($action);
        return $battleTurn;
    }
}