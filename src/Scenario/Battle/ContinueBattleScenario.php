<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
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
        $activeTurn = $battle->getTurns()->last();
        //TODO : reset atb of deads
        //TODO : reset atb of current actor
        //TODO : edit form
        //TODO : if form is validated => calculate all actions, save turn and redirect
    }
}