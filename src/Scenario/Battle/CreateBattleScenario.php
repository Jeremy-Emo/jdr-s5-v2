<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
use App\Entity\Party;
use App\Exception\ScenarioException;
use App\Manager\StatManager;
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

            return $this->redirectToRoute('mj_listBattles');
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'create_battle'
        ]);
    }

    /**
     * @param Battle $battle
     * @return Battle
     * @throws ScenarioException
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
            if ($dbFighter->getHero() !== null || $dbFighter->getFamiliar() !== null) {
                $fighter['ennemy'] = false;
                $fighter['isHuman'] = ($dbFighter->getHero() !== null);
            } else {
                $fighter['ennemy'] = true;
                $fighter['isHuman'] = false;
            }
            $fighter['name'] = $dbFighter->getName();
            unset($fighter);
        }
        return $fighters;
    }
}