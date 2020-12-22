<?php

namespace App\Scenario\Familiar;

use App\AbstractClass\AbstractScenario;
use App\Entity\Familiar;
use App\Entity\FighterInfos;
use App\Entity\FighterStat;
use App\Exception\ScenarioException;
use App\Manager\StatManager;
use App\Repository\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class SaveFamiliarScenario extends AbstractScenario
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
    public function handle(FormInterface $form, ?string $title = 'create_familiar'): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Familiar $familiar */
            $familiar = $form->getData();
            $fighter = $form->get('fighterInfos')->getData();

            $familiar->setFighterInfos($fighter);
            $familiar = $this->generateCurrentStats($familiar);

            $this->manager->persist($familiar);
            $this->manager->flush();

            return $this->redirectToRoute('admin_listFamiliars');
        }

        return $this->renderNewResponse('admin/createMonster.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }

    /**
     * @return Familiar
     */
    public function generateBaseFamiliar(): Familiar
    {
        $familiar = (new Familiar())->setIsInvoked(false);
        $fighterInfos = (new FighterInfos())->setFamiliar($familiar);

        $stats = $this->statRepository->findAll();
        foreach ($stats as $stat) {
            $fighterInfos->addStat(
                (new FighterStat())
                    ->setStat($stat)
                    ->setFighter($fighterInfos)
                    ->setValue(0)
            );
        }

        return $familiar->setFighterInfos($fighterInfos);
    }

    /**
     * @param Familiar $familiar
     * @return Familiar
     */
    private function generateCurrentStats(Familiar $familiar): Familiar
    {
        $stats = $familiar->getFighterInfos()->getStats();
        foreach ($stats as $fstat) {
            if ($fstat->getStat()->getNameId() === StatManager::STAMINA) {
                $familiar->getFighterInfos()->setCurrentHP(StatManager::calculateMaxHP($fstat->getValue()));
            }
            if ($fstat->getStat()->getNameId() === StatManager::WISDOM) {
                $familiar->getFighterInfos()->setCurrentMP(StatManager::calculateMaxMP($fstat->getValue()));
            }
        }
        $familiar->getFighterInfos()->setCurrentSP(0);

        return $familiar;
    }
}