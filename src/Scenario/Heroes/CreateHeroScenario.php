<?php

namespace App\Scenario\Heroes;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\FighterInfos;
use App\Entity\FighterItem;
use App\Entity\FighterSkill;
use App\Entity\FighterStat;
use App\Entity\Hero;
use App\Exception\ScenarioException;
use App\Manager\StatManager;
use App\Repository\ElementRepository;
use App\Repository\ItemRepository;
use App\Repository\ItemSlotRepository;
use App\Repository\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateHeroScenario extends AbstractScenario
{
    public const DEFAULT_STAT_POINTS = 10;

    //must not be >= 10 to prevent infinite stacking with accountSkills
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
    public const DEFAULT_FURTIVE = 10;
    public const DEFAULT_LEADERSHIP = 0;

    public const RARITY_BEGINNER_STUFF = 'Commun';

    public const GAIN_STAT_BY_SKILL = 'transcend_stat';

    /** @required */
    public StatRepository $statRepository;

    /** @required */
    public ElementRepository $elementRepository;

    /** @required */
    public ItemSlotRepository $itemSlotRepository;

    /** @required */
    public ItemRepository $itemRepository;

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

            $fighter = $this->getFighter($user, $hero);
            $hero = $this->generateAffinity($hero);

            $this->manager->persist(
                $hero
                    ->setAccount($user)
                    ->setIsDead(false)
                    ->setFighterInfos($fighter)
            );
            $this->manager->flush();

            return $this->redirectToRoute('listHeroes');
        }

        return $this->renderNewResponse('heroes/save.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Account $account
     * @param Hero $hero
     * @return FighterInfos
     */
    private function getFighter(Account $account, Hero $hero): FighterInfos
    {
        $fighter = (new FighterInfos())
            ->setSkillPoints(self::DEFAULT_SKILL_POINTS)
            ->setStatPoints($this->calculateStatPoints($account))
            ->setHero($hero)
            ->setCurrentSP(0)
            ->setCurrentHP(StatManager::calculateMaxHP(self::DEFAULT_STAMINA))
            ->setCurrentMP(StatManager::calculateMaxMP(self::DEFAULT_WISDOM))
        ;
        $fighter = $this->addDefaultStats($fighter);
        $fighter = $this->addDefaultSkills($fighter, $account);
        $fighter = $this->generateDefaultStuff($fighter);
        return $fighter;
    }

    private function calculateStatPoints(Account $account): int
    {
        $bonus = 0;
        foreach ($account->getAccountSkills() as $skill) {
            if (
                $skill->getSkill()->getFightingSkillInfo() !== null
                && $skill->getSkill()->getFightingSkillInfo()->getCustomEffects() !== null
                && $skill->getSkill()->getFightingSkillInfo()->getCustomEffects()->getNameId() === self::GAIN_STAT_BY_SKILL
            ) {
                $bonus += $skill->getLevel();
            }
        }
        return $bonus + self::DEFAULT_STAT_POINTS;
    }

    /**
     * @param FighterInfos $fighter
     * @return FighterInfos
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
                case StatManager::FURTIVE:
                    $newStat->setValue(self::DEFAULT_FURTIVE);
                    break;
                case StatManager::LEADERSHIP:
                    $newStat->setValue(self::DEFAULT_LEADERSHIP);
                    break;
                default:
                    $this->logger->error("Stat not found : " . $stat->getNameId(), [
                        'method' => __METHOD__,
                    ]);
                    $newStat->setValue(0);
            }

            $fighter->addStat($newStat);
        }
        return $fighter;
    }

    /**
     * @param FighterInfos $fighter
     * @param Account $account
     * @return FighterInfos
     */
    private function addDefaultSkills(FighterInfos $fighter, Account $account): FighterInfos
    {
        foreach ($account->getAccountSkills() as $accountSkill) {
            $fighter->addSkill(
                (new FighterSkill())
                    ->setSkill($accountSkill->getSkill())
                    ->setLevel($accountSkill->getLevel())
                    ->setFighter($fighter)
            );
        }
        return $fighter;
    }

    /**
     * @param Hero $hero
     * @return Hero
     */
    private function generateAffinity(Hero $hero): Hero
    {
        return $hero->setElementAffinity(
            $this->elementRepository->findOneByRandom(rand(1, 100))
        );
    }

    /**
     * @param FighterInfos $fighter
     * @return FighterInfos
     */
    private function generateDefaultStuff(FighterInfos $fighter): FighterInfos
    {
        $slots = $this->itemSlotRepository->findBy([
            'isForFamiliar' => false,
        ]);
        foreach ($slots as $slot) {
            $item = $this->itemRepository->findRandomByItemSlotAndRarity($slot, self::RARITY_BEGINNER_STUFF);
            if ($item !== null) {
                $fighter->addHeroItem(
                    (new FighterItem())
                        ->setHero($fighter)
                        ->setIsEquipped(true)
                        ->setItem($item)
                );
            }
        }

        $weapon = $this->itemRepository->findRandomWeaponByRarity(self::RARITY_BEGINNER_STUFF);
        if ($weapon !== null) {
            $fighter->addHeroItem(
                (new FighterItem())
                    ->setHero($fighter)
                    ->setIsEquipped(true)
                    ->setItem($weapon)
            );
        }

        return $fighter;
    }
}