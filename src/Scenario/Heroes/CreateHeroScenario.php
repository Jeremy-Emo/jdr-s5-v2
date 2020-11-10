<?php

namespace App\Scenario\Heroes;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\Hero;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateHeroScenario extends AbstractScenario
{
    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, Environment $twig)
    {
        parent::__construct($entityManager, $urlGenerator, $twig);
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

            $this->manager->persist($hero->setAccount($user));
            $this->manager->flush();

            return $this->redirectToRoute('listHeroes');
        }

        return $this->renderNewResponse('heroes/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}