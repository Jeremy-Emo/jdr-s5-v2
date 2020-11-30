<?php

namespace App\Scenario\Heroes;

use App\AbstractClass\AbstractScenario;
use App\Entity\Hero;
use App\Entity\HeroMoney;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EditHeroAdminScenario extends AbstractScenario
{
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
            /** @var Hero $hero */
            $hero = $form->getData();

            $statPoints = $form->get('addStatPoints')->getData();
            $skillPoints = $form->get('addSkillPoints')->getData();
            if (!empty($statPoints)) {
                $hero->getFighterInfos()->setStatPoints($hero->getFighterInfos()->getStatPoints() + $statPoints);
            }
            if (!empty($skillPoints)) {
                $hero->getFighterInfos()->setSkillPoints($hero->getFighterInfos()->getSkillPoints() + $skillPoints);
            }

            $money = $form->get('addMoney')->getData();
            $amount = $form->get('addMoneyAmount')->getData();
            if (!empty($money) && !empty($amount)) {
                foreach ($money as $currency) {
                    foreach ($hero->getHeroMoney() as $heroMoney) {
                        if ($heroMoney->getCurrency() === $currency) {
                            $addingMoney = $heroMoney;
                        }
                    }
                    if (!isset($addingMoney)) {
                        $addingMoney = (new HeroMoney())
                            ->setValue(0)
                            ->setHero($hero)
                            ->setCurrency($currency)
                        ;
                        $hero->addHeroMoney($addingMoney);
                    }
                    $addingMoney->setValue($addingMoney->getValue() + $amount);
                }
            }

            $this->manager->persist($hero);
            $this->manager->flush();

            return $this->redirectToRoute('admin_listHeroes');
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'edit_hero'
        ]);
    }
}