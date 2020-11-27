<?php

namespace App\Scenario\Stat;

use App\AbstractClass\AbstractScenario;
use App\Entity\FighterStat;
use App\Exception\ScenarioException;
use App\Repository\HeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateStatScenario extends AbstractScenario
{
    /** @required */
    public HeroRepository $heroRepository;

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
            $stat = $form->getData();
            $this->manager->persist($stat);

            $heroes = $this->heroRepository->findAll();
            foreach ($heroes as $hero) {
                $hero->getFighterInfos()->addStat(
                    (new FighterStat())
                        ->setValue($form->get('default')->getData())
                        ->setStat($stat)
                        ->setFighter($hero->getFighterInfos())
                );
                $this->manager->persist($hero);
            }
            $this->manager->flush();

            return $this->redirectToRoute('admin_listHeroes');
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'create_stat'
        ]);
    }
}