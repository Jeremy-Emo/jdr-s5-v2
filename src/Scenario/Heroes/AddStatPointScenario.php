<?php

namespace App\Scenario\Heroes;

use App\AbstractClass\AbstractScenario;
use App\Entity\Hero;
use App\Repository\FighterStatRepository;
use App\Repository\StatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class AddStatPointScenario extends AbstractScenario
{
    /** @required */
    public FighterStatRepository $fsRepository;

    /** @required */
    public StatRepository $statRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    public function handle(Hero $hero, int $statId, ?int $value = 1): Response
    {
        $stat = $this->statRepository->find($statId);
        if ($stat === null) {
            return (new JsonResponse([
                'success' => false,
                'message' => "Données envoyées incorrectes"
            ], 404))->setStatusCode(404);
        }

        $fighter = $hero->getFighterInfos();

        $fs = $this->fsRepository->findOneBy([
            'fighter' => $fighter,
            'stat' => $stat
        ]);

        if ($fighter->getStatPoints() >= $value) {
            $fighter->setStatPoints($fighter->getStatPoints() - $value);
            $this->manager->persist($fighter);
            $fs->setValue($fs->getValue() + $value);
            $this->manager->persist($fs);

            $this->manager->flush();

            return new JsonResponse([
                'success' => true
            ]);
        } else {
            if ($stat === null) {
                return (new JsonResponse([
                    'success' => false,
                    'message' => "Points de stats insuffisants"
                ], 400))->setStatusCode(400);
            }
        }
    }
}