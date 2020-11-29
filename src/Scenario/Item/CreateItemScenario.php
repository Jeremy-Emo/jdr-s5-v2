<?php

namespace App\Scenario\Item;

use App\AbstractClass\AbstractScenario;
use App\Entity\BattleItemInfo;
use App\Entity\Item;
use App\Exception\ScenarioException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateItemScenario extends AbstractScenario
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
     * @param FormInterface $form
     * @return Response
     * @throws ScenarioException
     */
    public function handle(FormInterface $form): Response
    {
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Item $item */
            $item = $form->getData();
            /** @var BattleItemInfo|null $itemBattleInfo */
            $itemBattleInfo = $form->get('battleItemInfo')->getData();

            if ($itemBattleInfo !== null) {
                $itemBattleInfo->setItem($item);
                $item->setBattleItemInfo($itemBattleInfo);

                foreach ($itemBattleInfo->getElementMultipliers() as $elementMultiplier) {
                    $elementMultiplier->setItem($itemBattleInfo);
                }
            }

            $this->manager->persist($item);
            $this->manager->flush();

            return $this->redirectToRoute('admin_listItems');
        }

        return $this->renderNewResponse('admin/createItem.html.twig', [
            'form' => $form->createView(),
            'title' => 'create_item',
            'specificJS' => 'collectionType'
        ]);
    }
}