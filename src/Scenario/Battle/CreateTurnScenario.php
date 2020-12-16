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

    private string $action = "";

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
     * @param array|null $actor
     * @return BattleTurn
     * @throws ScenarioException
     */
    public function handle(array $fighters, ?int $actualTurn = null, ?string $action = '', ?array $actor = null): BattleTurn
    {
        $fighters = $this->prepareFighters($fighters, $actor);
        $battleState = [
            'fighters' => $fighters,
            'nextActor' => $this->getNextActor($fighters),
        ];
        $battleTurn = (new BattleTurn())->setBattleState($battleState);

        if ($actualTurn === null) {
            $battleTurn->setTurnNumber(0);
            $this->action .= "Début du combat.";
        } else {
            $battleTurn->setTurnNumber($actualTurn + 1);
            $this->action .= $action;
        }
        $battleTurn->setAction($this->action);
        return $battleTurn;
    }

    /**
     * @param array $fighters
     * @param array|null $actor
     * @return array
     * @throws ScenarioException
     */
    private function prepareFighters(array &$fighters, ?array $actor = null): array
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
        $deadCounter = 0;
        while (!$breakHundred && $deadCounter !== count($fighters)) {
            foreach ($fighters as &$fighter) {
                if ($fighter['currentHP'] > 0) {
                    // Gestion debuff speed
                    $debuffSpeed = (
                        isset($fighter['statuses'])
                        && !empty($fighter['statuses']['slow'])
                    ) ? 0.5 : 1;
                    $buffSpeed = (
                        isset($fighter['statuses'])
                        && !empty($fighter['statuses']['buff_speed'])
                    ) ? 1.5 : 1;
                    // Add ATB
                    $fighter['atb'] += floor($fighter['speed'] / $totalSpeed * 100 * $debuffSpeed * $buffSpeed);
                    if ($fighter['atb'] >= 100) {
                        $breakHundred = true;
                    }
                } else {
                    $fighter['atb'] = 0;
                    $deadCounter ++;
                }
            }
        }

        //End of turn effects
        if (isset($actor)) {
            foreach ($fighters as &$fighter) {
                if ($fighter['id'] === $actor['id']) {
                    // Fatigue
                    if ($fighter['currentSP'] > $fighter['maxSP']) {
                        $fighter['currentHP'] -= ceil($fighter['maxHP'] / 5);
                        if ($fighter['currentHP'] <= 0) {
                            $fighter['currentHP'] = 0;
                            $this->action .= " | " . $fighter["name"] . " meurt de fatigue.";
                        }
                    }
                    // Poison
                    if ($this->checkStatus($fighter, 'poison')) {
                        $fighter['currentHP'] -= ceil($fighter['maxHP'] / 20);
                        if ($fighter['currentHP'] <= 0) {
                            $fighter['currentHP'] = 0;
                            $this->action .= " | " . $fighter["name"] . " meurt du poison.";
                        }
                    }
                    //Embrasement
                    if ($this->checkStatus($fighter, 'ignite')) {
                        $fighter['currentHP'] -= ceil($fighter['maxHP'] / 10);
                        if ($fighter['currentHP'] <= 0) {
                            $fighter['currentHP'] = 0;
                            $this->action .= " | " . $fighter["name"] . " meurt du feu.";
                        }
                    }
                    //HoT
                    if (
                        $this->checkStatus($fighter, 'heal_on_time')
                        && !$this->checkStatus($fighter, 'anti_heal')
                    ) {
                        $fighter['currentHP'] += ceil($fighter['maxHP'] / 10);
                        if ($fighter['currentHP'] > $fighter['maxHP']) {
                            $fighter['currentHP'] = $fighter['maxHP'];
                        }
                    }

                    // Update statuses number of turns
                    if (!empty($fighter['statuses'])) {
                        foreach ($fighter['statuses'] as $key => &$status) {
                            if ($status > 0) {
                                $status --;
                            }
                        }
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

    /**
     * @param array $fighter
     * @param string $statusName
     * @return bool
     */
    private function checkStatus(array $fighter, string $statusName): bool
    {
        return (
            isset($fighter['statuses'])
            && !empty($fighter['statuses'][$statusName])
        );
    }
}