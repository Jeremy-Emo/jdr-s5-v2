<?php

namespace App\Scenario\Familiar;

use App\AbstractClass\AbstractScenario;
use App\Manager\StatManager;
use App\Repository\FamiliarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ToggleFamiliarInvocationScenario extends AbstractScenario
{
    /** @required */
    public FamiliarRepository $familiarRepository;

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
     * @return Response
     * @throws \Exception
     */
    public function handle(int $id): Response
    {
        $familiar = $this->familiarRepository->find($id);
        if ($familiar === null) {
            return (new JsonResponse([
                'success' => false,
                'message' => "Données envoyées incorrectes"
            ], 404))->setStatusCode(404);
        }

        $data = [];

        $leadership = StatManager::returnTotalStat(StatManager::LEADERSHIP, $familiar->getMaster()->getFighterInfos())['value'];
        $usedLeaderShip = 0;
        foreach ($familiar->getMaster()->getFamiliars() as $object) {
            if ($object->getIsInvoked()) {
                $usedLeaderShip += $object->getNeedLeaderShip();
            }
        }

        if ($familiar->getIsInvoked()) {
            $familiar->setIsInvoked(false);
            $data['usedLeadership'] = $usedLeaderShip - $familiar->getNeedLeaderShip();
        } else {
            if (($usedLeaderShip + $familiar->getNeedLeaderShip()) > $leadership) {
                return (new JsonResponse([
                    'success' => false,
                    'message' => "Pas assez de commandement !"
                ], 400))->setStatusCode(400);
            }
            $familiar->setIsInvoked(true);
            $data['usedLeadership'] = $usedLeaderShip + $familiar->getNeedLeaderShip();
        }

        $this->manager->persist($familiar);
        $this->manager->flush();

        return new JsonResponse([
            'success' => true,
            'data' => $data
        ]);
    }
}