<?php

namespace App\Scenario\Heroes;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Exception\ScenarioException;
use App\Repository\HeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ToggleActiveHeroScenario extends AbstractScenario
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
     * @param int $id
     * @param Account $account
     * @param string $routeToRedirect
     * @return Response
     * @throws ScenarioException
     */
    public function handle(int $id, Account $account, $routeToRedirect = 'listHeroes'): Response
    {
        $defaultHero = $this->heroRepository->findOneBy([
            'account' => $account,
            'isCurrent' => true
        ]);
        $heroToSetCurrent = $this->heroRepository->findOneBy([
            'account' => $account,
            'id' => $id
        ]);

        if ($heroToSetCurrent === null || $defaultHero === null) {
            throw new ScenarioException("Heroes not found");
        }

        $defaultHero->setIsCurrent(false);
        $heroToSetCurrent->setIsCurrent(true);

        $this->manager->persist($defaultHero);
        $this->manager->persist($heroToSetCurrent);
        $this->manager->flush();

        return $this->redirectToRoute($routeToRedirect);
    }
}