<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
use App\Entity\BattleTurn;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ContinueBattleScenario extends AbstractScenario
{
    /** @required  */
    public CreateTurnScenario $createTurnScenario;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    public function handle(FormInterface $form, Battle $battle): Response
    {
        /** @var BattleTurn $activeTurn */
        $activeTurn = $battle->getTurns()->last();
        $fighters = $activeTurn->getBattleState();

        //Get current fighter which do something
        $actor = null;
        foreach ($fighters as $fighter) {
            if ($actor === null || $fighter['atb'] >= $actor['atb']) {
                if ($actor['atb'] === $fighter['atb']) {
                    $rand = [$actor, $fighter];
                    $actor = $rand[array_rand($rand)];
                } else {
                    $actor = $fighter;
                }
            }
        }

        //TODO : edit form
        //TODO : if form is validated => calculate all actions, reset atb of current actor, save turn and redirect
    }
}