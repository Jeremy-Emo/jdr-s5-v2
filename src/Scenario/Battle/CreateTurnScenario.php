<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\BattleTurn;
use App\Exception\ScenarioException;
use App\Manager\StatManager;
use App\Repository\FighterInfosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateTurnScenario extends AbstractScenario
{
    /** @required  */
    public FighterInfosRepository $fighterRepository;

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
     * @throws ScenarioException
     */
    public function handle(array $fighters, ?int $actualTurn = null, ?string $action = ''): BattleTurn
    {
        $fighters = $this->prepareATB($fighters);
        $battleState = [
            'fighters' => $fighters,
            'nextActor' => $this->getNextActor($fighters),
        ];
        $battleTurn = (new BattleTurn())->setBattleState($battleState);

        if ($actualTurn === null) {
            $battleTurn->setTurnNumber(0);
            $action = "Début du combat";
        } else {
            $battleTurn->setTurnNumber($actualTurn + 1);
        }
        $battleTurn->setAction($action);
        return $battleTurn;
    }

    /**
     * @param array $fighters
     * @return array
     * @throws ScenarioException
     */
    private function prepareATB(array $fighters): array
    {
        $totalSpeed = 0;
        foreach ($fighters as &$fighter) {
            if ($fighter['currentHP'] > 0) {
                $dbFighter = $this->fighterRepository->find($fighter['id']);
                $stats = StatManager::returnMetaStats($dbFighter);
                foreach ($stats as $stat) {
                    if ($stat['name'] === 'Vitesse') {
                        $speed = $stat['value'];
                    }
                }
                if (!isset($speed)) {
                    throw new ScenarioException("Stat not found !");
                }
                $fighter['speed'] = $speed;
                $totalSpeed += $speed;
            }
        }

        $breakHundred = false;
        while (!$breakHundred) {
            foreach ($fighters as &$fighter) {
                if ($fighter['currentHP'] > 0) {
                    $fighter['atb'] += floor($fighter['speed'] / $totalSpeed * 100);
                    if ($fighter['atb'] >= 100) {
                        $breakHundred = true;
                    }
                } else {
                    $fighter['atb'] = 0;
                }
            }
            //TODO : add security for infinite loop ?
        }

        return $fighters;
    }

    /**
     * @param $fighters
     * @return array|null
     */
    private function getNextActor($fighters): array
    {
        $actor = null;
        foreach ($fighters as $fighter) {
            if ($actor === null || $fighter['atb'] >= $actor['atb']) {
                if ($actor !== null && $actor['atb'] === $fighter['atb']) {
                    $rand = [$actor, $fighter];
                    $actor = $rand[array_rand($rand)];
                } else {
                    $actor = $fighter;
                }
            }
        }

        return $actor;
    }
}