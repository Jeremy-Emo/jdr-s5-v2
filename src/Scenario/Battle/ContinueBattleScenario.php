<?php

namespace App\Scenario\Battle;

use App\AbstractClass\AbstractScenario;
use App\Entity\Battle;
use App\Entity\BattleTurn;
use App\Exception\ScenarioException;
use App\Form\Listener\CheckSpellCastingListener;
use App\Repository\BattleStateRepository;
use App\Repository\FighterInfosRepository;
use App\Repository\FighterSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ContinueBattleScenario extends AbstractScenario
{
    /** @required  */
    public CreateTurnScenario $createTurnScenario;

    /** @required  */
    public FormFactoryInterface $formFactory;

    /** @required  */
    public FighterInfosRepository $fighterRepository;

    /** @required  */
    public FighterSkillRepository $fsRepository;

    /** @required  */
    public CalculateBattleActionScenario $calculateBattleScenario;

    /** @required  */
    public BattleStateRepository $bsRepository;

    private ?Battle $battle = null;

    public const ATTACK_WITH_WEAPON = "attack.with.weapon";
    public const DIE = "die.alone";
    public const GO_FORTH = "go.forth";
    public const CUSTOM_ACTION = "custom.action";

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    /**
     * @param Battle $battle
     * @return $this
     */
    public function setBattle(Battle $battle): self
    {
        $this->battle = $battle;

        return $this;
    }

    /**
     * @param FormInterface $form
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form): Response
    {
        if ($this->battle === null) {
            throw new ScenarioException("Battle not set.");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $targetId = $form->get('target')->getData();
            $action = $form->get('action')->getData();
            if (empty($action)) {
                $action = "";
            }

            $turn = $this->calculateBattleScenario->handle($this->battle, (string)$targetId, (string)$action);
            $this->manager->persist($turn->setBattle($this->battle));
            $this->manager->flush();

            if ($action === self::CUSTOM_ACTION) {
                return $this->redirectToRoute('mj_customActionBattle', ['id' => $turn->getId()]);
            }

            return $this->redirectToRoute('mj_continueBattle', [
                'id' => $this->battle->getId(),
            ]);
        }

        /** @var BattleTurn $activeTurn */
        $activeTurn = $this->battle->getTurns()->last();
        $actor = $activeTurn->getBattleState()['nextActor'];
        $fighters = $activeTurn->getBattleState()['fighters'];

        return $this->renderNewResponse('battle/continueBattle.html.twig', [
            'form' => $form->createView(),
            'battle' => $this->battle,
            'fighters' => $fighters,
            'actor' => $actor,
            'statuses' => $this->bsRepository->findAll(),
        ]);
    }

    /**
     * @return FormInterface
     * @throws ScenarioException
     */
    public function getForm(): FormInterface
    {
        if ($this->battle === null) {
            throw new ScenarioException("Battle not set.");
        }

        /** @var BattleTurn $activeTurn */
        $activeTurn = $this->battle->getTurns()->last();
        $actor = $activeTurn->getBattleState()['nextActor'];
        $fighters = $activeTurn->getBattleState()['fighters'];

        $fightersList = $this->getFightersAsChoiceList($fighters);
        $actionsList = $this->getActionsList($actor);

        $form = $this->formFactory->createBuilder()
            ->add('action', ChoiceType::class, [
                'choices' => $actionsList,
            ])
            ->add('target', ChoiceType::class, [
                'choices' => $fightersList,
            ])
        ;

        $form->addEventSubscriber(new CheckSpellCastingListener(
            $this->fsRepository,
            $this->fighterRepository,
            $actor
        ));

        return $form->getForm();
    }

    /**
     * @param array $fighters
     * @return array
     * @throws ScenarioException
     */
    private function getFightersAsChoiceList(array $fighters): array
    {
        $fightersChoiceList = [];
        foreach ($fighters as $fighter) {
            $dbFighter = $this->fighterRepository->find($fighter['id']);
            if ($dbFighter === null) {
                throw new ScenarioException("Fighter not found.");
            }

            if ($dbFighter->getHero() !== null) {
                $key = "Héros - " . $dbFighter->getName();
            } elseif ($dbFighter->getFamiliar() !== null) {
                $key = "Familier - " . $dbFighter->getName();
            } elseif ($dbFighter->getMonster() !== null) {
                $key = "Ennemi - " . $dbFighter->getName();
            } else {
                throw new ScenarioException("Type not found");
            }
            $fightersChoiceList[$key] = $fighter['id'];
        }
        return $fightersChoiceList;
    }

    /**
     * @param array $actor
     * @return array
     * @throws ScenarioException
     */
    private function getActionsList(array $actor): array
    {
        $actionsList = [
            'Ne rien faire' => null,
            'Mourir' => self::DIE,
            'Se rapprocher' => self::GO_FORTH,
            'Attaquer avec son arme' => self::ATTACK_WITH_WEAPON,
            'Action personnalisée' => self::CUSTOM_ACTION,
        ];

        $dbActor = $this->fighterRepository->find($actor['id']);
        if ($dbActor === null) {
            throw new ScenarioException("Actor not found.");
        }

        foreach ($dbActor->getSkills() as $fighterSkill) {
            if ($fighterSkill->getSkill()->getIsUsableInBattle()) {
                $actionsList[$fighterSkill->getSkill()->getName()] = $fighterSkill->getId();
            }
        }

        return $actionsList;
    }
}