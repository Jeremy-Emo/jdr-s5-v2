<?php

namespace App\Scenario\Monster;

use App\AbstractClass\AbstractScenario;
use App\Entity\FighterInfos;
use App\Entity\FighterStat;
use App\Entity\Monster;
use App\Exception\ScenarioException;
use App\Manager\StatManager;
use App\Repository\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class SaveMonsterScenario extends AbstractScenario
{
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
     * @param string|null $title
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, ?string $title = 'create_monster'): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Monster $monster */
            $monster = $form->getData();
            $fighter = $form->get('fighterInfos')->getData();

            $monster->setFighterInfos($fighter);
            $monster = $this->generateCurrentStats($monster);

            $this->manager->persist($monster);
            $this->manager->flush();

            return $this->redirectToRoute('admin_listMonsters');
        }

        return $this->renderNewResponse('admin/createMonster.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }

    /**
     * @return Monster
     */
    public function generateBaseMonster(): Monster
    {
        $monster = (new Monster())->setIsFinished(false);
        $fighterInfos = (new FighterInfos())->setMonster($monster);

        $stats = $this->statRepository->findAll();
        foreach ($stats as $stat) {
            $fighterInfos->addStat(
                (new FighterStat())
                    ->setStat($stat)
                    ->setFighter($fighterInfos)
                    ->setValue(0)
            );
        }

        return $monster->setFighterInfos($fighterInfos);
    }

    /**
     * @param Monster $monster
     * @return Monster
     */
    private function generateCurrentStats(Monster $monster): Monster
    {
        $stats = $monster->getFighterInfos()->getStats();
        foreach ($stats as $fstat) {
            if ($fstat->getStat()->getNameId() === StatManager::STAMINA) {
                $monster->getFighterInfos()->setCurrentHP(StatManager::calculateMaxHP($fstat->getValue()));
            }
            if ($fstat->getStat()->getNameId() === StatManager::WISDOM) {
                $monster->getFighterInfos()->setCurrentMP(StatManager::calculateMaxMP($fstat->getValue()));
            }
        }
        $monster->getFighterInfos()->setCurrentSP(0);

        return $monster;
    }
}