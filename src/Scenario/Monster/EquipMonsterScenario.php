<?php

namespace App\Scenario\Monster;

use App\AbstractClass\AbstractScenario;
use App\Entity\FighterItem;
use App\Entity\Monster;
use App\Exception\ScenarioException;
use App\Repository\ItemSlotRepository;
use App\Repository\WeaponTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EquipMonsterScenario extends AbstractScenario
{
    /** @required  */
    public ItemSlotRepository $itemSlotRepository;

    /** @required  */
    public WeaponTypeRepository $weaponTypeRepository;

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
     * @param Monster $monster
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form, Monster $monster): Response
    {
        $fighter = $monster->getFighterInfos();

        if ($form->isSubmitted() && $form->isValid()) {
            //Delete existing equipped stuff, we will recreate all
            foreach ($fighter->getHeroItems() as $existingItem) {
                if ($existingItem->getIsEquipped()) {
                    $this->manager->remove($existingItem);
                }
            }

            //Get and persist non-weapon stuff
            $fields = $form;
            foreach ($fields as $key => $data) {
                /** @var FighterItem $item */
                $item = $data->getData();
                if (!empty($item) && $key !== "weapons") {
                    $item->setHero($fighter)->setIsEquipped(true);
                    $this->manager->persist($item);
                }
            }

            //Get and persist weapons
            $weaponsToEquip = $form->get('weapons')->getData();
            foreach ($weaponsToEquip as $weapon) {
                $monsterItem = (new FighterItem())
                    ->setHero($fighter)
                    ->setItem($weapon)
                    ->setIsEquipped(true);
                $this->manager->persist($monsterItem);
            }

            $this->manager->flush();
            return $this->redirectToRoute('admin_listMonsters');
        }

        return $this->renderNewResponse('admin/defaultGenericForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'equip_monster',
        ]);
    }
}