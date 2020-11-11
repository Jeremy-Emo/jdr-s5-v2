<?php

namespace App\Scenario\Heroes;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Exception\ControllerException;
use App\Repository\HeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ToggleActiveHeroScenario extends AbstractScenario
{
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    /** @required */
    public HeroRepository $heroRepository;

    /** @required */
    public EntityManagerInterface $em;

    /**
     * @param int $id
     * @param Account $account
     * @param string $routeToRedirect
     * @return Response
     * @throws ControllerException
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
            throw new ControllerException("Heroes not found");
        }

        $defaultHero->setIsCurrent(false);
        $heroToSetCurrent->setIsCurrent(true);

        $this->em->persist($defaultHero);
        $this->em->persist($heroToSetCurrent);
        $this->em->flush();

        return $this->redirectToRoute($routeToRedirect);
    }
}