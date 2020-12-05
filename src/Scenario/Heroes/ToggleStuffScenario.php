<?php


namespace App\Scenario\Heroes;


use App\AbstractClass\AbstractScenario;
use App\Entity\Account;
use App\Entity\FighterItem;
use App\Entity\Hero;
use App\Exception\AuthorizationException;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ToggleStuffScenario extends AbstractScenario
{
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
     * @param FighterItem $stuff
     * @param Account $currentUser
     * @return Response
     * @throws AuthorizationException
     */
    public function handle(Hero $hero, FighterItem $stuff, Account $currentUser): Response
    {
        $this->checkAuthorization($hero, $stuff, $currentUser);

        $items = $hero->getFighterInfos()->getHeroItems();

        if ($stuff->getItem()->getItemSlot() !== null) {
            $stuff->setIsEquipped(!$stuff->getIsEquipped());
            if ($stuff->getIsEquipped()) {
                foreach ($items as $item) {
                    if (
                        $item->getItem()->getItemSlot() === $stuff->getItem()->getItemSlot()
                        && $item->getId() !== $stuff->getId()
                    ) {
                        $item->setIsEquipped(false);
                        $this->manager->persist($item);
                    }
                }
            }
            $this->manager->persist($stuff);
        } elseif ($stuff->getItem()->getBattleItemInfo()->getWeaponType() !== null) {
            $stuff->setIsEquipped(!$stuff->getIsEquipped());
            if ($stuff->getIsEquipped()) {
                $handNumberAvailable = 2;
                foreach ($items as $item) {
                    if (
                        $item->getIsEquipped()
                        && $item->getItem()->getBattleItemInfo()->getWeaponType() !== null
                        && $item->getId() !== $stuff->getId()
                    ) {
                        $handNumberAvailable -= $item->getItem()->getBattleItemInfo()->getWeaponType()->getHandNumber();
                    }
                }
                if ($stuff->getItem()->getBattleItemInfo()->getWeaponType()->getHandNumber() > $handNumberAvailable) {
                    foreach ($items as $item) {
                        if (
                            $item->getIsEquipped()
                            && $item->getItem()->getBattleItemInfo()->getWeaponType() !== null
                            && $item->getId() !== $stuff->getId()
                            && $item->getItem()->getBattleItemInfo()->getWeaponType()->getHandNumber() > 0
                        ) {
                            $item->setIsEquipped(false);
                            $this->manager->persist($item);
                        }
                    }
                }
            }
            $this->manager->persist($stuff);
        }

        $this->manager->flush();

        return $this->redirectToRoute('heroInventory', [
            'id' => $hero->getId()
        ]);
    }

    /**
     * @param Hero $hero
     * @param FighterItem $stuff
     * @param Account $currentUser
     * @throws AuthorizationException
     */
    private function checkAuthorization(Hero $hero, FighterItem $stuff, Account $currentUser): void
    {
        if (
            $hero->getFighterInfos() !== $stuff->getHero()
            && !in_array(['ROLE_ADMIN', 'ROLE_MJ'], $currentUser->getRoles())
        ) {
            throw new AuthorizationException("Bad user for this stuff !");
        }
    }
}