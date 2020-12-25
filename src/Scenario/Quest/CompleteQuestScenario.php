<?php

namespace App\Scenario\Quest;

use App\AbstractClass\AbstractScenario;
use App\Entity\FighterItem;
use App\Entity\Hero;
use App\Entity\PartyItem;
use App\Entity\Quest;
use App\Entity\Reward;
use App\Exception\ScenarioException;
use App\Repository\ItemRepository;
use App\Scenario\Account\UpgradeAccountSkillScenario;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CompleteQuestScenario extends AbstractScenario
{
    /** @required  */
    public ItemRepository $itemRepository;

    /** @required  */
    public UpgradeAccountSkillScenario $accountSkillScenario;

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
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Quest $quest */
            $quest = $form->getData();
            $quest->setIsCompleted(true);

            if (!$quest->getIsFailed() && $quest->getCompletionRank() !== null) {
                $this->giveRewards($quest);
            }

            $this->manager->persist($quest);
            $this->manager->flush();

            return $this->redirectToRoute('admin_listQuests');
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'complete_quest',
        ]);
    }

    /**
     * @param Quest $quest
     * @throws ScenarioException
     */
    private function giveRewards(Quest $quest): void
    {
        $reward = null;
        foreach ($quest->getRewards() as $questReward) {
            if ($questReward->getCompletionRank() === $quest->getCompletionRank()) {
                $reward = $questReward;
            }
        }

        if ($reward !== null) {
            if ($quest->getHero() !== null) {
                $this->giveRewardsOfOnePersonExceptFixedItem($quest->getHero(), $reward);
                if ($reward->getItems()->count() > 0) {
                    foreach ($reward->getItems() as $item) {
                        $quest->getHero()->getFighterInfos()->addHeroItem(
                            (new FighterItem())->setItem($item)->setHero($quest->getHero()->getFighterInfos())
                        );
                    }
                }
            }
            if ($quest->getParty() !== null) {
                $party = $quest->getParty();
                foreach ($party->getHeroes() as $hero) {
                    $this->giveRewardsOfOnePersonExceptFixedItem($hero, $reward);
                }
                if ($reward->getItems()->count() > 0) {
                    foreach ($reward->getItems() as $item) {
                        $quest->getParty()->addPartyItem(
                            (new PartyItem())->setItem($item)->setParty($quest->getParty())
                        );
                    }
                }
            }
        }
    }

    /**
     * @param Hero $hero
     * @param Reward $reward
     * @throws ScenarioException
     */
    private function giveRewardsOfOnePersonExceptFixedItem(Hero $hero, Reward $reward): void
    {
        if ($reward->getRandomItem() !== null) {
            $item = $this->itemRepository->findRandomByRarity($reward->getRandomItem());
            if ($item === null) {
                throw new ScenarioException("Random item not found");
            }
            $hero->getFighterInfos()->addHeroItem(
                (new FighterItem())->setItem($item)->setHero($hero->getFighterInfos())
            );
        }
        if (!empty($reward->getStatPoints())) {
            $hero->getFighterInfos()->addStatPoints($reward->getStatPoints());
        }
        if (!empty($reward->getSkillPoints())) {
            $hero->getFighterInfos()->addSkillPoints($reward->getSkillPoints());
        }
        if ($reward->getSkills()->count() > 0) {
            foreach ($reward->getSkills() as $skill) {
                $this->accountSkillScenario->handle($skill, $hero->getAccount());
            }
        }
        $this->manager->persist($hero);
    }
}