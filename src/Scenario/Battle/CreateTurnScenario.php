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
        $fighters = $this->prepareFighters($fighters);
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
    private function prepareFighters(array $fighters): array
    {
        $totalSpeed = 0;
        foreach ($fighters as &$fighter) {
            $dbFighter = $this->fighterRepository->find($fighter['id']);
            $stats = StatManager::returnMetaStats($dbFighter);
            foreach ($stats as $stat) {
                if ($stat['name'] === StatManager::LABEL_SPEED) {
                    $speed = $stat['value'];
                    $fighter['speed'] = $speed;
                }
                if ($stat['name'] === StatManager::LABEL_HP) {
                    $fighter['maxHP'] = explode(" / ", $stat['value'])[1];
                }
                if ($stat['name'] === StatManager::LABEL_MP) {
                    $fighter['maxMP'] = explode(" / ", $stat['value'])[1];
                }
                if ($stat['name'] === StatManager::LABEL_SP) {
                    $fighter['maxSP'] = explode(" / ", $stat['value'])[1];
                }
            }
            if ($fighter['currentHP'] > 0) {
                if (!isset($speed)) {
                    throw new ScenarioException("Stat not found !");
                }
                $totalSpeed += $speed;
            }
            if ($dbFighter->getHero() !== null) {
                $fighter['ennemy'] = false;
                $fighter['name'] = $dbFighter->getHero()->getName();
            } else {
                $fighter['ennemy'] = true;
                $fighter['name'] = $dbFighter->getMonster()->getName();
            }
        }

        //Gestion ATB
        $breakHundred = false;
        while (!$breakHundred) {
            foreach ($fighters as &$fighter) {
                if ($fighter['currentHP'] > 0) {
                    // Gestion debuff speed
                    $reducSpeed = (
                        isset($fighter['statuses'])
                        && !empty($fighter['statuses']['slow'])
                    ) ? 2 : 1;
                    // Add ATB
                    $fighter['atb'] += floor($fighter['speed'] / $totalSpeed * 100 / $reducSpeed);
                    if ($fighter['atb'] >= 100) {
                        $breakHundred = true;
                    }
                } else {
                    $fighter['atb'] = 0;
                }
            }
            //TODO : add security for infinite loop ?
        }

        //Gestion statuts
        foreach ($fighters as &$fighter) {
            if (isset($fighter['statuses'])) {
                foreach ($fighter['statutes'] as &$status) {
                    if ($status > 0) {
                        $status -= 1;
                    }
                }
            }
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