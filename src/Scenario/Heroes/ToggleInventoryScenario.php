<?php

namespace App\Scenario\Heroes;

use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\FighterItem;
use App\Entity\Hero;
use App\Entity\PartyItem;
use App\Exception\ScenarioException;
use App\Repository\FighterItemRepository;
use App\Repository\PartyItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ToggleInventoryScenario extends AbstractScenario
{
    /** @required  */
    public PartyItemRepository $partyItemRepo;

    /** @required  */
    public FighterItemRepository $fighterItemRepo;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $urlGenerator, $twig, $logger);
    }

    /**
     * @param Hero $hero
     * @param int $stuffId
     * @param string $type
     * @return Response
     * @throws ScenarioException
     */
    public function handle(Hero $hero, int $stuffId, string $type): Response
    {
        switch ($type) {
            case 'party':
                $stuff = $this->partyItemRepo->findOneBy([
                    'id' => $stuffId,
                    'party' => $hero->getParty(),
                ]);
                break;
            case 'hero':
                dump($stuffId);
                $stuff = $this->fighterItemRepo->findOneBy([
                    'id' => $stuffId,
                    'hero' => $hero->getFighterInfos(),
                ]);
                break;
            default:
                $stuff = null;
                break;
        }

        if ($stuff === null) {
            throw new NotFoundHttpException("Stuff not found");
        }

        if ($stuff instanceof PartyItem) {
            $item = (new FighterItem())
                ->setItem($stuff->getItem())
                ->setDurability($stuff->getDurability())
                ->setIsEquipped(false)
                ->setHero($hero->getFighterInfos())
            ;
        } elseif ($stuff instanceof FighterItem) {
            $item = (new PartyItem())
                ->setDurability($stuff->getDurability())
                ->setItem($stuff->getItem())
                ->setParty($hero->getParty())
            ;
        } else {
            throw new ScenarioException("Type error");
        }

        $this->manager->persist($item);
        $this->manager->remove($stuff);
        $this->manager->flush();

        return $this->redirectToRoute('heroInventory', [
            'id' => $hero->getId(),
        ]);
    }
}