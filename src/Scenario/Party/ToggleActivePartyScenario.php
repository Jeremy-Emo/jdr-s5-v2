<?php

namespace App\Scenario\Party;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Exception\ScenarioException;
use App\Repository\PartyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ToggleActivePartyScenario extends AbstractScenario
{
    /** @required  */
    public PartyRepository $partyRepository;

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
    public function handle(int $id, Account $account, $routeToRedirect = 'listParties'): Response
    {
        $defaultParty = $this->partyRepository->findOneBy([
            'mj' => $account,
            'isActive' => true
        ]);
        $partyToSetCurrent = $this->partyRepository->findOneBy([
            'mj' => $account,
            'id' => $id
        ]);

        if ($partyToSetCurrent === null || $defaultParty === null) {
            throw new ScenarioException("Heroes not found");
        }

        $defaultParty->setIsActive(false);
        $partyToSetCurrent->setIsActive(true);

        $this->manager->persist($defaultParty);
        $this->manager->persist($partyToSetCurrent);
        $this->manager->flush();

        return $this->redirectToRoute($routeToRedirect);
    }
}