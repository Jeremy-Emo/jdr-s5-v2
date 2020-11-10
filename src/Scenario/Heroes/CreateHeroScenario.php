<?php

namespace App\Scenario\Heroes;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\FighterInfos;
use App\Entity\FighterStat;
use App\Entity\Hero;
use App\Exception\ScenarioException;
use App\Manager\StatManager;
use App\Repository\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateHeroScenario extends AbstractScenario
{
    public const DEFAULT_STAT_POINTS = 0;
    public const DEFAULT_SKILL_POINTS = 0;

    public const DEFAULT_STRENGTH = 10;
    public const DEFAULT_STAMINA = 10;
    public const DEFAULT_INTELLIGENCE = 10;
    public const DEFAULT_WISDOM = 10;
    public const DEFAULT_AGILITY = 10;
    public const DEFAULT_CRITICAL_RATE = 30;
    public const DEFAULT_PERCEPTION = 10;
    public const DEFAULT_CHARISMA = 10;
    public const DEFAULT_RESISTANCE = 30;

    /** @required  */
    public StatRepository $statRepository;


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
     * @param Account $user
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, Account $user): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Hero $hero */
            $hero = $form->getData();
            if ($user->getCurrentHero() === null) {
                $hero->setIsCurrent(true);
            }

            $fighter = (new FighterInfos())
                ->setSkillPoints(self::DEFAULT_SKILL_POINTS)
                ->setStatPoints(self::DEFAULT_STAT_POINTS)
                ->setHero($hero)
            ;
            $fighter = $this->addDefaultStats($fighter);

            $this->manager->persist(
                $hero
                    ->setAccount($user)
                    ->setFighterInfos($fighter)
            );
            $this->manager->flush();

            return $this->redirectToRoute('listHeroes');
        }

        return $this->renderNewResponse('heroes/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param FighterInfos $fighter
     * @return FighterInfos
     * @throws ScenarioException
     */
    private function addDefaultStats(FighterInfos $fighter): FighterInfos
    {
        $stats = $this->statRepository->findAll();
        foreach ($stats as $stat) {
            $newStat = (new FighterStat())->setStat($stat);
            switch ($stat->getNameId()) {
                case StatManager::STRENGTH:
                    $newStat->setValue(self::DEFAULT_STRENGTH);
                    break;
                case StatManager::STAMINA:
                    $newStat->setValue(self::DEFAULT_STAMINA);
                    break;
                case StatManager::INTELLIGENCE:
                    $newStat->setValue(self::DEFAULT_INTELLIGENCE);
                    break;
                case StatManager::WISDOM:
                    $newStat->setValue(self::DEFAULT_WISDOM);
                    break;
                case StatManager::AGILITY:
                    $newStat->setValue(self::DEFAULT_AGILITY);
                    break;
                case StatManager::CRITICAL_RATE:
                    $newStat->setValue(self::DEFAULT_CRITICAL_RATE);
                    break;
                case StatManager::PERCEPTION:
                    $newStat->setValue(self::DEFAULT_PERCEPTION);
                    break;
                case StatManager::CHARISMA:
                    $newStat->setValue(self::DEFAULT_CHARISMA);
                    break;
                case StatManager::RESISTANCE:
                    $newStat->setValue(self::DEFAULT_RESISTANCE);
                    break;
                default:
                    $this->logger->error("Stat not found", [
                        'method' => __METHOD__,
                    ]);
                    throw new ScenarioException("Stat not found");
            }

            $fighter->addStat($newStat);
        }
        return $fighter;
    }
}