<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
use App\Entity\Party;
use App\Exception\ScenarioException;
use App\Repository\FighterInfosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateBattleScenario extends AbstractScenario
{
    /** @required  */
    public CreateTurnScenario $createTurnScenario;

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
     * @param FormInterface $form
     * @param Party $party
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, Party $party): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Battle $battle */
            $battle = $form->getData();
            $battle->setParty($party);
            $battle = $this->initializeBattle($battle);

            $this->manager->persist($battle);
            $this->manager->flush();

            //TODO : better redirect
            return $this->redirectToRoute('index');
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'create_battle'
        ]);
    }

    /**
     * @param Battle $battle
     * @return Battle
     */
    private function initializeBattle(Battle $battle): Battle
    {
        $fighters = $this->fighterRepository->findAllInBattle($battle);
        $turn = $this->createTurnScenario->handle($this->prepareFighters($fighters));
        return $battle->addTurn($turn);
    }

    /**
     * @param array $fighters
     * @return array
     */
    private function prepareFighters(array $fighters): array
    {
        foreach ($fighters as &$fighter) {
            $fighter['atb'] = 0;
        }
        return $fighters;
    }
}